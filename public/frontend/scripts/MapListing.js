const scrollEnabled = !(
    $("#map").attr("data-map-scroll") === "true" || $(window).width() < 992
);
const center = [initialLat, initialLon];

const map = L.map("map", {
    gestureHandling: scrollEnabled,
    center,
    zoom: initialZoom,
    maxBounds: [
        [5.0, 97.0],
        [21.5, 106.0],
    ],
    maxBoundsViscosity: 1.0,
});

L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "&copy; OpenStreetMap contributors",
    maxZoom: 19,
}).addTo(map);

const customIcon = L.divIcon({
    iconAnchor: [20, 51],
    popupAnchor: [0, -51],
    className: "listeo-marker-icon",
    html: `<div class="marker-container no-marker-animation">
               <div class="marker-card">
                 <div class="front face"><i class="fa fa-home"></i></div>
                 <div class="back face"><i class="fa fa-home"></i></div>
                 <div class="marker-arrow"></div>
               </div>
             </div>`,
});

const marker = L.marker(center, {
    icon: customIcon,
    draggable: true,
}).addTo(map);

function updateInputs(lat, lon, zoom) {
    document.getElementById("lat").value = lat.toFixed(6);
    document.getElementById("lon").value = lon.toFixed(6);
    document.getElementById("zoom").value = zoom;
}

