// Load Omise script externally in your HTML <head> or before this script runs:
// <script src="https://cdn.omise.co/omise.js"></script>

// Make sure you set your Omise public key here before using the file,
// e.g., by server rendering or injecting via a global variable:

// External JS - payment.js
document.addEventListener("DOMContentLoaded", function () {
    // ===== Omise Setup =====
    Omise.setPublicKey(window.PaymentConfig.omisePublicKey);

    // ===== DOM Elements =====
    const paymentForm = document.getElementById("paymentForm");
    const promptpayRadio = document.getElementById("promptpay");
    const cardRadio = document.getElementById("card");
    const promptpaySection = document.getElementById("promptpay_section");
    const creditcardSection = document.getElementById("creditcard_section");
    const payAndSubmitButton = document.getElementById("place_order");
    const qrContainer = document.getElementById("qr-container");
    const amount = document.getElementById("amount").value;
    const cartDetails = document.getElementById("cart_details");
    let qrGenerated = false;

    // ===== Omise Token Creation on Card Submit =====
    paymentForm.addEventListener("submit", function (e) {
        const paymentMethod = document.querySelector(
            'input[name="payment_method"]:checked'
        )?.value;

        if (paymentMethod === "card") {
            e.preventDefault();

            Omise.createToken(
                "card",
                {
                    name: document.getElementById("card_holder_name").value,
                    number: document.getElementById("creditcard").value,
                    expiration_month:
                        document.getElementById("card_exp_month").value,
                    expiration_year:
                        document.getElementById("card_exp_year").value,
                    security_code: document.getElementById("card_cvc").value,
                },
                function (statusCode, response) {
                    if (response.object === "token") {
                        document.getElementById("omise_token").value =
                            response.id;
                        paymentForm.submit();
                    } else {
                        alert("Error: " + response.message);
                    }
                }
            );
        }
    });

    // ===== Toggle Sections Based on Payment Method =====
    function toggleSections() {
        if (promptpayRadio.checked) {
            promptpaySection.style.display = "block";
            creditcardSection.style.display = "none";
            payAndSubmitButton.style.display = "none";
            cartDetails.style.display = "block";

            if (!qrGenerated) {
                qrGenerated = true;
                showSpinner();
                qrContainer.innerHTML = "<p>กำลังโหลด QR...</p>";

                fetch(window.PaymentConfig.promptpayRoute, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": window.PaymentConfig.csrfToken,
                    },
                    body: JSON.stringify({
                        amount: amount,
                        package_id: document.querySelector(
                            'input[name="package_id"]'
                        ).value,
                    }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            const chargeId = data.charge_id;
                            const packageId = document.querySelector(
                                'input[name="package_id"]'
                            ).value;
                            if (window.PaymentConfig.testMode) {
                                // Simulate payment success after 5 seconds in test mode
                                const pollInterval = setInterval(() => {
                                    fetch(
                                        `/check-promptpay-status?charge_id=${chargeId}&package_id=${packageId}&test_mode=true`
                                    )
                                        .then((res) => res.json())
                                        .then((resData) => {
                                            if (resData.status === "paid") {
                                                clearInterval(pollInterval);

                                                // Handle redirect & notifications from response
                                                if (resData.redirect_url) {
                                                    window.location.href =
                                                        resData.redirect_url;
                                                } else {
                                                    window.location.href =
                                                        "/dashboard?status=active";
                                                }
                                            }
                                        });
                                }, 5000);
                            } else {
                                const pollInterval = setInterval(() => {
                                    fetch(
                                        `/check-promptpay-status?charge_id=${chargeId}&package_id=${packageId}&test_mode=${window.PaymentConfig.testMode}`
                                    )
                                        .then((res) => res.json())
                                        .then((resData) => {
                                            if (resData.status === "paid") {
                                                clearInterval(pollInterval);
                                                setTimeout(() => {
                                                    window.location.href =
                                                        resData.redirect_url;
                                                }, 2000);
                                            }
                                        });
                                }, 5000);
                            }

                            qrContainer.innerHTML = `
                                <img src="${
                                    data.qr_url
                                }" alt="PromptPay QR Code" style="max-width:300px;" />
                                <p>ยอดชำระ: ${parseFloat(amount).toFixed(
                                    2
                                )} บาท</p>
                                <p>กรุณาชำระเงินภายใน 10 นาที</p>
                                <p style="color:red;">* หากจ่ายเงินเสร็จแล้ว กรุณารอ 10 วินาที ระบบจะทำการตรวจสอบสถานะการชำระเงิน *</p>
                            `;
                            hideSpinner(true);
                        } else {
                            qrContainer.innerHTML = `<p style="color:red;">${data.message}</p>`;
                            hideSpinner();
                        }
                    })
                    .catch(() => {
                        qrContainer.innerHTML = `<p style="color:red;">เกิดข้อผิดพลาดในการเชื่อมต่อ</p>`;
                        hideSpinner();
                    });
            }
        } else if (cardRadio.checked) {
            promptpaySection.style.display = "none";
            creditcardSection.style.display = "block";
            payAndSubmitButton.style.display = "block";
            cartDetails.style.display = "block";
        }
    }

    toggleSections();
    promptpayRadio.addEventListener("change", toggleSections);
    cardRadio.addEventListener("change", toggleSections);

    // ===== Place Order Validation =====
    payAndSubmitButton.addEventListener("click", function (event) {
        const selectedRadio = document.querySelector(
            'input[name="payment_method"]:checked'
        );
        if (!selectedRadio) {
            alert("กรุณาเลือกวิธีการชำระเงิน");
            event.preventDefault();
            return;
        }

        const paymentMethod = selectedRadio.value;
        let requiredFields = [
            { id: "agree", message: "กรุณายอมรับเงื่อนไขและข้อตกลง" },
        ];

        if (paymentMethod === "card") {
            requiredFields = requiredFields.concat([
                { id: "card_holder_name", message: "กรุณากรอกชื่อ" },
                { id: "creditcard", message: "กรุณากรอกหมายเลขบัตรเครดิต" },
                { id: "card_exp_month", message: "กรุณากรอกเดือนที่หมดอายุ" },
                { id: "card_exp_year", message: "กรุณากรอกปีที่หมดอายุ" },
                { id: "card_cvc", message: "กรุณากรอก CVC" },
            ]);
        }

        let valid = true;
        let errorMessages = [];
        let firstInvalidId = null;

        requiredFields.forEach((field) => {
            const input = document.getElementById(field.id);

            if (field.id === "agree") {
                if (!input.checked) {
                    valid = false;
                    errorMessages.push(field.message);
                    if (!firstInvalidId) firstInvalidId = field.id;
                }
            } else if (!input || input.value.trim() === "") {
                valid = false;
                errorMessages.push(field.message);
                if (!firstInvalidId) firstInvalidId = field.id;
            }
        });

        if (!valid) {
            event.preventDefault();
            alert(errorMessages.join("\n"));
            highlightAndScrollTo(`#${firstInvalidId}`);
        }
    });

    // ===== Utility Functions =====
    window.formatCreditCard = function (input) {
        let value = input.value.replace(/\D/g, "").substring(0, 16);
        let formatted = value.match(/.{1,4}/g);
        input.value = formatted ? formatted.join(" ") : "";
    };

    function showSpinner() {
        document.getElementById("upload-spinner").style.display = "block";
        document.getElementById("spinner-icon").style.display = "block";
        document.getElementById("success-check").style.display = "none";
    }

    function hideSpinner(success = false) {
        const spinner = document.getElementById("upload-spinner");
        const spinnerIcon = document.getElementById("spinner-icon");
        const successCheck = document.getElementById("success-check");

        if (success) {
            spinnerIcon.style.display = "none";
            successCheck.style.display = "block";
            spinner.classList.add("fade-out");
            setTimeout(() => {
                spinner.style.display = "none";
                spinner.classList.remove("fade-out");
            }, 1000);
        } else {
            spinner.style.display = "none";
        }
    }

    function highlightAndScrollTo(selector) {
        $("html, body").animate(
            {
                scrollTop: $(selector).offset().top - 100,
            },
            600
        );
        $(selector)
            .addClass("highlight-border")
            .delay(2000)
            .queue(function (next) {
                $(this).removeClass("highlight-border");
                next();
            });
    }
});
