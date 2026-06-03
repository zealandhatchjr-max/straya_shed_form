const WALLS = ["L1", "L2", "W1", "W2"];
const STORAGE_KEY = "shedQuoteRequests";
const SAMPLE_QUOTES = [
  {
    id: "sample-001",
    status: "New",
    customerName: "Mia Thompson",
    email: "mia.thompson@example.com",
    phone: "0412 345 901",
    deliveryAddress: "74 Lincoln Causeway, Gateway Island VIC 3691, Australia",
    deliveryAddressData: {
      provider: "sample",
      placeId: "sample-lincoln",
      display: "74 Lincoln Causeway, Gateway Island VIC 3691, Australia",
      street: "74 Lincoln Causeway",
      suburb: "Gateway Island",
      state: "VIC",
      postcode: "3691",
      country: "Australia",
      lat: -36.0969,
      lng: 146.9012,
    },
    dimensions: {
      length: "18m",
      width: "9m",
      height: "4.2m",
    },
    roofType: "Gable",
    roofPitch: "10 degrees",
    roofIncluded: true,
    selectedWalls: ["L1", "L2", "W1"],
    wallCount: 3,
    wallMaterial: "Corrugated steel",
    options: {
      purlins150c: true,
      bayWidth: "6",
      structuralSteel: true,
      insulationBlankets: false,
    },
    uploadedFiles: ["gateway-island-shed-plan.pdf", "roller-door-markup.png"],
    notes: "Two roller doors on W2. Measurements are from the builder and should be checked before ordering.",
    submittedAt: "2026-06-03T01:35:00.000Z",
  },
  {
    id: "sample-002",
    status: "Reviewing",
    customerName: "Jack Williams",
    email: "jack.williams@example.com",
    phone: "0499 800 221",
    deliveryAddress: "27 Boundary Road, Rutherford NSW 2320",
    deliveryAddressData: {
      provider: "sample",
      placeId: "sample-rutherford",
      display: "27 Boundary Road, Rutherford NSW 2320",
      street: "27 Boundary Road",
      suburb: "Rutherford",
      state: "NSW",
      postcode: "2320",
      country: "Australia",
      lat: -32.7163,
      lng: 151.5262,
    },
    dimensions: {
      length: "12m",
      width: "7.5m",
      height: "3.6m",
    },
    roofType: "Skillion",
    roofPitch: "5 degrees",
    roofIncluded: true,
    selectedWalls: [],
    wallCount: 0,
    wallMaterial: "None - roof only",
    options: {
      purlins150c: false,
      bayWidth: "",
      structuralSteel: false,
      insulationBlankets: true,
    },
    uploadedFiles: ["rutherford-roof-only.dwg"],
    notes: "Roof sheets only. Delivery access is via side driveway.",
    submittedAt: "2026-06-02T22:10:00.000Z",
  },
  {
    id: "sample-003",
    status: "Needs drawings",
    customerName: "Olivia Chen",
    email: "olivia.chen@example.com",
    phone: "0431 112 908",
    deliveryAddress: "19 Workshop Circuit, Cardiff NSW 2285",
    deliveryAddressData: {
      provider: "sample",
      placeId: "sample-cardiff",
      display: "19 Workshop Circuit, Cardiff NSW 2285",
      street: "19 Workshop Circuit",
      suburb: "Cardiff",
      state: "NSW",
      postcode: "2285",
      country: "Australia",
      lat: -32.9412,
      lng: 151.6568,
    },
    dimensions: {
      length: "24m",
      width: "10m",
      height: "5m",
    },
    roofType: "Gable",
    roofPitch: "10 degrees",
    roofIncluded: true,
    selectedWalls: ["L1", "W1", "W2"],
    wallCount: 3,
    wallMaterial: "Concrete tilt panel",
    options: {
      purlins150c: true,
      bayWidth: "8",
      structuralSteel: false,
      insulationBlankets: true,
    },
    uploadedFiles: [],
    notes: "Customer will send drawings later. Wants wall sheets for one long side and both end walls.",
    submittedAt: "2026-06-01T05:22:00.000Z",
  },
];
const ADDRESS_OPTIONS = [
  {
    display: "12 Smith Street, Newcastle NSW 2300",
    street: "12 Smith Street",
    suburb: "Newcastle",
    state: "NSW",
    postcode: "2300",
    country: "Australia",
    lat: -32.9283,
    lng: 151.7817,
  },
  {
    display: "12 Smith Road, Maitland NSW 2320",
    street: "12 Smith Road",
    suburb: "Maitland",
    state: "NSW",
    postcode: "2320",
    country: "Australia",
    lat: -32.7331,
    lng: 151.5574,
  },
  {
    display: "84 Industrial Drive, Mayfield NSW 2304",
    street: "84 Industrial Drive",
    suburb: "Mayfield",
    state: "NSW",
    postcode: "2304",
    country: "Australia",
    lat: -32.892,
    lng: 151.7365,
  },
  {
    display: "27 Boundary Road, Rutherford NSW 2320",
    street: "27 Boundary Road",
    suburb: "Rutherford",
    state: "NSW",
    postcode: "2320",
    country: "Australia",
    lat: -32.7163,
    lng: 151.5262,
  },
  {
    display: "5 Shed Lane, Thornton NSW 2322",
    street: "5 Shed Lane",
    suburb: "Thornton",
    state: "NSW",
    postcode: "2322",
    country: "Australia",
    lat: -32.7836,
    lng: 151.635,
  },
  {
    display: "41 Rural Way, Cessnock NSW 2325",
    street: "41 Rural Way",
    suburb: "Cessnock",
    state: "NSW",
    postcode: "2325",
    country: "Australia",
    lat: -32.8347,
    lng: 151.3555,
  },
  {
    display: "19 Workshop Circuit, Cardiff NSW 2285",
    street: "19 Workshop Circuit",
    suburb: "Cardiff",
    state: "NSW",
    postcode: "2285",
    country: "Australia",
    lat: -32.9412,
    lng: 151.6568,
  },
  {
    display: "73 Farm Road, Tamworth NSW 2340",
    street: "73 Farm Road",
    suburb: "Tamworth",
    state: "NSW",
    postcode: "2340",
    country: "Australia",
    lat: -31.0833,
    lng: 150.9167,
  },
  {
    display: "72 Wilson Street, Newtown NSW 2042",
    street: "72 Wilson Street",
    suburb: "Newtown",
    state: "NSW",
    postcode: "2042",
    country: "Australia",
    lat: -33.8951,
    lng: 151.181,
  },
  {
    display: "72 Wilson Road, Acacia Gardens NSW 2763",
    street: "72 Wilson Road",
    suburb: "Acacia Gardens",
    state: "NSW",
    postcode: "2763",
    country: "Australia",
    lat: -33.7317,
    lng: 150.9126,
  },
];

