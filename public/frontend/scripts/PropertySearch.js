window.onload = function () {
    slideOne();
    slideTwo();
};

let sliderOne = document.getElementById("slider-1");
let sliderTwo = document.getElementById("slider-2");

let minGap = 0;
let sliderTrack = document.querySelector(".slider-track");
let sliderMaxValue = document.getElementById("slider-1").max;

function slideOne() {
    if (parseInt(sliderTwo.value) - parseInt(sliderOne.value) <= minGap) {
        sliderOne.value = parseInt(sliderTwo.value) - minGap;
    }

    document.getElementById("input-1").value = formatNumberWithCommas(
        sliderOne.value
    );
    fillColor();
}

function slideTwo() {
    if (parseInt(sliderTwo.value) - parseInt(sliderOne.value) <= minGap) {
        sliderTwo.value = parseInt(sliderOne.value) + minGap;
    }

    document.getElementById("input-2").value = formatNumberWithCommas(
        sliderTwo.value
    );
    fillColor();
}
function fillColor() {
    percent1 = (sliderOne.value / sliderMaxValue) * 100;
    percent2 = (sliderTwo.value / sliderMaxValue) * 100;
    sliderTrack.style.background = `linear-gradient(to right, #dadae5 ${percent1}% , #84c015 ${percent1}% , #84c015 ${percent2}%, #dadae5 ${percent2}%)`;
}

function updateSliderValue(sliderNumber) {
    let input1 = document.getElementById("input-1");
    let input2 = document.getElementById("input-2");

    let value1 = removeCommas(input1.value);
    let value2 = removeCommas(input2.value);

    // Allow user to clear the input box
    if (value1 === "" || isNaN(parseInt(value1))) return;
    if (value2 === "" || isNaN(parseInt(value2))) return;

    value1 = parseInt(value1);
    value2 = parseInt(value2);

    if (sliderNumber === 1) {
        if (value2 - value1 <= minGap) {
            value1 = value2 - minGap;
        }
        sliderOne.value = value1;
        input1.value = formatNumberWithCommas(value1);
        slideOne();
    } else {
        if (value2 - value1 <= minGap) {
            value2 = value1 + minGap;
        }
        sliderTwo.value = value2;
        input2.value = formatNumberWithCommas(value2);
        slideTwo();
    }
}

function fillColor() {
    let percent1 = (sliderOne.value / sliderMaxValue) * 100;
    let percent2 = (sliderTwo.value / sliderMaxValue) * 100;
    sliderTrack.style.background = `linear-gradient(to right, #dadae5 ${percent1}%, #84c015 ${percent1}%, #84c015 ${percent2}%, #dadae5 ${percent2}%)`;

    // Update histogram bar colors
    const bars = document.querySelectorAll(".histogram .bar");
    const minVal = Math.min(
        parseInt(sliderOne.value),
        parseInt(sliderTwo.value)
    );
    const maxVal = Math.max(
        parseInt(sliderOne.value),
        parseInt(sliderTwo.value)
    );

    bars.forEach((bar, index) => {
        const barMin = index * (sliderMaxValue / bars.length);
        const barMax = (index + 1) * (sliderMaxValue / bars.length);

        if (barMin >= minVal && barMax <= maxVal) {
            bar.style.backgroundColor = "#84c015"; // Inside range
        } else {
            bar.style.backgroundColor = "#ccc"; // Outside range
        }
    });
}

const histogramData = [0, 5, 8, 15, 60, 40, 35, 70, 10, 5, 20, 35, 45, 5, 0]; // example data max 80 is sliderMaxValue
const histogram = document.getElementById("histogram");

histogram.innerHTML = ""; // Clear previous bars

histogramData.forEach((val, index) => {
    const bar = document.createElement("div");
    bar.className = "bar";
    bar.dataset.index = index;
    bar.style.height = `${val}px`;
    histogram.appendChild(bar);
});

document.addEventListener("DOMContentLoaded", function () {
    const salePrice = document.getElementById("input-1");
    const rentPrice = document.getElementById("input-2");

    salePrice.addEventListener("keydown", function (event) {
        const key = event.key;

        // Allow only digits 0–9
        if (
            !/^[0-9]$/.test(key) &&
            key !== "Backspace" &&
            key !== "Delete" &&
            key !== "Tab" &&
            !key.startsWith("Arrow") &&
            !event.ctrlKey &&
            !event.metaKey
        ) {
            event.preventDefault();
        }
    });

    rentPrice.addEventListener("keydown", function (event) {
        const key = event.key;

        // Same logic as above
        if (
            !/^[0-9]$/.test(key) &&
            key !== "Backspace" &&
            key !== "Delete" &&
            key !== "Tab" &&
            !key.startsWith("Arrow") &&
            !event.ctrlKey &&
            !event.metaKey
        ) {
            event.preventDefault();
        }
    });
});

function formatNumberWithCommas(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function removeCommas(numberString) {
    return numberString.replace(/,/g, "");
}

document.addEventListener("DOMContentLoaded", function () {
    const input1 = document.getElementById("input-1");
    const input2 = document.getElementById("input-2");

    input1.addEventListener("blur", function () {
        if (input1.value.trim() === "") {
            sliderOne.value = sliderOne.min;
            input1.value = formatNumberWithCommas(sliderOne.min);
            slideOne();
        }
    });

    input2.addEventListener("blur", function () {
        if (input2.value.trim() === "") {
            sliderTwo.value = sliderTwo.max;
            input2.value = formatNumberWithCommas(sliderTwo.max);
            slideTwo();
        }
    });
});
