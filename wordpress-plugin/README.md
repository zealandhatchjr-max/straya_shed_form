# Straya Sheds Quote Form

## Google Places API key

Do not hard-code the Google Places API key in this plugin.

Set it on the WordPress server as `STRAYA_SHEDS_GOOGLE_MAPS_API_KEY`.

For a typical WordPress install, add this to `wp-config.php` above the line that says `/* That's all, stop editing! */`:

```php
define( 'STRAYA_SHEDS_GOOGLE_MAPS_API_KEY', 'your_google_places_browser_key_here' );
```

If the host supports environment variables, set:

```text
STRAYA_SHEDS_GOOGLE_MAPS_API_KEY=your_google_places_browser_key_here
```

The key is still sent to the browser because Google Places autocomplete runs in the browser. Restrict the key in Google Cloud to the Straya Sheds domain and only the APIs needed for Places autocomplete/details.

## monday.com enquiry sync

The plugin can create one monday.com item for each submitted enquiry. The sync runs on the WordPress server after the enquiry is stored locally, so the monday API token is never exposed to the browser.

Add these constants to `wp-config.php` above the line that says `/* That's all, stop editing! */`:

```php
define( 'STRAYA_SHEDS_MONDAY_API_TOKEN', 'your_monday_api_token_here' );
define( 'STRAYA_SHEDS_MONDAY_BOARD_ID', '1234567890' );
```

If enquiries should go into a specific monday group, also add:

```php
define( 'STRAYA_SHEDS_MONDAY_GROUP_ID', 'topics' );
```

Then map Straya form fields to monday column IDs. The array key is the form field, and each value needs the monday column ID and type:

```php
define(
	'STRAYA_SHEDS_MONDAY_COLUMNS',
	array(
		'email'               => array( 'id' => 'email_mkabc123', 'type' => 'email' ),
		'phone'               => array( 'id' => 'phone_mkabc123', 'type' => 'phone' ),
		'delivery_address'    => array( 'id' => 'text_mkabc123', 'type' => 'text' ),
		'dimensions'          => array( 'id' => 'long_text_mkabc123', 'type' => 'long_text' ),
		'sheet_color'         => array( 'id' => 'color_mkabc123', 'type' => 'status' ),
		'roof_type'           => array( 'id' => 'color_mkabc124', 'type' => 'status' ),
		'roof_pitch'          => array( 'id' => 'color_mkabc125', 'type' => 'status' ),
		'selected_walls'      => array( 'id' => 'text_mkabc124', 'type' => 'text' ),
		'wall_material'       => array( 'id' => 'text_mkabc125', 'type' => 'text' ),
		'purlins_150c'        => array( 'id' => 'color_mkabc126', 'type' => 'status' ),
		'structural_steel'    => array( 'id' => 'color_mkabc127', 'type' => 'status' ),
		'bay_width'           => array( 'id' => 'numbers_mkabc123', 'type' => 'numbers' ),
		'insulation_blankets' => array( 'id' => 'color_mkabc128', 'type' => 'status' ),
		'uploaded_files'      => array( 'id' => 'long_text_mkabc124', 'type' => 'long_text' ),
		'notes'               => array( 'id' => 'long_text_mkabc125', 'type' => 'long_text' ),
	)
);
```

Use monday Developer Mode to find board, group, and column IDs. If you map a field to a `status` or `dropdown` column, make sure the monday labels already match the form values, such as `Dark grey`, `Light grey`, `Gable`, `Skillion`, `5 degrees`, `10 degrees`, `Yes`, and `No`. If you do not want to manage labels, use `text` or `long_text` columns.

Supported mapping keys are `customer_name`, `email`, `phone`, `delivery_address`, `length`, `width`, `height`, `dimensions`, `sheet_color`, `roof_type`, `roof_pitch`, `selected_walls`, `wall_count`, `wall_material`, `purlins_150c`, `structural_steel`, `bay_width`, `insulation_blankets`, `uploaded_files`, `notes`, `wordpress_enquiry_id`, and `submitted_at`.

Each WordPress enquiry stores monday sync metadata:

- `monday_sync_status`: `synced`, `failed`, or `not configured`
- `monday_item_id`
- `monday_item_url`
- `monday_sync_error`
- `monday_synced_at`

Actual drawing file uploads to monday should be added later. This first sync sends uploaded file names into a text or long-text column.