const state = {
  selectedWalls: [],
  activeQuoteId: null,
  selectedAddress: null,
  activeAddressIndex: -1,
  currentAddressMatches: [],
  addressProvider: "local",
  googleSessionToken: null,
  addressSearchTimer: null,
  addressRequestId: 0,
  googlePlacesError: "",
};

const form = document.querySelector("#quote-form");
const noWalls = document.querySelector("#no-walls");
const wallButtons = Array.from(document.querySelectorAll("[data-wall]"));
const wallSummary = document.querySelector("#wall-summary");
const addressInput = document.querySelector("#delivery-address");
const addressDataInput = document.querySelector("#delivery-address-data");
const addressSuggestions = document.querySelector("#address-suggestions");
const addressSelectionSummary = document.querySelector("#address-selection-summary");
const notesInput = form.elements.notes;
const notesCount = document.querySelector("#notes-count");
const quoteList = document.querySelector("#quote-list");
const quoteDetail = document.querySelector("#quote-detail");
const loadSamplesButton = document.querySelector("#load-samples");
const bayWidthField = document.querySelector("#bay-width-field");
const bayWidthInput = form.elements.bayWidth;
const wallMaterialField = document.querySelector("#wall-material-field");
const quoteAnotherShedButton = document.querySelector("#quote-another-shed");

function getStoredQuotes() {
  try {
    const storedQuotes = localStorage.getItem(STORAGE_KEY);
    return storedQuotes ? JSON.parse(storedQuotes) : SAMPLE_QUOTES;
  } catch {
    return SAMPLE_QUOTES;
  }
}

function saveStoredQuotes(quotes) {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(quotes));
}

