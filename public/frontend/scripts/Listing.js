//---------------- Prices Box Function-----------------

var forSale = document.getElementById("forSale");
var forRent = document.getElementById("forRent");
var salePrice = document.getElementById("salePrice");
var rentPrice = document.getElementById("rentPrice");

function updateUI() {
    salePrice.disabled = !forSale.checked;
    rentPrice.disabled = !forRent.checked;
}

forSale.addEventListener("change", updateUI);
forRent.addEventListener("change", updateUI);

document.addEventListener("DOMContentLoaded", function () {
    const salePrice = document.getElementById("salePrice");
    const rentPrice = document.getElementById("rentPrice");

    salePrice.addEventListener("keydown", function (event) {
        const key = event.key;

        // Allow numbers (0-9) and comma (,)
        // Also allow common control keys like Backspace, Delete, Tab, Arrow keys
        if (
            !/^[0-9,]$/.test(key) &&
            key !== "Backspace" &&
            key !== "Delete" &&
            key !== "Tab" &&
            !event.ctrlKey &&
            !event.metaKey &&
            // Allow Ctrl/Cmd key combinations (e.g., Ctrl+C, Ctrl+V)
            !key.startsWith("Arrow")
        ) {
            // Allow arrow keys for navigation
            event.preventDefault(); // Prevent the invalid character from being entered
        }
    });
    rentPrice.addEventListener("keydown", function (event) {
        const key = event.key;

        // Allow numbers (0-9) and comma (,)
        // Also allow common control keys like Backspace, Delete, Tab, Arrow keys
        if (
            !/^[0-9,]$/.test(key) &&
            key !== "Backspace" &&
            key !== "Delete" &&
            key !== "Tab" &&
            !event.ctrlKey &&
            !event.metaKey &&
            // Allow Ctrl/Cmd key combinations (e.g., Ctrl+C, Ctrl+V)
            !key.startsWith("Arrow")
        ) {
            // Allow arrow keys for navigation
            event.preventDefault(); // Prevent the invalid character from being entered
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const numericInputs = ["land_size", "usage_size", "property_built_year"];

    numericInputs.forEach((id) => {
        const input = document.getElementById(id);
        input.addEventListener("input", function () {
            this.value = this.value.replace(/[^0-9,]/g, "");
        });
    });
});

//-------------Preview Button Usable When Data Filled---------------

$(document).ready(function () {
    function togglePreviewButton() {
        const hasName = $("#property_name").val().trim() !== "";
        const hasType = $("#property_type_id").val() !== "";
        const hasSale = $("#salePrice").val().trim() !== "";
        const hasRent = $("#rentPrice").val().trim() !== "";
        const hasLand = $("#land_size").val().trim() !== "";

        const shouldEnable =
            hasName && (hasSale || hasRent) && hasType && hasLand;
        $("#preview").prop("disabled", !shouldEnable);
    }

    // ✅ Bind all relevant fields, including land_size
    $("#property_name, #salePrice, #rentPrice, #land_size").on(
        "input",
        togglePreviewButton
    );
    $("#property_type_id").on("change", togglePreviewButton);

    // Run on load
    togglePreviewButton();
});

$(document).ready(function () {
    function toggleRoomFields() {
        var selected = $("#property_type_id").val();
        if (selected == "3" || selected == "4") {
            $("#room-fields").hide();
        } else {
            $("#room-fields").show();
        }
    }

    // Initial check on page load
    toggleRoomFields();

    // On change
    $("#property_type_id").change(function () {
        toggleRoomFields();
    });
});

$("#preview").on("click", function (e) {
    const imageCount = $("#uploaded-gallery").find("img").length;
    const hasImages = imageCount >= 5;
    const propertyAddress = $("#province_name").val().trim();
    const nameValue = $("#property_name").val().trim();
    const hasName = nameValue.length > 10;
    // ✅ Check if property_address is empty
    if (!propertyAddress) {
        e.preventDefault();
        alert("กรุณากรอกที่อยู่ของประกาศ");
        highlightAndScrollTo("#property_address");
        return;
    }

    if (!hasName) {
        e.preventDefault();
        alert("กรุณากรอกชื่อประกาศให้ครบถ้วน (มากกว่า 10 ตัวอักษร)");
        highlightAndScrollTo("#property_name");
        return;
    }

    if (!hasImages) {
        e.preventDefault();
        alert("กรุณาอัปโหลดรูปภาพอย่างน้อย 5 รูป");
        highlightAndScrollTo("#media-uploader");
        return;
    }
});

function highlightAndScrollTo(selector) {
    $("html, body").animate({ scrollTop: $(selector).offset().top - 100 }, 600);

    $(selector)
        .addClass("highlight-border")
        .delay(2000)
        .queue(function (next) {
            $(this).removeClass("highlight-border");
            next();
        });
}
