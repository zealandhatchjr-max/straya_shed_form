# Shed Quote Localhost App

Static localhost development prototype for the named wall selection shed quote flow.

## Run

From this directory:

```powershell
node server.js
```

Then open:

```text
http://localhost:4173
```

For a fresh checkout, copy `.env.example` to `.env` and add the Google Maps browser key locally.

## Development Notes

- The roof is always included.
- `selectedWalls` is stored as an array of named wall values: `L1`, `L2`, `W1`, `W2`.
- `wallCount` is derived from `selectedWalls.length`.
- Empty `selectedWalls` means roof only.
- Notes are optional and limited to 2000 characters.
- Submitted requests are stored in browser `localStorage` for local development only.
- Address autocomplete uses Google Places when `.env` has `STRAYA_SHEDS_GOOGLE_MAPS_API_KEY`. Without a key, it falls back to local sample addresses.
- The admin dashboard includes quote list, quote detail, notes, selected wall count, and an email notification preview.
- No automatic pricing is calculated or displayed.

## Google Address Autocomplete

1. Create a Google Maps Platform browser API key.
2. Enable the Maps JavaScript API and Places API for the key.
3. Restrict the key to local development first, for example:

```text
http://localhost:4173/*
```

4. Add the key in `.env`:

```text
STRAYA_SHEDS_GOOGLE_MAPS_API_KEY=YOUR_BROWSER_KEY
```

For production, add your real website domain to the key restrictions before deploying.