function wallLabel(selectedWalls) {
  return selectedWalls.length ? selectedWalls.join(", ") : "None - roof only";
}

function updateWallUi() {
  const hasWalls = state.selectedWalls.length > 0;
  noWalls.checked = !hasWalls;
  wallButtons.forEach((button) => {
    const isSelected = state.selectedWalls.includes(button.dataset.wall);
    button.classList.toggle("is-selected", isSelected);
    button.setAttribute("aria-pressed", String(isSelected));
  });
  wallSummary.textContent = `Selected walls: ${wallLabel(state.selectedWalls)}`;
  updateWallMaterialField();
}

function toggleWall(wall) {
  if (!WALLS.includes(wall)) return;

  if (state.selectedWalls.includes(wall)) {
    state.selectedWalls = state.selectedWalls.filter((selectedWall) => selectedWall !== wall);
  } else {
    state.selectedWalls = [...state.selectedWalls, wall];
  }

  updateWallUi();
}

function validateSelectedWalls(selectedWalls) {
  if (!Array.isArray(selectedWalls)) return false;
  const uniqueWalls = new Set(selectedWalls);
  if (uniqueWalls.size !== selectedWalls.length) return false;
  return selectedWalls.every((wall) => WALLS.includes(wall));
}

function fileNames(fileList) {
  return Array.from(fileList || []).map((file) => file.name);
}

function normalizeSearch(value) {
  return value.trim().toLowerCase().replace(/\s+/g, " ");
}

function findAddressMatches(value) {
  const query = normalizeSearch(value);
  if (query.length < 1) return [];

  return ADDRESS_OPTIONS.filter((address) => {
    const searchable = normalizeSearch(
      `${address.display} ${address.street} ${address.suburb} ${address.state} ${address.postcode}`,
    );
    return query.split(" ").every((part) => searchable.includes(part));
  }).slice(0, 6);
}

function renderAddressSuggestions(matches) {
  state.currentAddressMatches = matches;
  state.activeAddressIndex = matches.length ? 0 : -1;
  addressSuggestions.innerHTML = "";

  if (!matches.length) {
    addressSuggestions.hidden = true;
    addressInput.setAttribute("aria-expanded", "false");
    return;
  }

  matches.forEach((address, index) => {
    const mainText = address.mainText || address.street || address.display;
    const secondaryText =
      address.secondaryText ||
      [address.suburb, address.state, address.postcode].filter(Boolean).join(" ") ||
      address.country ||
      "";
    const option = document.createElement("button");
    option.type = "button";
    option.id = `address-option-${index}`;
    option.className = "address-suggestion";
    option.classList.toggle("is-active", index === state.activeAddressIndex);
    option.setAttribute("role", "option");
    option.setAttribute("aria-selected", String(index === state.activeAddressIndex));
    option.innerHTML = `
      <strong>${escapeHtml(mainText)}</strong>
      <span>${escapeHtml(secondaryText)}</span>
    `;
    option.addEventListener("mousedown", (event) => {
      event.preventDefault();
      if (address.provider === "google-prediction") {
        selectGooglePrediction(address);
      } else {
        selectAddress(address);
      }
    });
    addressSuggestions.append(option);
  });

  addressSuggestions.hidden = false;
  addressInput.setAttribute("aria-expanded", "true");
  addressInput.setAttribute("aria-activedescendant", `address-option-${state.activeAddressIndex}`);
}

function hideAddressSuggestions() {
  window.clearTimeout(state.addressSearchTimer);
  state.addressRequestId += 1;
  state.currentAddressMatches = [];
  state.activeAddressIndex = -1;
  addressSuggestions.innerHTML = "";
  addressSuggestions.hidden = true;
  addressInput.setAttribute("aria-expanded", "false");
  addressInput.removeAttribute("aria-activedescendant");
}

function updateActiveAddress(index) {
  const lastIndex = state.currentAddressMatches.length - 1;
  state.activeAddressIndex = Math.max(0, Math.min(index, lastIndex));
  Array.from(addressSuggestions.children).forEach((child, childIndex) => {
    const isActive = childIndex === state.activeAddressIndex;
    child.classList.toggle("is-active", isActive);
    child.setAttribute("aria-selected", String(isActive));
  });
  addressInput.setAttribute("aria-activedescendant", `address-option-${state.activeAddressIndex}`);
}

