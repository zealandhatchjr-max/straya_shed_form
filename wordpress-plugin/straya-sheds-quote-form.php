<?php
/**
 * Plugin Name: Straya Sheds Quote Form
 * Description: Adds the Straya Sheds quote form at /form and stores enquiries in WordPress.
 * Version: 1.0.0
 * Author: Straya Sheds
 * Text Domain: straya-sheds-quote-form
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'STRAYA_SHEDS_QUOTE_FORM_VERSION', '1.0.0' );
define( 'STRAYA_SHEDS_QUOTE_FORM_PATH', plugin_dir_path( __FILE__ ) );
define( 'STRAYA_SHEDS_QUOTE_FORM_URL', plugin_dir_url( __FILE__ ) );

function straya_sheds_quote_form_google_maps_api_key() {
	if ( defined( 'STRAYA_SHEDS_GOOGLE_MAPS_API_KEY' ) ) {
		return (string) STRAYA_SHEDS_GOOGLE_MAPS_API_KEY;
	}

	$env_value = getenv( 'STRAYA_SHEDS_GOOGLE_MAPS_API_KEY' );
	if ( false !== $env_value ) {
		return (string) $env_value;
	}

	return '';
}

function straya_sheds_quote_form_register_post_type() {
	register_post_type(
		'straya_shed_quote',
		array(
			'labels'       => array(
				'name'          => __( 'Shed enquiries', 'straya-sheds-quote-form' ),
				'singular_name' => __( 'Shed enquiry', 'straya-sheds-quote-form' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'show_in_menu' => true,
			'menu_icon'    => 'dashicons-feedback',
			'supports'     => array( 'title', 'custom-fields' ),
		)
	);
}
add_action( 'init', 'straya_sheds_quote_form_register_post_type' );

function straya_sheds_quote_form_activate() {
	straya_sheds_quote_form_register_post_type();

	$page = get_page_by_path( 'form' );
	if ( ! $page ) {
		wp_insert_post(
			array(
				'post_title'   => 'Shed quote form',
				'post_name'    => 'form',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => '[straya_sheds_quote_form]',
			)
		);
	} elseif ( false === strpos( $page->post_content, '[straya_sheds_quote_form]' ) ) {
		wp_update_post(
			array(
				'ID'           => $page->ID,
				'post_content' => '[straya_sheds_quote_form]',
			)
		);
	}

	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'straya_sheds_quote_form_activate' );

function straya_sheds_quote_form_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'straya_sheds_quote_form_deactivate' );

function straya_sheds_quote_form_has_shortcode() {
	if ( is_page( 'form' ) ) {
		return true;
	}

	global $post;
	return $post && has_shortcode( $post->post_content, 'straya_sheds_quote_form' );
}

function straya_sheds_quote_form_enqueue_assets() {
	if ( ! straya_sheds_quote_form_has_shortcode() ) {
		return;
	}

	wp_enqueue_style(
		'straya-sheds-quote-form',
		STRAYA_SHEDS_QUOTE_FORM_URL . 'styles.css',
		array(),
		STRAYA_SHEDS_QUOTE_FORM_VERSION
	);

	wp_enqueue_script(
		'straya-sheds-quote-form',
		STRAYA_SHEDS_QUOTE_FORM_URL . 'app.js',
		array(),
		STRAYA_SHEDS_QUOTE_FORM_VERSION,
		true
	);

	wp_localize_script(
		'straya-sheds-quote-form',
		'SHED_QUOTE_CONFIG',
		array(
			'googleMapsApiKey' => straya_sheds_quote_form_google_maps_api_key(),
			'addressCountry'   => 'au',
			'wordpressMode'    => true,
			'restUrl'          => esc_url_raw( rest_url( 'straya-sheds/v1/enquiries' ) ),
			'restNonce'        => wp_create_nonce( 'wp_rest' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'straya_sheds_quote_form_enqueue_assets' );

function straya_sheds_quote_form_shortcode() {
	$template_path = STRAYA_SHEDS_QUOTE_FORM_PATH . 'template.html';
	if ( ! file_exists( $template_path ) ) {
		return '';
	}

	$html = file_get_contents( $template_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	$html = preg_replace( '/^.*?<body>/is', '', $html );
	$html = preg_replace( '/<script\b[^>]*>.*?<\/script>\s*/is', '', $html );
	$html = preg_replace( '/<\/body>.*$/is', '', $html );
	$html = str_replace( './assets/', esc_url( STRAYA_SHEDS_QUOTE_FORM_URL . 'assets/' ), $html );
	$html = str_replace( 'Local development', 'Straya Sheds', $html );
	$html = str_replace(
		'For local development, uploaded file names are recorded only in your browser.',
		'Uploaded file names are included with your enquiry.',
		$html
	);

	return '<div class="straya-sheds-quote-form-embed">' . $html . '</div>';
}
add_shortcode( 'straya_sheds_quote_form', 'straya_sheds_quote_form_shortcode' );

