let selectedLocation = null;
let firstSuggestion = null;
let debounceTimeout;
let priceFilterApplied = false;

async function initAutocomplete() {
    const input = document.getElementById("autocomplete-input");
    const suggestionsBox = document.getElementById("autocomplete-suggestions");
    const form = document.getElementById("search-form");
    const searchButton = document.getElementById("search-button");

    if (!input || !suggestionsBox || !form || !searchButton) {
        return; // Quietly exit if elements aren't present
    }

    const { AutocompleteSuggestion, AutocompleteSessionToken } =
        await google.maps.importLibrary("places");
    const sessionToken = new AutocompleteSessionToken();

    // 🟡 Debounced input
    input.addEventListener("input", function () {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
            handleInputChange(this.value.trim());
        }, 250);
    });

    // 🟡 Suggestion click or fallback
    async function handleInputChange(query) {
        selectedLocation = null;
        if (query.length < 2) {
            suggestionsBox.innerHTML = "";
            firstSuggestion = null;
            return;
        }

        try {
            const response =
                await AutocompleteSuggestion.fetchAutocompleteSuggestions({
                    input: query,
                    sessionToken: sessionToken,
                    includedRegionCodes: ["TH"],
                });

            suggestionsBox.innerHTML = "";
            firstSuggestion = null;

            response.suggestions.forEach((suggestion, index) => {
                const pred = suggestion.placePrediction;
                const rawText = pred?.text?.text || "";
                const cleanedText = rawText
                    .replace(/,?\s*ประเทศไทย\s*$/i, "")
                    .trim();

                if (index === 0) {
                    firstSuggestion = pred;
                }

                const div = document.createElement("div");
                div.textContent = cleanedText;

                div.addEventListener("click", async () => {
                    input.value = cleanedText;
                    suggestionsBox.innerHTML = "";

                    try {
                        const place = await pred.toPlace();
                        await place.fetchFields({
                            fields: [
                                "location",
                                "displayName",
                                "formattedAddress",
                            ],
                        });

                        const cleanedAddress = place.formattedAddress
                            ?.replace(/,?\s*ประเทศไทย\s*$/i, "")
                            .trim();

                        selectedLocation = {
                            lat: place.location.lat(),
                            lon: place.location.lng(),
                            display_name: cleanedAddress || cleanedText,
                        };

                        input.focus();
                    } catch (error) {
                        console.error("Place details fetch error:", error);
                        selectedLocation = null;
                    }
                });

                suggestionsBox.appendChild(div);
            });
        } catch (err) {
            console.error("AutocompleteSuggestion error:", err);
        }
    }

    // 🔁 Enter key handling
    input.addEventListener("keydown", async function (e) {
        if (e.key === "Enter") {
            e.preventDefault();

            if (
                !selectedLocation ||
                input.value.trim() !== selectedLocation.display_name
            ) {
                if (firstSuggestion) {
                    try {
                        const place = await firstSuggestion.toPlace();
                        await place.fetchFields({
                            fields: [
                                "location",
                                "displayName",
                                "formattedAddress",
                            ],
                        });

                        const cleanedAddress = place.formattedAddress
                            ?.replace(/,?\s*ประเทศไทย\s*$/i, "")
                            .trim();

                        selectedLocation = {
                            lat: place.location.lat(),
                            lon: place.location.lng(),
                            display_name: cleanedAddress || input.value.trim(),
                        };
                    } catch (error) {
                        selectedLocation = null;
                    }
                }
            }

            searchButton.click();
        }
    });

    // 🔁 Search click handler
    searchButton.addEventListener("click", async function (e) {
        e.preventDefault();

        const actionUrl = form.getAttribute("action") || "/search";
        const base = new URL(actionUrl, window.location.origin);

        if (selectedLocation) {
            const { lat, lon, display_name } = selectedLocation;
            base.searchParams.set("lat", lat);
            base.searchParams.set("lon", lon);
            base.searchParams.set("label", display_name);
        } else if (input.value.trim() && !firstSuggestion) {
            // Try to fetch first suggestion now
            try {
                const response =
                    await AutocompleteSuggestion.fetchAutocompleteSuggestions({
                        input: input.value.trim(),
                        sessionToken: sessionToken,
                        includedRegionCodes: ["TH"],
                    });

                if (response?.suggestions?.length > 0) {
                    const pred = response.suggestions[0].placePrediction;
                    const place = await pred.toPlace();
                    await place.fetchFields({
                        fields: ["location", "displayName", "formattedAddress"],
                    });

                    const cleanedAddress = place.formattedAddress
                        ?.replace(/,?\s*ประเทศไทย\s*$/i, "")
                        .trim();

                    const lat = place.location.lat();
                    const lon = place.location.lng();
                    const label = cleanedAddress || input.value.trim();

                    base.searchParams.set("lat", lat);
                    base.searchParams.set("lon", lon);
                    base.searchParams.set("label", label);
                } else {
                    base.searchParams.set("label", input.value.trim());
                }
            } catch (error) {
                base.searchParams.set("label", input.value.trim());
            }
        } else if (firstSuggestion) {
            try {
                const place = await firstSuggestion.toPlace();
                await place.fetchFields({
                    fields: ["location", "displayName", "formattedAddress"],
                });

                const cleanedAddress = place.formattedAddress
                    ?.replace(/,?\s*ประเทศไทย\s*$/i, "")
                    .trim();

                const lat = place.location.lat();
                const lon = place.location.lng();
                const label = cleanedAddress || input.value.trim();

                base.searchParams.set("lat", lat);
                base.searchParams.set("lon", lon);
                base.searchParams.set("label", label);
            } catch (error) {
                base.searchParams.set("label", input.value.trim());
            }
        } else {
            base.searchParams.set("label", input.value.trim());
        }

        // ✅ Purpose and Category
        const purposeSelect = document.getElementById("purpose-select");
        const categorySelect = document.getElementById("category-select");
        const purpose = purposeSelect?.value;
        const category = categorySelect?.value;

        if (purpose) base.searchParams.set("purpose", purpose);
        if (category) base.searchParams.set("category", category);

        // ✅ Min-Max Price
        if (priceFilterApplied) {
            const minPriceInput = document.getElementById("input-1");
            const maxPriceInput = document.getElementById("input-2");

            const minPrice = minPriceInput?.value.replace(/,/g, ""); // Remove commas
            const maxPrice = maxPriceInput?.value.replace(/,/g, ""); // Remove commas

            if (minPrice) base.searchParams.set("min_price", minPrice);
            if (maxPrice) base.searchParams.set("max_price", maxPrice);
        }

        // ✅ Collect checked amenities
        const amenityCheckboxes =
            document.querySelectorAll(".amenity-checkbox");
        let selectedAmenities = [];

        amenityCheckboxes.forEach((cb) => {
            if (cb.checked) {
                selectedAmenities.push(cb.value);
            }
        });

        // Add amenities[] as multiple params
        selectedAmenities.forEach((id) => {
            base.searchParams.append("amenities[]", id);
        });

        window.location.href = base.toString();
    });

    // 🔁 Form submit fallback
    form.addEventListener("submit", function (e) {
        e.preventDefault();
        searchButton.click();
    });

    const applyButtons = document.querySelectorAll(".panel-apply");
    applyButtons.forEach((btn) => {
        if (btn.id !== "price-button") {
            btn.addEventListener("click", function (e) {
                e.preventDefault();
                priceFilterApplied = false;
                searchButton.click();
            });
        }
    });
    const priceButton = document.getElementById("price-button");
    if (priceButton) {
        priceButton.addEventListener("click", function (e) {
            e.preventDefault();
            priceFilterApplied = true;
            searchButton.click();
        });
    }
}