function selectAddress(address) {
  state.selectedAddress = address;
  addressInput.value = address.display;
  addressDataInput.value = JSON.stringify(address);
  addressSelectionSummary.textContent = `Selected address: ${address.display}`;
  hideAddressSuggestions();
}

function selectGooglePlace(place) {
  const address = mapGooglePlaceToAddress(place);
  state.selectedAddress = address;
  addressInput.value = address.display;
  addressDataInput.value = JSON.stringify(address);
  addressSelectionSummary.textContent = `Selected Google address: ${address.display}`;
  hideAddressSuggestions();
}

function selectGooglePrediction(prediction) {
  const fallbackAddress = {
    provider: "google",
    placeId: prediction.placeId,
    display: prediction.display,
    street: prediction.mainText,
    suburb: "",
    state: "",
    postcode: "",
    country: "Australia",
    lat: null,
    lng: null,
  };

  selectAddress(fallbackAddress);

  fetchGooglePlaceDetails(prediction.placeId)
    .then((place) => selectGooglePlace(mapNewGooglePlaceToLegacyPlace(place)))
    .catch(() => {
      selectAddress(fallbackAddress);
    });
}

function clearSelectedAddress() {
  state.selectedAddress = null;
  addressDataInput.value = "";
  addressSelectionSummary.textContent =
    state.addressProvider === "google"
      ? "Start typing and choose a Google address match, or keep typing a custom address."
      : "Choose a matching address, or keep typing a custom address.";
}

function googleApiKey() {
  return (window.SHED_QUOTE_CONFIG || {}).googleMapsApiKey || "";
}

function getGoogleComponent(place, componentType) {
  const component = place.address_components?.find((item) => item.types.includes(componentType));
  return component?.long_name || "";
}

function getGoogleShortComponent(place, componentType) {
  const component = place.address_components?.find((item) => item.types.includes(componentType));
  return component?.short_name || "";
}

function mapGooglePlaceToAddress(place) {
  const streetNumber = getGoogleComponent(place, "street_number");
  const route = getGoogleComponent(place, "route");
  const suburb =
    getGoogleComponent(place, "locality") ||
    getGoogleComponent(place, "postal_town") ||
    getGoogleComponent(place, "administrative_area_level_2");
  const stateName = getGoogleShortComponent(place, "administrative_area_level_1");
  const postcode = getGoogleComponent(place, "postal_code");
  const country = getGoogleComponent(place, "country") || "Australia";
  const location = place.geometry?.location;

  return {
    provider: "google",
    placeId: place.place_id || "",
    display: place.formatted_address || addressInput.value,
    street: [streetNumber, route].filter(Boolean).join(" "),
    suburb,
    state: stateName,
    postcode,
    country,
    lat: typeof location?.lat === "function" ? location.lat() : null,
    lng: typeof location?.lng === "function" ? location.lng() : null,
  };
}

function mapNewGooglePlaceToLegacyPlace(place) {
  return {
    place_id: place.id || "",
    formatted_address: place.formattedAddress || "",
    address_components: (place.addressComponents || []).map((component) => ({
      long_name: component.longText || "",
      short_name: component.shortText || "",
      types: component.types || [],
    })),
    geometry: {
      location: {
        lat: () => place.location?.latitude ?? null,
        lng: () => place.location?.longitude ?? null,
      },
    },
  };
}

function loadGoogleMapsScript(apiKey) {
  return new Promise((resolve, reject) => {
    if (window.google?.maps?.places) {
      resolve();
      return;
    }

    const callbackName = "initShedQuoteGooglePlaces";
    window[callbackName] = () => resolve();

    const script = document.createElement("script");
    script.src = `https://maps.googleapis.com/maps/api/js?key=${encodeURIComponent(
      apiKey,
    )}&libraries=places&callback=${callbackName}`;
    script.async = true;
    script.defer = true;
    script.onerror = () => reject(new Error("Google Maps script failed to load."));
    document.head.append(script);
  });
}

async function initGoogleAddressAutocomplete() {
  const apiKey = googleApiKey();

  if (!apiKey) {
    state.addressProvider = "local";
    clearSelectedAddress();
    return;
  }

  state.googleSessionToken = crypto.randomUUID();
  state.addressProvider = "google";
  clearSelectedAddress();
}

