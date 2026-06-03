<?php
/**
 * Plugin Name: Straya Sheds Quote Form
 * Description: Adds the Straya Sheds quote form at /form and stores enquiries in WordPress.
 * Version: 1.1.23
 * Author: Straya Sheds
 * Text Domain: straya-sheds-quote-form
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'STRAYA_SHEDS_QUOTE_FORM_VERSION', '1.1.23' );
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

function straya_sheds_quote_form_monday_api_token() {
	if ( defined( 'STRAYA_SHEDS_MONDAY_API_TOKEN' ) ) {
		return (string) STRAYA_SHEDS_MONDAY_API_TOKEN;
	}

	$env_value = getenv( 'STRAYA_SHEDS_MONDAY_API_TOKEN' );
	if ( false !== $env_value ) {
		return (string) $env_value;
	}

	return '';
}

function straya_sheds_quote_form_monday_board_id() {
	if ( defined( 'STRAYA_SHEDS_MONDAY_BOARD_ID' ) ) {
		return (string) STRAYA_SHEDS_MONDAY_BOARD_ID;
	}

	$env_value = getenv( 'STRAYA_SHEDS_MONDAY_BOARD_ID' );
	if ( false !== $env_value ) {
		return (string) $env_value;
	}

	return '';
}

function straya_sheds_quote_form_monday_group_id() {
	if ( defined( 'STRAYA_SHEDS_MONDAY_GROUP_ID' ) ) {
		return (string) STRAYA_SHEDS_MONDAY_GROUP_ID;
	}

	$env_value = getenv( 'STRAYA_SHEDS_MONDAY_GROUP_ID' );
	if ( false !== $env_value ) {
		return (string) $env_value;
	}

	return '';
}

function straya_sheds_quote_form_monday_columns() {
	$columns = defined( 'STRAYA_SHEDS_MONDAY_COLUMNS' ) && is_array( STRAYA_SHEDS_MONDAY_COLUMNS )
		? STRAYA_SHEDS_MONDAY_COLUMNS
		: array();

	return apply_filters( 'straya_sheds_quote_form_monday_columns', $columns );
}

function straya_sheds_quote_form_monday_column_value( $value, $type ) {
	$type  = strtolower( (string) $type );
	$value = is_array( $value ) ? implode( ', ', array_filter( $value ) ) : (string) $value;
	$value = trim( $value );

	if ( '' === $value ) {
		return '';
	}

	if ( 'status' === $type ) {
		return array( 'label' => $value );
	}

	if ( 'dropdown' === $type ) {
		return array( 'labels' => array( $value ) );
	}

	if ( 'email' === $type ) {
		return array(
			'email' => sanitize_email( $value ),
			'text'  => $value,
		);
	}

	if ( 'phone' === $type ) {
		return array(
			'phone'            => $value,
			'countryShortName' => 'AU',
		);
	}

	if ( 'numbers' === $type ) {
		return preg_replace( '/[^0-9.\-]/', '', $value );
	}

	return $value;
}

function straya_sheds_quote_form_monday_column_definition( $columns, $field ) {
	if ( empty( $columns[ $field ] ) ) {
		return null;
	}

	if ( is_array( $columns[ $field ] ) ) {
		return array(
			'id'   => isset( $columns[ $field ]['id'] ) ? (string) $columns[ $field ]['id'] : '',
			'type' => isset( $columns[ $field ]['type'] ) ? (string) $columns[ $field ]['type'] : 'text',
		);
	}

	return array(
		'id'   => (string) $columns[ $field ],
		'type' => 'text',
	);
}

function straya_sheds_quote_form_quote_field_value( array $quote, $field, $post_id ) {
	$dimensions = isset( $quote['dimensions'] ) && is_array( $quote['dimensions'] ) ? $quote['dimensions'] : array();
	$options    = isset( $quote['options'] ) && is_array( $quote['options'] ) ? $quote['options'] : array();

	switch ( $field ) {
		case 'customer_name':
			return isset( $quote['customerName'] ) ? $quote['customerName'] : '';
		case 'email':
			return isset( $quote['email'] ) ? $quote['email'] : '';
		case 'phone':
			return isset( $quote['phone'] ) ? $quote['phone'] : '';
		case 'delivery_address':
			return isset( $quote['deliveryAddress'] ) ? $quote['deliveryAddress'] : '';
		case 'length':
			return isset( $dimensions['length'] ) ? $dimensions['length'] : '';
		case 'width':
			return isset( $dimensions['width'] ) ? $dimensions['width'] : '';
		case 'height':
			return isset( $dimensions['height'] ) ? $dimensions['height'] : '';
		case 'dimensions':
			return sprintf(
				'%s m length x %s m width x %s m height',
				isset( $dimensions['length'] ) ? $dimensions['length'] : '',
				isset( $dimensions['width'] ) ? $dimensions['width'] : '',
				isset( $dimensions['height'] ) ? $dimensions['height'] : ''
			);
		case 'sheet_color':
			return isset( $quote['sheetColor'] ) ? $quote['sheetColor'] : '';
		case 'roof_type':
			return isset( $quote['roofType'] ) ? $quote['roofType'] : '';
		case 'roof_pitch':
			return isset( $quote['roofPitch'] ) ? $quote['roofPitch'] : '';
		case 'selected_walls':
			return ! empty( $quote['selectedWalls'] ) && is_array( $quote['selectedWalls'] ) ? implode( ', ', $quote['selectedWalls'] ) : 'None - roof only';
		case 'wall_count':
			return isset( $quote['wallCount'] ) ? $quote['wallCount'] : '';
		case 'wall_material':
			return isset( $quote['wallMaterial'] ) ? $quote['wallMaterial'] : '';
		case 'purlins_150c':
			return ! empty( $options['purlins150c'] ) ? 'Yes' : 'No';
		case 'structural_steel':
			return ! empty( $options['structuralSteel'] ) ? 'Yes' : 'No';
		case 'bay_width':
			return isset( $options['bayWidth'] ) ? $options['bayWidth'] : '';
		case 'insulation_blankets':
			return ! empty( $options['insulationBlankets'] ) ? 'Yes' : 'No';
		case 'uploaded_files':
			return ! empty( $quote['uploadedFiles'] ) && is_array( $quote['uploadedFiles'] ) ? implode( ', ', $quote['uploadedFiles'] ) : '';
		case 'notes':
			return isset( $quote['notes'] ) ? $quote['notes'] : '';
		case 'wordpress_enquiry_id':
			return (string) $post_id;
		case 'submitted_at':
			return isset( $quote['submittedAt'] ) ? $quote['submittedAt'] : '';
		default:
			return isset( $quote[ $field ] ) ? $quote[ $field ] : '';
	}
}

function straya_sheds_quote_form_monday_column_values( array $quote, $post_id ) {
	$columns       = straya_sheds_quote_form_monday_columns();
	$column_values = array();

	foreach ( array_keys( $columns ) as $field ) {
		$definition = straya_sheds_quote_form_monday_column_definition( $columns, $field );
		if ( ! $definition || '' === $definition['id'] ) {
			continue;
		}

		$value = straya_sheds_quote_form_quote_field_value( $quote, $field, $post_id );
		$value = straya_sheds_quote_form_monday_column_value( $value, $definition['type'] );

		if ( '' !== $value && array() !== $value ) {
			$column_values[ $definition['id'] ] = $value;
		}
	}

	return $column_values;
}

function straya_sheds_quote_form_sync_monday( array $quote, $post_id ) {
	$token   = straya_sheds_quote_form_monday_api_token();
	$board_id = straya_sheds_quote_form_monday_board_id();
	$columns = straya_sheds_quote_form_monday_columns();

	if ( '' === $token || '' === $board_id || empty( $columns ) ) {
		return new WP_Error(
			'straya_sheds_monday_not_configured',
			__( 'monday.com sync is not configured.', 'straya-sheds-quote-form' )
		);
	}

	$item_name = sprintf(
		'%s - %s',
		isset( $quote['customerName'] ) ? $quote['customerName'] : __( 'New shed enquiry', 'straya-sheds-quote-form' ),
		isset( $quote['deliveryAddress'] ) ? $quote['deliveryAddress'] : wp_date( 'Y-m-d H:i' )
	);

	$group_id = straya_sheds_quote_form_monday_group_id();
	$mutation = '' === $group_id
		? 'mutation CreateEnquiry($boardId: ID!, $itemName: String!, $columnValues: JSON!) {
			create_item(board_id: $boardId, item_name: $itemName, column_values: $columnValues) {
				id
				name
				url
			}
		}'
		: 'mutation CreateEnquiry($boardId: ID!, $itemName: String!, $columnValues: JSON!, $groupId: String!) {
			create_item(board_id: $boardId, group_id: $groupId, item_name: $itemName, column_values: $columnValues) {
				id
				name
				url
			}
		}';

	$variables = array(
		'boardId'      => $board_id,
		'itemName'     => $item_name,
		'columnValues' => wp_json_encode( straya_sheds_quote_form_monday_column_values( $quote, $post_id ) ),
	);

	if ( '' !== $group_id ) {
		$variables['groupId'] = $group_id;
	}

	$response = wp_remote_post(
		'https://api.monday.com/v2',
		array(
			'timeout' => 20,
			'headers' => array(
				'Authorization' => $token,
				'Content-Type'  => 'application/json',
				'API-Version'   => '2026-04',
			),
			'body'    => wp_json_encode(
				array(
					'query'     => $mutation,
					'variables' => $variables,
				)
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		return $response;
	}

	$response_code = wp_remote_retrieve_response_code( $response );
	$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

	if ( $response_code < 200 || $response_code >= 300 ) {
		return new WP_Error(
			'straya_sheds_monday_http_error',
			sprintf( 'monday.com returned HTTP %d.', $response_code )
		);
	}

	if ( ! empty( $response_body['errors'] ) ) {
		$message = isset( $response_body['errors'][0]['message'] ) ? $response_body['errors'][0]['message'] : __( 'monday.com returned an API error.', 'straya-sheds-quote-form' );
		return new WP_Error( 'straya_sheds_monday_api_error', $message );
	}

	if ( empty( $response_body['data']['create_item']['id'] ) ) {
		return new WP_Error(
			'straya_sheds_monday_missing_item',
			__( 'monday.com did not return a created item ID.', 'straya-sheds-quote-form' )
		);
	}

	return $response_body['data']['create_item'];
}

function straya_sheds_quote_form_record_monday_sync( $post_id, $result ) {
	if ( is_wp_error( $result ) ) {
		$status = 'straya_sheds_monday_not_configured' === $result->get_error_code() ? 'not configured' : 'failed';
		update_post_meta( $post_id, 'monday_sync_status', $status );
		update_post_meta( $post_id, 'monday_sync_error', $result->get_error_message() );
		update_post_meta( $post_id, 'monday_synced_at', current_time( 'mysql' ) );
		return;
	}

	update_post_meta( $post_id, 'monday_sync_status', 'synced' );
	update_post_meta( $post_id, 'monday_sync_error', '' );
	update_post_meta( $post_id, 'monday_item_id', isset( $result['id'] ) ? $result['id'] : '' );
	update_post_meta( $post_id, 'monday_item_url', isset( $result['url'] ) ? $result['url'] : '' );
	update_post_meta( $post_id, 'monday_synced_at', current_time( 'mysql' ) );
	delete_post_meta( $post_id, 'monday_retry_scheduled_at' );
}

function straya_sheds_quote_form_get_quote_data( $post_id ) {
	$post = get_post( $post_id );
	if ( ! $post || 'straya_shed_quote' !== $post->post_type ) {
		return new WP_Error(
			'straya_sheds_quote_not_found',
			__( 'Enquiry not found.', 'straya-sheds-quote-form' )
		);
	}

	$raw   = get_post_meta( $post_id, 'straya_sheds_quote_data', true );
	$quote = json_decode( $raw, true );

	if ( ! is_array( $quote ) ) {
		return new WP_Error(
			'straya_sheds_quote_data_missing',
			__( 'No enquiry data found.', 'straya-sheds-quote-form' )
		);
	}

	return $quote;
}

function straya_sheds_quote_form_retry_monday_sync( $post_id ) {
	$post_id = absint( $post_id );
	if ( ! $post_id ) {
		return new WP_Error(
			'straya_sheds_quote_not_found',
			__( 'Enquiry not found.', 'straya-sheds-quote-form' )
		);
	}

	if ( 'synced' === get_post_meta( $post_id, 'monday_sync_status', true ) ) {
		return true;
	}

	$quote = straya_sheds_quote_form_get_quote_data( $post_id );
	if ( is_wp_error( $quote ) ) {
		straya_sheds_quote_form_record_monday_sync( $post_id, $quote );
		return $quote;
	}

	update_post_meta( $post_id, 'monday_sync_status', 'retrying' );
	update_post_meta( $post_id, 'monday_sync_error', '' );

	$result = straya_sheds_quote_form_sync_monday( $quote, $post_id );
	straya_sheds_quote_form_record_monday_sync( $post_id, $result );

	return $result;
}

function straya_sheds_quote_form_schedule_monday_retry( $post_id, $delay = 300 ) {
	$post_id = absint( $post_id );
	if ( ! $post_id || 'synced' === get_post_meta( $post_id, 'monday_sync_status', true ) ) {
		return;
	}

	if ( wp_next_scheduled( 'straya_sheds_quote_form_retry_monday_event', array( $post_id ) ) ) {
		return;
	}

	wp_schedule_single_event(
		time() + absint( $delay ),
		'straya_sheds_quote_form_retry_monday_event',
		array( $post_id )
	);
	update_post_meta( $post_id, 'monday_retry_scheduled_at', current_time( 'mysql' ) );
}
add_action( 'straya_sheds_quote_form_retry_monday_event', 'straya_sheds_quote_form_retry_monday_sync', 10, 1 );

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

function straya_sheds_quote_form_normalize_au_phone( $value ) {
	$phone = preg_replace( '/[\s().-]+/', '', (string) $value );
	if ( 0 === strpos( $phone, '+61' ) ) {
		$phone = '0' . substr( $phone, 3 );
	}

	return $phone;
}

function straya_sheds_quote_form_format_au_phone( $value ) {
	$phone = straya_sheds_quote_form_normalize_au_phone( $value );

	if ( preg_match( '/^04\d{8}$/', $phone ) ) {
		return substr( $phone, 0, 4 ) . ' ' . substr( $phone, 4, 3 ) . ' ' . substr( $phone, 7 );
	}

	if ( preg_match( '/^0[2378]\d{8}$/', $phone ) ) {
		return substr( $phone, 0, 2 ) . ' ' . substr( $phone, 2, 4 ) . ' ' . substr( $phone, 6 );
	}

	if ( preg_match( '/^1[38]00\d{6}$/', $phone ) ) {
		return substr( $phone, 0, 4 ) . ' ' . substr( $phone, 4, 3 ) . ' ' . substr( $phone, 7 );
	}

	return '';
}

function straya_sheds_quote_form_receive_enquiry( WP_REST_Request $request ) {
	$quote = straya_sheds_quote_form_clean_value( $request->get_json_params() );

	$customer_name = isset( $quote['customerName'] ) ? $quote['customerName'] : '';
	$email         = isset( $quote['email'] ) ? sanitize_email( $quote['email'] ) : '';
	$phone         = isset( $quote['phone'] ) ? $quote['phone'] : '';
	$address       = isset( $quote['deliveryAddress'] ) ? $quote['deliveryAddress'] : '';
	$sheet_color   = isset( $quote['sheetColor'] ) ? $quote['sheetColor'] : '';

	if ( '' === $customer_name || '' === $email || '' === $phone || '' === $address || '' === $sheet_color ) {
		return new WP_Error(
			'straya_sheds_missing_required_fields',
			__( 'Required customer, delivery, and sheet colour fields are missing.', 'straya-sheds-quote-form' ),
			array( 'status' => 400 )
		);
	}

	$phone = straya_sheds_quote_form_format_au_phone( $phone );
	if ( '' === $phone ) {
		return new WP_Error(
			'straya_sheds_invalid_phone',
			__( 'Enter a complete Australian phone number.', 'straya-sheds-quote-form' ),
			array( 'status' => 400 )
		);
	}
	$quote['phone'] = $phone;

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
				'sheet_color'             => $sheet_color,
			),
		),
		true
	);

	if ( is_wp_error( $post_id ) ) {
		return $post_id;
	}

	straya_sheds_quote_form_send_notification( $quote, $post_id );
	$monday_result = straya_sheds_quote_form_sync_monday( $quote, $post_id );
	straya_sheds_quote_form_record_monday_sync( $post_id, $monday_result );

	if ( is_wp_error( $monday_result ) ) {
		straya_sheds_quote_form_schedule_monday_retry( $post_id );
	}

	return rest_ensure_response(
		array(
			'ok'               => true,
			'enquiryId'        => $post_id,
			'mondaySyncStatus' => get_post_meta( $post_id, 'monday_sync_status', true ),
		)
	);
}

function straya_sheds_quote_form_send_notification( array $quote, $post_id ) {
	$dimensions = isset( $quote['dimensions'] ) && is_array( $quote['dimensions'] ) ? $quote['dimensions'] : array();
	$options    = isset( $quote['options'] ) && is_array( $quote['options'] ) ? $quote['options'] : array();
	$walls      = ! empty( $quote['selectedWalls'] ) && is_array( $quote['selectedWalls'] ) ? implode( ', ', $quote['selectedWalls'] ) : 'None - roof only';

	$message = sprintf(
		"New shed quote enquiry #%d\n\nName: %s\nEmail: %s\nPhone: %s\nAddress: %s\n\nDimensions: %s m L x %s m W x %s m H\nSheet colour: %s\nRoof type: %s\nRoof pitch: %s\nWalls: %s\nWall material: %s\n150 C purlins: %s\nStructural steel: %s\nBay width: %s m\nInsulation blankets: %s\n\nNotes:\n%s\n\nView it in WordPress admin under Shed enquiries.",
		$post_id,
		isset( $quote['customerName'] ) ? $quote['customerName'] : '',
		isset( $quote['email'] ) ? $quote['email'] : '',
		isset( $quote['phone'] ) ? $quote['phone'] : '',
		isset( $quote['deliveryAddress'] ) ? $quote['deliveryAddress'] : '',
		isset( $dimensions['length'] ) ? $dimensions['length'] : '',
		isset( $dimensions['width'] ) ? $dimensions['width'] : '',
		isset( $dimensions['height'] ) ? $dimensions['height'] : '',
		isset( $quote['sheetColor'] ) ? $quote['sheetColor'] : '',
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
	$quote = straya_sheds_quote_form_get_quote_data( $post->ID );

	if ( is_wp_error( $quote ) ) {
		echo '<p>' . esc_html( $quote->get_error_message() ) . '</p>';
		return;
	}

	$dimensions = isset( $quote['dimensions'] ) && is_array( $quote['dimensions'] ) ? $quote['dimensions'] : array();
	$options    = isset( $quote['options'] ) && is_array( $quote['options'] ) ? $quote['options'] : array();
	$walls      = ! empty( $quote['selectedWalls'] ) && is_array( $quote['selectedWalls'] ) ? implode( ', ', $quote['selectedWalls'] ) : 'None - roof only';
	$files      = ! empty( $quote['uploadedFiles'] ) && is_array( $quote['uploadedFiles'] ) ? implode( ', ', $quote['uploadedFiles'] ) : 'None';
	$monday_url = get_post_meta( $post->ID, 'monday_item_url', true );
	$monday_status = get_post_meta( $post->ID, 'monday_sync_status', true );
	$retry_url = wp_nonce_url(
		admin_url( 'admin-post.php?action=straya_sheds_retry_monday_sync&post_id=' . absint( $post->ID ) ),
		'straya_sheds_retry_monday_sync_' . absint( $post->ID )
	);

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
		'Sheet colour'         => isset( $quote['sheetColor'] ) ? $quote['sheetColor'] : '',
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
		'monday.com sync'      => $monday_status,
		'monday.com item ID'   => get_post_meta( $post->ID, 'monday_item_id', true ),
		'monday.com item URL'  => $monday_url,
		'monday.com error'     => get_post_meta( $post->ID, 'monday_sync_error', true ),
		'monday.com retry scheduled' => get_post_meta( $post->ID, 'monday_retry_scheduled_at', true ),
	);

	echo '<table class="widefat striped"><tbody>';
	foreach ( $rows as $label => $value ) {
		if ( 'monday.com item URL' === $label && '' !== $value ) {
			echo '<tr><th style="width:220px;">' . esc_html( $label ) . '</th><td><a href="' . esc_url( $value ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $value ) . '</a></td></tr>';
			continue;
		}

		echo '<tr><th style="width:220px;">' . esc_html( $label ) . '</th><td>' . esc_html( $value ) . '</td></tr>';
	}
	echo '</tbody></table>';

	if ( 'synced' !== $monday_status ) {
		echo '<p><a class="button button-primary" href="' . esc_url( $retry_url ) . '">' . esc_html__( 'Retry monday.com sync', 'straya-sheds-quote-form' ) . '</a></p>';
	}
}

function straya_sheds_quote_form_admin_columns( $columns ) {
	$columns['customer_email']   = __( 'Email', 'straya-sheds-quote-form' );
	$columns['customer_phone']   = __( 'Phone', 'straya-sheds-quote-form' );
	$columns['delivery_address'] = __( 'Delivery address', 'straya-sheds-quote-form' );
	$columns['sheet_color']      = __( 'Sheet colour', 'straya-sheds-quote-form' );
	$columns['monday_sync_status'] = __( 'monday.com', 'straya-sheds-quote-form' );
	return $columns;
}
add_filter( 'manage_straya_shed_quote_posts_columns', 'straya_sheds_quote_form_admin_columns' );

function straya_sheds_quote_form_admin_column_content( $column, $post_id ) {
	if ( in_array( $column, array( 'customer_email', 'customer_phone', 'delivery_address', 'sheet_color' ), true ) ) {
		echo esc_html( get_post_meta( $post_id, $column, true ) );
	}

	if ( 'monday_sync_status' === $column ) {
		$status = get_post_meta( $post_id, 'monday_sync_status', true );
		$status = $status ? $status : 'not attempted';
		echo esc_html( $status );

		if ( 'synced' !== $status && current_user_can( 'edit_post', $post_id ) ) {
			$retry_url = wp_nonce_url(
				admin_url( 'admin-post.php?action=straya_sheds_retry_monday_sync&post_id=' . absint( $post_id ) ),
				'straya_sheds_retry_monday_sync_' . absint( $post_id )
			);
			echo '<br><a href="' . esc_url( $retry_url ) . '">' . esc_html__( 'Retry sync', 'straya-sheds-quote-form' ) . '</a>';
		}
	}
}
add_action( 'manage_straya_shed_quote_posts_custom_column', 'straya_sheds_quote_form_admin_column_content', 10, 2 );

function straya_sheds_quote_form_handle_monday_retry_action() {
	$post_id = isset( $_GET['post_id'] ) ? absint( $_GET['post_id'] ) : 0;

	if ( ! $post_id || ! current_user_can( 'edit_post', $post_id ) ) {
		wp_die( esc_html__( 'You do not have permission to retry this sync.', 'straya-sheds-quote-form' ) );
	}

	check_admin_referer( 'straya_sheds_retry_monday_sync_' . $post_id );

	$result = straya_sheds_quote_form_retry_monday_sync( $post_id );
	$arg    = is_wp_error( $result ) ? 'failed' : 'synced';

	wp_safe_redirect(
		add_query_arg(
			array(
				'post' => $post_id,
				'action' => 'edit',
				'straya_monday_retry' => $arg,
			),
			admin_url( 'post.php' )
		)
	);
	exit;
}
add_action( 'admin_post_straya_sheds_retry_monday_sync', 'straya_sheds_quote_form_handle_monday_retry_action' );

function straya_sheds_quote_form_monday_retry_notice() {
	if ( empty( $_GET['straya_monday_retry'] ) ) {
		return;
	}

	$type    = 'synced' === $_GET['straya_monday_retry'] ? 'success' : 'error';
	$message = 'synced' === $_GET['straya_monday_retry']
		? __( 'monday.com sync completed.', 'straya-sheds-quote-form' )
		: __( 'monday.com sync failed again. Check the monday.com error row below.', 'straya-sheds-quote-form' );

	echo '<div class="notice notice-' . esc_attr( $type ) . ' is-dismissible"><p>' . esc_html( $message ) . '</p></div>';
}
add_action( 'admin_notices', 'straya_sheds_quote_form_monday_retry_notice' );