function straya_sheds_quote_form_register_rest_routes() {
	register_rest_route(
		'straya-sheds/v1',
		'/enquiries',
		array(
			'methods'             => 'POST',
			'callback'            => 'straya_sheds_quote_form_receive_enquiry',
			'permission_callback' => 'straya_sheds_quote_form_verify_rest_nonce',
		)
	);
}
add_action( 'rest_api_init', 'straya_sheds_quote_form_register_rest_routes' );

function straya_sheds_quote_form_verify_rest_nonce( WP_REST_Request $request ) {
	$nonce = $request->get_header( 'X-WP-Nonce' );
	return (bool) wp_verify_nonce( $nonce, 'wp_rest' );
}

function straya_sheds_quote_form_clean_value( $value ) {
	if ( is_array( $value ) ) {
		return array_map( 'straya_sheds_quote_form_clean_value', $value );
	}

	if ( is_bool( $value ) || is_numeric( $value ) ) {
		return $value;
	}

	return sanitize_text_field( (string) $value );
}

function straya_sheds_quote_form_receive_enquiry( WP_REST_Request $request ) {
	$quote = straya_sheds_quote_form_clean_value( $request->get_json_params() );

	$customer_name = isset( $quote['customerName'] ) ? $quote['customerName'] : '';
	$email         = isset( $quote['email'] ) ? sanitize_email( $quote['email'] ) : '';
	$phone         = isset( $quote['phone'] ) ? $quote['phone'] : '';
	$address       = isset( $quote['deliveryAddress'] ) ? $quote['deliveryAddress'] : '';

	if ( '' === $customer_name || '' === $email || '' === $phone || '' === $address ) {
		return new WP_Error(
			'straya_sheds_missing_required_fields',
			__( 'Required customer and delivery fields are missing.', 'straya-sheds-quote-form' ),
			array( 'status' => 400 )
		);
	}

	$post_id = wp_insert_post(
		array(
			'post_type'   => 'straya_shed_quote',
			'post_status' => 'private',
			'post_title'  => sprintf(
				'%s - %s',
				$customer_name,
				wp_date( 'Y-m-d H:i' )
			),
			'meta_input'  => array(
				'straya_sheds_quote_data' => wp_json_encode( $quote ),
				'customer_name'           => $customer_name,
				'customer_email'          => $email,
				'customer_phone'          => $phone,
				'delivery_address'        => $address,
			),
		),
		true
	);

	if ( is_wp_error( $post_id ) ) {
		return $post_id;
	}

	straya_sheds_quote_form_send_notification( $quote, $post_id );

	return rest_ensure_response(
		array(
			'ok'        => true,
			'enquiryId' => $post_id,
		)
	);
}

function straya_sheds_quote_form_send_notification( array $quote, $post_id ) {
	$dimensions = isset( $quote['dimensions'] ) && is_array( $quote['dimensions'] ) ? $quote['dimensions'] : array();
	$options    = isset( $quote['options'] ) && is_array( $quote['options'] ) ? $quote['options'] : array();
	$walls      = ! empty( $quote['selectedWalls'] ) && is_array( $quote['selectedWalls'] ) ? implode( ', ', $quote['selectedWalls'] ) : 'None - roof only';

	$message = sprintf(
		"New shed quote enquiry #%d\n\nName: %s\nEmail: %s\nPhone: %s\nAddress: %s\n\nDimensions: %s m L x %s m W x %s m H\nRoof type: %s\nRoof pitch: %s\nWalls: %s\nWall material: %s\n150 C purlins: %s\nStructural steel: %s\nBay width: %s m\nInsulation blankets: %s\n\nNotes:\n%s\n\nView it in WordPress admin under Shed enquiries.",
		$post_id,
		isset( $quote['customerName'] ) ? $quote['customerName'] : '',
		isset( $quote['email'] ) ? $quote['email'] : '',
		isset( $quote['phone'] ) ? $quote['phone'] : '',
		isset( $quote['deliveryAddress'] ) ? $quote['deliveryAddress'] : '',
		isset( $dimensions['length'] ) ? $dimensions['length'] : '',
		isset( $dimensions['width'] ) ? $dimensions['width'] : '',
		isset( $dimensions['height'] ) ? $dimensions['height'] : '',
		isset( $quote['roofType'] ) ? $quote['roofType'] : '',
		isset( $quote['roofPitch'] ) ? $quote['roofPitch'] : '',
		$walls,
		isset( $quote['wallMaterial'] ) ? $quote['wallMaterial'] : '',
		! empty( $options['purlins150c'] ) ? 'Yes' : 'No',
		! empty( $options['structuralSteel'] ) ? 'Yes' : 'No',
		isset( $options['bayWidth'] ) ? $options['bayWidth'] : '',
		! empty( $options['insulationBlankets'] ) ? 'Yes' : 'No',
		isset( $quote['notes'] ) ? $quote['notes'] : ''
	);

	wp_mail(
		get_option( 'admin_email' ),
		sprintf( 'New shed quote enquiry #%d', $post_id ),
		$message
	);
}