async function fetchGooglePredictions(value) {
  const config = window.SHED_QUOTE_CONFIG || {};
  const response = await fetch("https://places.googleapis.com/v1/places:autocomplete", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-Goog-Api-Key": googleApiKey(),
      "X-Goog-FieldMask":
        "suggestions.placePrediction.placeId,suggestions.placePrediction.text,suggestions.placePrediction.structuredFormat",
    },
    body: JSON.stringify({
      input: value,
      includedRegionCodes: [config.addressCountry || "au"],
      sessionToken: state.googleSessionToken,
    }),
  });

  const data = await response.json();
  if (!response.ok) {
    throw new Error(data?.error?.message || "Google Places autocomplete failed.");
  }

  return data.suggestions || [];
}

async function fetchGooglePlaceDetails(placeId) {
  const response = await fetch(
    `https://places.googleapis.com/v1/places/${encodeURIComponent(
      placeId,
    )}?fields=id,formattedAddress,addressComponents,location`,
    {
      headers: {
        "X-Goog-Api-Key": googleApiKey(),
      },
    },
  );

  const data = await response.json();
  if (!response.ok) {
    throw new Error(data?.error?.message || "Google place details failed.");
  }

  return data;
}

function searchGoogleAddresses(value) {
  const query = normalizeSearch(value);
  window.clearTimeout(state.addressSearchTimer);
  const localMatches = findAddressMatches(value);

  if (query.length < 1) {
    renderAddressSuggestions([]);
    return;
  }

  renderAddressSuggestions(localMatches);

  if (query.length < 3 || !googleApiKey()) return;

  const requestId = state.addressRequestId + 1;
  state.addressRequestId = requestId;

  state.addressSearchTimer = window.setTimeout(async () => {
    try {
      const suggestions = await fetchGooglePredictions(value);
      if (requestId !== state.addressRequestId) return;

      const googleMatches = suggestions
        .map((suggestion) => suggestion.placePrediction)
        .filter(Boolean)
        .slice(0, 6)
        .map((prediction) => ({
            provider: "google-prediction",
            placeId: prediction.placeId,
            display: prediction.text?.text || "",
            mainText: prediction.structuredFormat?.mainText?.text || prediction.text?.text || "",
            secondaryText: prediction.structuredFormat?.secondaryText?.text || "",
          }));

      state.googlePlacesError = "";
      renderAddressSuggestions(googleMatches.length ? googleMatches : localMatches);
    } catch (error) {
      if (requestId !== state.addressRequestId) return;
      state.googlePlacesError = error.message;
      addressSelectionSummary.textContent = `Google address lookup is not available yet: ${error.message}`;
      renderAddressSuggestions(localMatches);
    }
  }, 250);
}

function buildQuote(formData) {
  const selectedWalls = [...state.selectedWalls];
  if (!validateSelectedWalls(selectedWalls)) {
    throw new Error("Selected walls must only include L1, L2, W1, and W2 without duplicates.");
  }

  const notes = String(formData.get("notes") || "").trim();
  if (notes.length > 2000) {
    throw new Error("Notes must be 2000 characters or fewer.");
  }

  const purlins150c = form.elements.purlins150c.checked;
  const structuralSteel = form.elements.structuralSteel.checked;
  const bayWidth = String(formData.get("bayWidth") || "").trim();
  if ((purlins150c || structuralSteel) && !bayWidth) {
    throw new Error("Bay width is required when 150 C purlins or structural steel are selected.");
  }
  const wallMaterial = selectedWalls.length ? String(formData.get("wallMaterial") || "").trim() : "None - roof only";
  if (selectedWalls.length && !wallMaterial) {
    throw new Error("Wall material is required when walls are selected.");
  }

  return {
    id: crypto.randomUUID(),
    customerName: String(formData.get("customerName") || "").trim(),
    email: String(formData.get("email") || "").trim(),
    phone: String(formData.get("phone") || "").trim(),
    deliveryAddress: String(formData.get("deliveryAddress") || "").trim(),
    deliveryAddressData: state.selectedAddress,
    dimensions: {
      length: String(formData.get("length") || "").trim(),
      width: String(formData.get("width") || "").trim(),
      height: String(formData.get("height") || "").trim(),
    },
    roofType: String(formData.get("roofType") || "").trim(),
    roofPitch: String(formData.get("roofPitch") || "").trim(),
    roofIncluded: true,
    selectedWalls,
    wallCount: selectedWalls.length,
    wallMaterial,
    options: {
      purlins150c,
      bayWidth: purlins150c || structuralSteel ? bayWidth : "",
      structuralSteel,
      insulationBlankets: form.elements.insulationBlankets.checked,
    },
    uploadedFiles: fileNames(form.elements.drawings.files),
    notes,
    submittedAt: new Date().toISOString(),
  };
}

