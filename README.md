# Straya Sheds Form

Quote form for Straya Sheds. This repo contains:

- `localhost-app/` style files at the repo root for local development.
- `wordpress-plugin/` for the deployable WordPress plugin used on `/form`.

## Local Development

Run the local development server from the repo root:

```powershell
node server.js
```

Then open:

```text
http://localhost:4173
```

For local Google address autocomplete, copy `.env.example` to `.env` and set:

```text
STRAYA_SHEDS_GOOGLE_MAPS_API_KEY=YOUR_BROWSER_KEY
```

Do not commit `.env`.

## WordPress Plugin

The deployable plugin files are in:

```text
wordpress-plugin/
```

Upload the contents of that folder to:

```text
public_html/wp-content/plugins/straya-sheds-quote-form/
```

Then activate **Straya Sheds Quote Form** in WordPress admin.

The form page should contain:

```text
[straya_sheds_quote_form]
```

## Google Places Key

The Google API key is intentionally not hard-coded.

For WordPress, add this to `wp-config.php` above the line that says `/* That's all, stop editing! */`:

```php
define( 'STRAYA_SHEDS_GOOGLE_MAPS_API_KEY', 'your_google_places_browser_key_here' );
```

Restrict the key in Google Cloud:

- HTTP referrer: `https://strayasheds.com.au/*`
- APIs: only the required Google Places APIs
- Country restriction in the app: Australia only

Autocomplete waits until 6 characters, uses a 600ms debounce, and de-dupes in-flight requests to reduce API usage.

## Backend

The WordPress plugin:

- registers a private `straya_shed_quote` post type named **Shed enquiries**
- stores submitted quote data as WordPress post meta
- sends a notification email to the WordPress admin email
- exposes a REST endpoint at `/wp-json/straya-sheds/v1/enquiries`

Check submissions in WordPress admin under **Shed enquiries**.

## Secrets

Never commit real credentials:

- Google API keys
- WordPress passwords
- Hostinger credentials
- `.env` files

Use `.env.example` and `wordpress-plugin/.env.example` only as placeholders.
