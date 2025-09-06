document.addEventListener("DOMContentLoaded", async function () {
    // ========== Stripe Setup ==========
    const stripe = Stripe(window.PaymentConfig.stripePublicKey);
    const elements = stripe.elements();
    const cardElement = elements.create("card", {
        style: {
            base: {
                fontSize: "16px",
                color: "#000",
            },
        },
    });

    const cardElementContainer = document.getElementById("card-element");
    cardElementContainer.innerHTML = ""; // clear before mount
    cardElement.mount("#card-element");

    // ========== DOM Elements ==========
    const paymentForm = document.getElementById("paymentForm");
    const promptpayRadio = document.getElementById("promptpay");
    const cardRadio = document.getElementById("card");
    const promptpaySection = document.getElementById("promptpay_section");
    const creditcardSection = document.getElementById("creditcard_section");
    const payButton = document.getElementById("place_order");
    const qrContainer = document.getElementById("qr-container");
    const amount = document.getElementById("amount").value;
    const packageId = document.querySelector('input[name="package_id"]').value;
    const cartDetails = document.getElementById("cart_details");
    let qrGenerated = false;

    // ========== Helper Function ==========
    function getSelectedPaymentMethod() {
        return document.querySelector(
            'input[name="payment_method"]:checked'
        )?.value;
    }

    // ========== Show/Hide Spinner ==========
    function showSpinner() {
        document.getElementById("upload-spinner").style.display = "block";
        document.getElementById("spinner-icon").style.display = "block";
        document.getElementById("success-check").style.display = "none";
    }

    function hideSpinner(success = false) {
        const spinner = document.getElementById("upload-spinner");
        const icon = document.getElementById("spinner-icon");
        const check = document.getElementById("success-check");

        if (success) {
            icon.style.display = "none";
            check.style.display = "block";
            spinner.classList.add("fade-out");
            setTimeout(() => {
                spinner.style.display = "none";
                spinner.classList.remove("fade-out");
            }, 1000);
        } else {
            spinner.style.display = "none";
        }
    }

    // ========== Form Submission Handler ==========
    paymentForm.addEventListener("submit", async function (e) {
        const selected = getSelectedPaymentMethod();

        if (selected === "card") {
            e.preventDefault();
            showSpinner();
            payButton.disabled = true;
            document.getElementById("card-errors").textContent = "";

            const { paymentMethod, error } = await stripe.createPaymentMethod({
                type: "card",
                card: cardElement,
                billing_details: {
                    name:
                        document.getElementById("card_holder_name").value || "",
                },
            });

            if (error) {
                document.getElementById("card-errors").textContent =
                    error.message;
                hideSpinner();
                payButton.disabled = false;
                return;
            }

            const input = document.createElement("input");
            input.type = "hidden";
            input.name = "stripe_payment_method_id";
            input.value = paymentMethod.id;
            paymentForm.appendChild(input);

            paymentForm.submit();
        }
    });

    // ========== Toggle Payment Sections ==========
    function toggleSections() {
        const method = getSelectedPaymentMethod();
        if (!method) {
            cartDetails.style.display = "none"; // Hide if nothing selected
            promptpaySection.style.display = "none";
            creditcardSection.style.display = "none";
            payButton.style.display = "none";
            return;
        }

        // Show cartDetails only if a method is selected
        cartDetails.style.display = "block";

        if (method === "promptpay") {
            promptpaySection.style.display = "block";
            creditcardSection.style.display = "none";
            payButton.style.display = "none";

            if (!qrGenerated) {
                qrGenerated = true;
                setTimeout(() => loadPromptPayQRCode(), 200);
            }
        } else if (method === "card") {
            promptpaySection.style.display = "none";
            creditcardSection.style.display = "block";
            payButton.style.display = "block";
        }
    }

    // ========== Generate PromptPay QR Code ==========
    async function loadPromptPayQRCode() {
        showSpinner();
        qrContainer.innerHTML = "<p>กำลังโหลด QR...</p>";

        try {
            const res = await fetch(window.PaymentConfig.promptpayRoute, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": window.PaymentConfig.csrfToken,
                },
                body: JSON.stringify({
                    amount: amount,
                    package_id: packageId,
                }),
            });

            const data = await res.json();

            if (data.success && data.qr_url) {
                displayQRCode(data.qr_url, data.payment_intent_id);
            } else {
                qrContainer.innerHTML = `<p style="color:red;">${
                    data.message || "ไม่สามารถสร้าง QR ได้"
                }</p>`;
            }

            hideSpinner(true);
        } catch (err) {
            console.error("PromptPay QR fetch failed", err);
            qrContainer.innerHTML = `<p style="color:red;">เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์</p>`;
            hideSpinner();
        }
    }

    // ========== Display QR Code & Polling ==========
    function displayQRCode(qrUrl, paymentIntentId) {
        qrContainer.innerHTML = `
        <img src="${qrUrl}" alt="PromptPay QR Code" style="max-width:300px;" />
        <p>ยอดชำระ: ${parseFloat(amount).toFixed(2)} บาท</p>
        <p>กรุณาชำระเงินภายใน 10 นาที</p>
        <p style="color:red;">* หลังจากชำระเงิน กรุณารอ ระบบจะตรวจสอบสถานะการชำระเงิน *</p>
    `;

        hideSpinner(true);

        // Start polling Stripe for payment status
        let attempts = 0;
        const maxAttempts = 30; // ~90 seconds
        const interval = setInterval(async () => {
            attempts++;
            if (attempts > maxAttempts) {
                clearInterval(interval);
                qrContainer.innerHTML += `<p style="color:red;">หมดเวลารอการชำระเงิน</p>`;
                return;
            }

            try {
                const res = await fetch(
                    `/check-payment-stripe/${paymentIntentId}`
                );
                const data = await res.json();

                if (data.status === "succeeded") {
                    clearInterval(interval);
                    qrContainer.innerHTML += `<p style="color:green;">✅ การชำระเงินเสร็จสมบูรณ์</p>`;

                    // Send payment_intent_id and package_id to backend to finalize payment
                    try {
                        await fetch("/finalize-promptpay-payment", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": window.PaymentConfig.csrfToken,
                            },
                            body: JSON.stringify({
                                payment_intent_id: paymentIntentId,
                                package_id: packageId,
                            }),
                        });
                    } catch (error) {
                        console.error("Error finalizing payment:", error);
                    }

                    setTimeout(() => {
                        window.location.href = "/thank-you"; // or dashboard, etc.
                    }, 2000);
                }
            } catch (err) {
                console.error("Error checking payment status", err);
            }
        }, 3000); // every 3 seconds
    }

    // ========== Highlight & Scroll ==========
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

    // ========== Validate Terms & Required Fields ==========
    payButton.addEventListener("click", function (e) {
        const selected = getSelectedPaymentMethod();
        if (!selected) {
            alert("กรุณาเลือกวิธีการชำระเงิน");
            e.preventDefault();
            return;
        }

        let errors = [];
        let firstInvalidId = null;

        if (!document.getElementById("agree").checked) {
            errors.push("กรุณายอมรับเงื่อนไขและข้อตกลง");
            firstInvalidId = "agree";
        }

        if (selected === "card") {
            const name = document.getElementById("card_holder_name");
            if (!name.value.trim()) {
                errors.push("กรุณากรอกชื่อบนบัตร");
                if (!firstInvalidId) firstInvalidId = "card_holder_name";
            }
        }

        if (errors.length > 0) {
            e.preventDefault();
            alert(errors.join("\n"));
            if (firstInvalidId) {
                highlightAndScrollTo("#" + firstInvalidId);
            }
        }
    });

    // ========== Initialize UI based on selected payment method ==========
    toggleSections();

    promptpayRadio.addEventListener("click", () => {
        qrGenerated = false; // allow regenerating on reselect
        toggleSections();
    });

    cardRadio.addEventListener("click", () => {
        toggleSections();
    });

    if (promptpayRadio.checked) {
        qrGenerated = false;
        toggleSections();
    }
});