function formatDate(isoDate) {
  return new Intl.DateTimeFormat(undefined, {
    dateStyle: "medium",
    timeStyle: "short",
  }).format(new Date(isoDate));
}

function renderQuoteList() {
  const quotes = getStoredQuotes();
  quoteList.innerHTML = "";

  if (!quotes.length) {
    quoteList.innerHTML = '<p class="empty-state">No local quote requests yet.</p>';
    quoteDetail.innerHTML =
      '<p class="empty-state">Submit a quote request to view the selected walls, notes, and email notification preview.</p>';
    return;
  }

  quotes.forEach((quote) => {
    const item = document.createElement("button");
    item.type = "button";
    item.className = "quote-list-item";
    item.classList.toggle("is-active", quote.id === state.activeQuoteId);
    item.innerHTML = `
      <span class="quote-list-top">
        <strong>${escapeHtml(quote.customerName || "Unnamed customer")}</strong>
        <em>${escapeHtml(quote.status || "New")}</em>
      </span>
      <span>${escapeHtml(quote.deliveryAddress || "No delivery address")}</span>
      <span>Walls: ${escapeHtml(quote.selectedWalls.length ? quote.selectedWalls.join(", ") : "Roof only")}</span>
      <span>${escapeHtml(formatDate(quote.submittedAt))}</span>
    `;
    item.addEventListener("click", () => {
      state.activeQuoteId = quote.id;
      renderAdmin();
    });
    quoteList.append(item);
  });

  const activeQuote = quotes.find((quote) => quote.id === state.activeQuoteId) || quotes[0];
  state.activeQuoteId = activeQuote.id;
  renderQuoteDetail(activeQuote);
}

function escapeHtml(value) {
  return String(value)
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#039;");
}

function yesNo(value) {
  return value ? "Yes" : "No";
}

function structuredAddressLine(addressData) {
  if (!addressData) return "Custom typed address";
  return [addressData.street, `${addressData.suburb} ${addressData.state} ${addressData.postcode}`, addressData.country]
    .filter((part) => part && part.trim())
    .join(", ");
}

function dimensionsLine(dimensions) {
  return `${dimensions.length} m length, ${dimensions.width} m width, ${dimensions.height} m height`;
}

