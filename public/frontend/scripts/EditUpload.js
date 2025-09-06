document.addEventListener("DOMContentLoaded", () => {
    const propertyInput = document.getElementById("property_id");
    const propertyId = propertyInput.value;

    $("<input>")
        .attr({
            type: "hidden",
            id: "temp_id",
            name: "temp_id",
            value: propertyId,
        })
        .appendTo("#myForm");

    document.getElementById("add-more-photos").onclick = () =>
        document.getElementById("add-files-input").click();

    loadUploadedGallery(initialGallery);
});

function updateImageOrder() {
    const order = Array.from(
        document.querySelectorAll("#media-uploader .dz-preview")
    ).map((el, i) => ({
        index: i,
        name: el.dataset.fileName,
    }));
    console.log("New image order:", order);
}

function updateCoverImage(file) {
    currentCoverFileName = file.name;
    console.log("Cover image set to:", currentCoverFileName);
}

function loadUploadedGallery(images = []) {
    const gallery = document.getElementById("uploaded-gallery");
    const tempId = document.getElementById("property_id").value;
    gallery.innerHTML = "";
    gallery.style.display = "flex";

    return new Promise((resolve) => {
        if (!images.length) return resolve();

        let loaded = 0;
        images.forEach((image) => {
            const wrapper = document.createElement("div");
            wrapper.className = "dz-preview dz-file-preview";
            wrapper.dataset.id = image.id;
            if (image.is_cover) wrapper.classList.add("is-cover");

            wrapper.innerHTML = `
                <div class="dz-image"><img src="${
                    image.url
                }" alt="Uploaded image" /></div>
                <div class="cover-placeholder">${
                    image.is_cover ? coverStarSVG() : ""
                }</div>
                <button class="dz-remove-custom" type="button" title="Remove">×</button>
            `;

            const img = wrapper.querySelector("img");
            img.onload = img.onerror = () => {
                loaded++;
                if (loaded === images.length) resolve();
            };

            wrapper.onclick = () => {
                document.querySelectorAll(".dz-preview").forEach((el) => {
                    el.classList.remove("is-cover");
                    const icon = el.querySelector(".cover-placeholder");
                    if (icon) icon.innerHTML = "";
                });

                wrapper.classList.add("is-cover");
                wrapper.querySelector(".cover-placeholder").innerHTML =
                    coverStarSVG();

                fetch("/user/gallery/set-cover", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                    },
                    body: JSON.stringify({ id: image.id }),
                }).catch((err) =>
                    alert("Error setting cover image: " + err.message)
                );
            };

            wrapper.querySelector(".dz-remove-custom").onclick = (e) => {
                e.preventDefault();
                e.stopPropagation();

                showDialog(
                    "Are you sure you want to remove this image?",
                    () => {
                        fetch(
                            `/user/gallery/delete/${image.id}?temp_id=${tempId}`,
                            {
                                method: "DELETE",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": document.querySelector(
                                        'meta[name="csrf-token"]'
                                    ).content,
                                },
                            }
                        )
                            .then((res) => {
                                if (!res.ok)
                                    throw new Error("Failed to delete image");
                                wrapper.remove();
                            })
                            .catch((err) => {
                                alert("Error deleting image: " + err.message);
                            });
                    }
                );
            };

            gallery.appendChild(wrapper);
        });

        new Sortable(gallery, {
            items: ".dz-preview",
            draggable: ".dz-preview",
            onEnd: () => {
                const order = [...gallery.querySelectorAll(".dz-preview")].map(
                    (el, i) => ({
                        id: el.dataset.id,
                        order: i,
                    })
                );

                fetch("/user/gallery/reorder", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                    },
                    body: JSON.stringify({ order }),
                }).catch((err) => {
                    console.error("Order update error:", err);
                    alert("Error saving image order.");
                });
            },
        });
    });
}

function coverStarSVG() {
    return `<svg class="cover-icon" viewBox="0 0 48 48" width="20" height="20" fill="gold">
        <path d="M24 4c.7 0 1.3.4 1.6 1l5.8 11.8 13 1.9c1.7.2 2.4 2.4 1.2 3.6l-9.4 9.1 
        2.2 12.8c.3 1.7-1.5 3-3 2.2L24 39.3l-11.6 6.1c-1.5.8-3.3-.5-3-2.2l2.2-12.8
        -9.4-9.1c-1.2-1.2-.5-3.4 1.2-3.6l13-1.9L22.4 5c.3-.6.9-1 1.6-1z" />
    </svg>`;
}

function showDialog(message, onConfirm) {
    const dialog = document.getElementById("md-dialog-backdrop");
    dialog.querySelector(".md-dialog__body").textContent = message;
    dialog.style.display = "flex";

    const cleanup = () => {
        dialog.style.display = "none";
        confirmBtn.onclick = cancelBtn.onclick = null;
    };

    const confirmBtn = document.getElementById("md-btn-confirm");
    const cancelBtn = document.getElementById("md-btn-cancel");

    cancelBtn.onclick = cleanup;
    confirmBtn.onclick = () => {
        cleanup();
        onConfirm();
    };
}

document.getElementById("add-files-input").onchange = async (e) => {
    const fileInput = e.target;
    const files = fileInput.files;
    if (!files.length) return;

    fileInput.disabled = true; // 🔐 disable temporarily

    const propertyIdInput = document.getElementById("property_id");
    if (!propertyIdInput) {
        alert("Property ID not found.");
        fileInput.disabled = false;
        return;
    }

    const incomingCount = files.length;

    // 🔍 Fetch current gallery to count existing images
    let currentCount = 0;
    try {
        const res = await fetch(
            `/user/gallery/list?temp_id=${propertyIdInput.value}`
        );
        const gallery = await res.json();
        currentCount = gallery.length;
    } catch (err) {
        console.error("Failed to get current gallery count", err);
        alert("Cannot validate image limit. Please try again.");
        return;
    }

    if (currentCount + incomingCount > 15) {
        alert("ลงรูปได้สูงสุด 15 รูปต่อประกาศ");
        return;
    }

    const formData = new FormData();
    [...files].forEach((file, i) => {
        formData.append("images[]", file);
        formData.append("orders[]", i);
        formData.append("cover[]", 0);
    });
    formData.append("temp_id", propertyIdInput.value); // For backend compatibility

    e.target.value = "";
    const spinner = document.getElementById("upload-spinner");
    spinner.style.display = "block";

    fetch("/user/gallery/upload", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
        },
        body: formData,
    })
        .then((res) => res.json())
        .then(() => {
            const spinnerIcon = document.getElementById("spinner-icon");
            const check = document.getElementById("success-check");

            spinnerIcon.style.display = "none";
            check.style.display = "block";

            return fetch(`/user/gallery/list?temp_id=${propertyIdInput.value}`);
        })
        .then((res) => res.json())
        .then((newGallery) => loadUploadedGallery(newGallery))
        .then(() => {
            setTimeout(() => {
                spinner.classList.add("fade-out");
                setTimeout(() => {
                    spinner.style.display = "none";
                    spinner.classList.remove("fade-out");
                    document.getElementById("success-check").style.display =
                        "none";
                    document.getElementById("spinner-icon").style.display =
                        "block";
                }, 1000);
            }, 500);
        })
        .catch((err) => {
            console.error("Upload error:", err);
            spinner.style.display = "none";
            alert("Failed to upload or reload images: " + err.message);
        })
        .finally(() => {
            fileInput.disabled = false; // ✅ always re-enable
        });
};
