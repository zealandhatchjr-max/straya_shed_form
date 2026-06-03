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