function renderQuoteDetail(quote) {
  const uploadedFiles = quote.uploadedFiles.length ? quote.uploadedFiles.join(", ") : "None";
  const notes = quote.notes || "None";
  const emailPreview = [
    `Customer name: ${quote.customerName}`,
    `Email: ${quote.email}`,
    `Phone: ${quote.phone}`,
    `Delivery address: ${quote.deliveryAddress}`,
    `Structured address: ${structuredAddressLine(quote.deliveryAddressData)}`,
    `Shed dimensions: ${dimensionsLine(quote.dimensions)}`,
    `Shed type: ${quote.roofType}`,
    `Roof pitch: ${quote.roofPitch}`,
    "Roof included: Yes",
    `Selected walls: ${wallLabel(quote.selectedWalls)}`,
    `Wall count: ${quote.wallCount}`,
    `Wall material: ${quote.wallMaterial || (quote.selectedWalls.length ? "Not provided" : "None - roof only")}`,
    `150 C purlins option: ${yesNo(quote.options.purlins150c)}`,
    `Bay width: ${
      quote.options.purlins150c || quote.options.structuralSteel
        ? `${quote.options.bayWidth || "Not provided"} m`
        : "Not applicable"
    }`,
    `Structural steel option: ${yesNo(quote.options.structuralSteel)}`,
    `Insulation blankets option: ${yesNo(quote.options.insulationBlankets)}`,
    `Notes: ${notes}`,
    `Uploaded file links: ${uploadedFiles}`,
    `Submission timestamp: ${quote.submittedAt}`,
  ].join("\n");

  quoteDetail.innerHTML = `
    <div class="section-heading">
      <p class="eyebrow">Enquiry detail</p>
      <h2>${escapeHtml(quote.customerName || "Unnamed customer")}</h2>
      <p class="detail-subtitle">${escapeHtml(quote.status || "New")} enquiry submitted ${escapeHtml(formatDate(quote.submittedAt))}</p>
    </div>
    <div class="detail-section">
      <h3>Customer</h3>
      <div class="detail-grid">
        <div class="detail-card"><strong>Name</strong><span>${escapeHtml(quote.customerName || "Unnamed customer")}</span></div>
        <div class="detail-card"><strong>Email</strong><span>${escapeHtml(quote.email)}</span></div>
        <div class="detail-card"><strong>Phone</strong><span>${escapeHtml(quote.phone)}</span></div>
        <div class="detail-card"><strong>Status</strong><span>${escapeHtml(quote.status || "New")}</span></div>
      </div>
    </div>
    <div class="detail-section">
      <h3>Delivery</h3>
      <div class="detail-grid">
        <div class="detail-card detail-card-wide"><strong>Delivery address</strong><span>${escapeHtml(quote.deliveryAddress)}</span></div>
        <div class="detail-card detail-card-wide"><strong>Address match</strong><span>${escapeHtml(structuredAddressLine(quote.deliveryAddressData))}</span></div>
      </div>
    </div>
    <div class="detail-section">
      <h3>Shed</h3>
    <div class="detail-grid">
      <div class="detail-card"><strong>Roof</strong><span>Included</span></div>
      <div class="detail-card"><strong>Roof type</strong><span>${escapeHtml(quote.roofType)}</span></div>
      <div class="detail-card"><strong>Dimensions</strong><span>${escapeHtml(dimensionsLine(quote.dimensions))}</span></div>
      <div class="detail-card"><strong>Selected walls</strong><span>${escapeHtml(wallLabel(quote.selectedWalls))}</span></div>
      <div class="detail-card"><strong>Wall count</strong><span>${quote.wallCount}</span></div>
      <div class="detail-card"><strong>Wall material</strong><span>${escapeHtml(quote.wallMaterial || (quote.selectedWalls.length ? "Not provided" : "None - roof only"))}</span></div>
      <div class="detail-card"><strong>Roof pitch</strong><span>${escapeHtml(quote.roofPitch)}</span></div>
      <div class="detail-card"><strong>150 C purlins</strong><span>${yesNo(quote.options.purlins150c)}</span></div>
      <div class="detail-card"><strong>Bay width</strong><span>${escapeHtml(quote.options.purlins150c || quote.options.structuralSteel ? `${quote.options.bayWidth || "Not provided"} m` : "Not applicable")}</span></div>
      <div class="detail-card"><strong>Structural steel</strong><span>${yesNo(quote.options.structuralSteel)}</span></div>
      <div class="detail-card"><strong>Insulation blankets</strong><span>${yesNo(quote.options.insulationBlankets)}</span></div>
      <div class="detail-card"><strong>Uploaded files</strong><span>${escapeHtml(uploadedFiles)}</span></div>
      <div class="detail-card"><strong>Submitted</strong><span>${escapeHtml(formatDate(quote.submittedAt))}</span></div>
    </div>
    </div>
    <div class="detail-card"><strong>Notes</strong><span>${escapeHtml(notes)}</span></div>
    <section>
      <h3>Email notification preview</h3>
      <pre class="email-preview">${escapeHtml(emailPreview)}</pre>
    </section>
  `;
}

function renderAdmin() {
  renderQuoteList();
}

function showView(viewName) {
  document.querySelectorAll("[data-view]").forEach((navButton) => {
    navButton.classList.toggle("is-active", navButton.dataset.view === viewName);
  });
  document.querySelectorAll(".view").forEach((view) => {
    view.classList.toggle("is-visible", view.id === `${viewName}-view`);
  });
  if (viewName === "admin") {
    renderAdmin();
  }
  window.scrollTo({ top: 0, behavior: "smooth" });
}