function reverseGeocode(lat, lon, skipCheckServer = false) {
    const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=18&addressdetails=1&accept-language=th`;

    fetch(url)
        .then((res) => res.json())
        .then((data) => {
            const addr = data.address || {};
            let zip_code = addr.postcode || "";
            const province = addr.province || addr.city || "";
            const district = addr.suburb || addr.county || "";
            const sub_district = addr.county || addr.quarter || "";

            if (zip_code === "55520") zip_code = "50200";

            document.getElementById("zip_code").value = zip_code;

            if (!skipCheckServer) {
                checkAddressWithServer({
                    sub_district,
                    district,
                    province,
                    zip_code,
                });
            }
        })
        .catch((err) => console.error("Reverse geocoding error:", err));
}

function geocodeAddress(address, skipCheckServer = false) {
    const url = `https://nominatim.openstreetmap.org/search?format=json&countrycodes=th&limit=5&q=${encodeURIComponent(
        address
    )}`;
    fetch(url)
        .then((res) => res.json())
        .then((data) => {
            if (data?.length) {
                let latNum, lonNum;
                if (address.replace(/\s/g, "").includes("51000")) {
                    latNum = 18.5811;
                    lonNum = 99.0092;
                } else {
                    const { lat, lon } = data[0];
                    latNum = parseFloat(lat);
                    lonNum = parseFloat(lon);
                }

                marker.setLatLng([latNum, lonNum]);
                map.setView([latNum, lonNum], 17);
                updateInputs(latNum, lonNum, map.getZoom());
                reverseGeocode(latNum, lonNum, skipCheckServer);
            } else {
                alert("ไม่พบที่อยู่ กรุณาลองใหม่");
            }
        })
        .catch((err) => {
            console.error("Geocoding error:", err);
            alert("Geocoding failed: " + err.message);
        });
}

marker.on("dragstart", (e) => {
    const province = document.getElementById("province_name").value;
    if (!province) {
        alert("กรุณาค้นหาที่อยู่ก่อนลากหมุด");
        // Stop dragging by resetting marker to original position
        marker.setLatLng(marker.getLatLng());
        e.preventDefault(); // Optional: stops the drag behavior
    }
});

marker.on("dragend", (e) => {
    const pos = e.target.getLatLng();
    updateInputs(pos.lat, pos.lng, map.getZoom());
    //reverseGeocode(pos.lat, pos.lng);
});

map.on("zoomend", () => {
    document.getElementById("zoom").value = map.getZoom();
});

map.removeControl(map.zoomControl);
L.control
    .zoom({
        zoomInText: '<i class="fa fa-plus"></i>',
        zoomOutText: '<i class="fa fa-minus"></i>',
    })
    .addTo(map);

updateInputs(center[0], center[1], map.getZoom());

document.getElementById("search_place").addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
        $("#upload-spinner").show();
        e.preventDefault();
        $("#address-fields").slideDown();
        const query = e.target.value.trim();
        if (query) geocodeAddress(query);
        // Hide the search fields
        $("#search-fields").slideUp();
        // Show the edit button
        $("#edit_button_container").fadeIn();
    }
});

document.getElementById("check_place").addEventListener("click", (e) => {
    e.preventDefault(); // Prevent form submission
    const query = document.getElementById("search_place").value.trim();

    if (query) {
        $("#upload-spinner").show(); // Optional: if you have a spinner
        geocodeAddress(query); // Call your geocode function

        // Show address fields
        $("#address-fields").slideDown();

        // Hide the search input section
        $("#search-fields").slideUp();

        // Show the edit button
        $("#edit_button_container").fadeIn();
    } else {
        alert("กรุณากรอกข้อมูลที่อยู่ก่อนค้นหา");
        // Optionally focus back to input
        document.getElementById("search_place").focus();
    }
});

$("#edit_search").on("click", function () {
    // Show the search fields again
    $("#search-fields").slideDown();
    // Hide the edit button
    $("#edit_button_container").fadeOut();
});

let provinceChoice = null;
let districtChoice = null;
let subDistrictChoice = null;

document.addEventListener("DOMContentLoaded", () => {
    provinceChoice = new Choices("#province_name", {
        searchEnabled: true,
        itemSelectText: "",
        noResultsText: "ไม่พบข้อมูลที่ค้นหา",
        shouldSort: false,
        renderChoiceLimit: 15,
    });

    districtChoice = new Choices("#district_name", {
        searchEnabled: true,
        itemSelectText: "",
        noResultsText: "กรุณาเลือกจังหวัดก่อน",
        shouldSort: false,
    });

    subDistrictChoice = new Choices("#sub_district_name", {
        searchEnabled: true,
        itemSelectText: "",
        noResultsText: "กรุณาเลือกจังหวัดก่อน",
        shouldSort: false,
    });

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
        },
    });

    $("#province_name").on("change", function () {
        const provinceId = $(this).val();
        const provinceText = $("#province_name option:selected").text();

        districtChoice.clearStore();
        districtChoice.setChoices(
            [{ value: "", label: "เลือกอำเภอ" }],
            "value",
            "label",
            true
        );

        subDistrictChoice.clearStore();
        subDistrictChoice.setChoices(
            [{ value: "", label: "เลือกตำบล" }],
            "value",
            "label",
            true
        );

        if (!provinceId) return;

        geocodeAddress(provinceText, true);

        $.getJSON(`/get-districts/${provinceId}`)
            .done((data) => {
                const districtOptions = data.map((d) => ({
                    value: d.id,
                    label: d.name_th,
                }));
                districtChoice.setChoices(
                    districtOptions,
                    "value",
                    "label",
                    true
                );
            })
            .fail(() => alert("ไม่สามารถโหลดรายการอำเภอได้"));
    });

    document
        .getElementById("district_name")
        .addEventListener("change", function () {
            const distId = this.value;
            const districtText = this.options[this.selectedIndex].text;
            const provinceText = $("#province_name option:selected").text();

            subDistrictChoice.clearStore();
            subDistrictChoice.setChoices(
                [{ value: "", label: "เลือกตำบล" }],
                "value",
                "label",
                true
            );

            if (!distId) return;

            geocodeAddress(`${districtText}, ${provinceText}`, true);

            fetch(`/get-subdistricts/${distId}`)
                .then((r) => r.json())
                .then((data) => {
                    const opts = data.map((s) => ({
                        value: s.id,
                        label: s.name_th,
                    }));
                    subDistrictChoice.setChoices(opts, "value", "label", true);
                })
                .catch(() => alert("ไม่สามารถโหลดตำบลได้"));
        });

    document
        .getElementById("sub_district_name")
        .addEventListener("change", function () {
            const subDistrictText = this.options[this.selectedIndex].text;
            const districtText = $("#district_name option:selected").text();
            const provinceText = $("#province_name option:selected").text();

            if (this.value) {
                geocodeAddress(`${subDistrictText}, ${provinceText}`, true);
            }
        });
});

function checkAddressWithServer(data) {
    //$("#upload-spinner").show();

    districtChoice.clearStore();
    districtChoice.setChoices(
        [{ value: "", label: "เลือกอำเภอ" }],
        "value",
        "label",
        true
    );

    subDistrictChoice.clearStore();
    subDistrictChoice.setChoices(
        [{ value: "", label: "เลือกตำบล" }],
        "value",
        "label",
        true
    );

    $.post("/check-address-server", data)
        .done((response) => {
            if (response.exists && response.province && provinceChoice) {
                provinceChoice.setChoiceByValue(
                    response.province.id.toString()
                );

                if (Array.isArray(response.districts)) {
                    const districtOptions = response.districts.map((d) => ({
                        value: d.id.toString(),
                        label: d.name_th,
                    }));
                    districtChoice.setChoices(
                        districtOptions,
                        "value",
                        "label",
                        false
                    );

                    if (response.district) {
                        districtChoice.setChoiceByValue(
                            response.district.id.toString()
                        );
                    }
                }

                if (Array.isArray(response.subdistricts)) {
                    const subOptions = response.subdistricts.map((d) => ({
                        value: d.id.toString(),
                        label: d.name_th,
                    }));
                    subDistrictChoice.setChoices(
                        subOptions,
                        "value",
                        "label",
                        false
                    );

                    if (response.sub_district) {
                        subDistrictChoice.setChoiceByValue(
                            response.sub_district.id.toString()
                        );
                    }
                }
            } else {
                alert("ไม่พบข้อมูลที่ตรงกับรหัสไปรษณีย์และตำบล");
            }
        })
        .fail((xhr) => {
            alert(
                "Error: " +
                    (xhr.responseJSON?.message || "Something went wrong")
            );
        })
        .always(() => {
            $("#upload-spinner").hide();
        });
}