function straya_sheds_quote_form_add_meta_boxes() {
	add_meta_box(
		'straya-sheds-quote-detail',
		__( 'Enquiry details', 'straya-sheds-quote-form' ),
		'straya_sheds_quote_form_render_meta_box',
		'straya_shed_quote',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'straya_sheds_quote_form_add_meta_boxes' );

function straya_sheds_quote_form_render_meta_box( WP_Post $post ) {
	$raw   = get_post_meta( $post->ID, 'straya_sheds_quote_data', true );
	$quote = json_decode( $raw, true );

	if ( ! is_array( $quote ) ) {
		echo '<p>' . esc_html__( 'No enquiry data found.', 'straya-sheds-quote-form' ) . '</p>';
		return;
	}

	$dimensions = isset( $quote['dimensions'] ) && is_array( $quote['dimensions'] ) ? $quote['dimensions'] : array();
	$options    = isset( $quote['options'] ) && is_array( $quote['options'] ) ? $quote['options'] : array();
	$walls      = ! empty( $quote['selectedWalls'] ) && is_array( $quote['selectedWalls'] ) ? implode( ', ', $quote['selectedWalls'] ) : 'None - roof only';
	$files      = ! empty( $quote['uploadedFiles'] ) && is_array( $quote['uploadedFiles'] ) ? implode( ', ', $quote['uploadedFiles'] ) : 'None';

	$rows = array(
		'Name'                 => isset( $quote['customerName'] ) ? $quote['customerName'] : '',
		'Email'                => isset( $quote['email'] ) ? $quote['email'] : '',
		'Phone'                => isset( $quote['phone'] ) ? $quote['phone'] : '',
		'Delivery address'     => isset( $quote['deliveryAddress'] ) ? $quote['deliveryAddress'] : '',
		'Dimensions'           => sprintf(
			'%s m length x %s m width x %s m height',
			isset( $dimensions['length'] ) ? $dimensions['length'] : '',
			isset( $dimensions['width'] ) ? $dimensions['width'] : '',
			isset( $dimensions['height'] ) ? $dimensions['height'] : ''
		),
		'Roof type'            => isset( $quote['roofType'] ) ? $quote['roofType'] : '',
		'Roof pitch'           => isset( $quote['roofPitch'] ) ? $quote['roofPitch'] : '',
		'Selected walls'       => $walls,
		'Wall material'        => isset( $quote['wallMaterial'] ) ? $quote['wallMaterial'] : '',
		'150 C purlins'        => ! empty( $options['purlins150c'] ) ? 'Yes' : 'No',
		'Structural steel'     => ! empty( $options['structuralSteel'] ) ? 'Yes' : 'No',
		'Bay width'            => isset( $options['bayWidth'] ) && '' !== $options['bayWidth'] ? $options['bayWidth'] . ' m' : 'Not applicable',
		'Insulation blankets'  => ! empty( $options['insulationBlankets'] ) ? 'Yes' : 'No',
		'Uploaded file names'  => $files,
		'Notes'                => isset( $quote['notes'] ) ? $quote['notes'] : '',
	);

	echo '<table class="widefat striped"><tbody>';
	foreach ( $rows as $label => $value ) {
		echo '<tr><th style="width:220px;">' . esc_html( $label ) . '</th><td>' . esc_html( $value ) . '</td></tr>';
	}
	echo '</tbody></table>';
}

function straya_sheds_quote_form_admin_columns( $columns ) {
	$columns['customer_email']   = __( 'Email', 'straya-sheds-quote-form' );
	$columns['customer_phone']   = __( 'Phone', 'straya-sheds-quote-form' );
	$columns['delivery_address'] = __( 'Delivery address', 'straya-sheds-quote-form' );
	return $columns;
}
add_filter( 'manage_straya_shed_quote_posts_columns', 'straya_sheds_quote_form_admin_columns' );

function straya_sheds_quote_form_admin_column_content( $column, $post_id ) {
	if ( in_array( $column, array( 'customer_email', 'customer_phone', 'delivery_address' ), true ) ) {
		echo esc_html( get_post_meta( $post_id, $column, true ) );
	}
}
add_action( 'manage_straya_shed_quote_posts_custom_column', 'straya_sheds_quote_form_admin_column_content', 10, 2 );