function updateBayWidthField() {
  const needsBayWidth = form.elements.purlins150c.checked || form.elements.structuralSteel.checked;
  bayWidthField.hidden = !needsBayWidth;
  bayWidthInput.required = needsBayWidth;
  if (!needsBayWidth) {
    bayWidthInput.value = "";
  }
}

function updateWallMaterialField() {
  const needsWallMaterial = state.selectedWalls.length > 0;
  wallMaterialField.hidden = !needsWallMaterial;
  Array.from(form.elements.wallMaterial || []).forEach((input) => {
    input.required = needsWallMaterial;
    if (!needsWallMaterial) {
      input.checked = false;
    }
  });
}

function resetFormState() {
  form.reset();
  state.selectedWalls = [];
  clearSelectedAddress();
  hideAddressSuggestions();
  updateBayWidthField();
  updateWallUi();
  notesCount.textContent = "0 / 2000 characters";
}

wallButtons.forEach((button) => {
  button.addEventListener("click", () => toggleWall(button.dataset.wall));
});

noWalls.addEventListener("change", () => {
  if (noWalls.checked) {
    state.selectedWalls = [];
    updateWallUi();
  } else {
    noWalls.checked = state.selectedWalls.length === 0;
  }
});

form.elements.purlins150c.addEventListener("change", updateBayWidthField);
form.elements.structuralSteel.addEventListener("change", updateBayWidthField);

addressInput.addEventListener("input", () => {
  if (state.selectedAddress && addressInput.value !== state.selectedAddress.display) {
    clearSelectedAddress();
  }
  if (state.addressProvider === "google") {
    searchGoogleAddresses(addressInput.value);
    return;
  }
  renderAddressSuggestions(findAddressMatches(addressInput.value));
});

addressInput.addEventListener("keydown", (event) => {
  if (addressSuggestions.hidden || !state.currentAddressMatches.length) return;

  if (event.key === "ArrowDown") {
    event.preventDefault();
    updateActiveAddress(state.activeAddressIndex + 1);
  }

  if (event.key === "ArrowUp") {
    event.preventDefault();
    updateActiveAddress(state.activeAddressIndex - 1);
  }

  if (event.key === "Enter") {
    event.preventDefault();
    const activeAddress = state.currentAddressMatches[state.activeAddressIndex];
    if (activeAddress.provider === "google-prediction") {
      selectGooglePrediction(activeAddress);
    } else {
      selectAddress(activeAddress);
    }
  }

  if (event.key === "Escape") {
    hideAddressSuggestions();
  }
});

addressSuggestions.addEventListener(
  "click",
  () => {
    hideAddressSuggestions();
  },
  true,
);

document.addEventListener("pointerdown", (event) => {
  if (addressInput.contains(event.target) || addressSuggestions.contains(event.target)) {
    return;
  }

  hideAddressSuggestions();
});

addressInput.addEventListener("blur", () => {
  window.setTimeout(() => {
    hideAddressSuggestions();
  }, 120);
});

notesInput.addEventListener("input", () => {
  notesCount.textContent = `${notesInput.value.length} / 2000 characters`;
});

form.addEventListener("submit", (event) => {
  event.preventDefault();

  try {
    const quote = buildQuote(new FormData(form));
    const quotes = [quote, ...getStoredQuotes()];
    saveStoredQuotes(quotes);
    state.activeQuoteId = quote.id;
    resetFormState();
    renderAdmin();
    showView("thank-you");
  } catch (error) {
    alert(error.message);
  }
});

document.querySelectorAll("[data-view]").forEach((button) => {
  button.addEventListener("click", () => {
    showView(button.dataset.view);
  });
});

quoteAnotherShedButton.addEventListener("click", () => {
  resetFormState();
  showView("form");
});

document.querySelector("#clear-quotes").addEventListener("click", () => {
  saveStoredQuotes([]);
  state.activeQuoteId = null;
  renderAdmin();
});

loadSamplesButton.addEventListener("click", () => {
  saveStoredQuotes(SAMPLE_QUOTES);
  state.activeQuoteId = SAMPLE_QUOTES[0].id;
  renderAdmin();
});

updateWallUi();
updateBayWidthField();
renderAdmin();
initGoogleAddressAutocomplete();
