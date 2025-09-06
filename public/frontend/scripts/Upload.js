// Generate UUID once per listing page
let tempId = document.getElementById("temp_id")?.value;

if (!tempId) {
    tempId = crypto.randomUUID();
    $(document).ready(function () {
        $("<input>")
            .attr({
                type: "hidden",
                id: "temp_id",
                name: "temp_id",
                value: tempId,
            })
            .appendTo("#myForm");
    });
}

//---------Drop Zone Upload Photos---------------

document.getElementById("add-more-photos").onclick = () =>
    document.getElementById("add-files-input").click();

Dropzone.autoDiscover = false;

let currentCoverFileName = null;

const dropzone = new Dropzone("#media-uploader", {
    url: "/user/gallery/upload",
    headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
            .content,
    },
    paramName: "images",
    autoProcessQueue: false,
    uploadMultiple: true,
    maxFiles: 15,
    acceptedFiles: "image/*",
    addRemoveLinks: true,
    thumbnailWidth: 400,
    thumbnailHeight: 400,
    previewTemplate: `
        <div class="dz-preview dz-file-preview">
            <div class="dz-image"><img data-dz-thumbnail /></div>
            <div class="cover-placeholder"></div>
            <button class="dz-remove-custom" type="button" title="Remove">×</button>
        </div>`,
    init() {
        const dz = this;

        document.getElementById("submit-gallery").disabled = true;
        dz.on("addedfile", () => {
            updateSubmitButtonState();
            updateRemainingSlots();
        });

        dz.on("removedfile", () => {
            updateSubmitButtonState();
            updateRemainingSlots();
        });

        setTimeout(() => {
            new Sortable(dz.previewsContainer, {
                items: ".dz-preview",
                draggable: ".dz-preview",
                onEnd: updateImageOrder,
            });
        }, 0);

        dz.on("addedfile", (file) => {
            const preview = file.previewElement;
            file.previewElement.dropzoneFile = file;
            preview.setAttribute("data-file-name", file.name);

            // Detect .heic or .HEIC files
            if (file.name.toLowerCase().endsWith(".heic")) {
                preview.classList.add("heic-preview");

                const placeholder = preview.querySelector(".dz-image");
                if (placeholder) {
                    placeholder.innerHTML =
                        "Photo will appear<br>once uploaded";
                }
            }

            preview.querySelector(".dz-remove-custom").onclick = (e) => {
                e.preventDefault();
                e.stopPropagation();
                showDialog("ด้องการลบรูปนี้?", () => dz.removeFile(file));
            };

            preview.addEventListener("click", () => {
                document.querySelectorAll(".dz-preview").forEach((el) => {
                    el.classList.remove("is-cover");
                    const icon = el.querySelector(".cover-placeholder");
                    if (icon) icon.innerHTML = "";
                });

                preview.classList.add("is-cover");
                const iconHolder = preview.querySelector(".cover-placeholder");
                if (iconHolder) iconHolder.innerHTML = coverStarSVG();
                updateCoverImage(file);
            });
        });

        dz.on("uploadprogress", (file, progress) => {
            const el = file.previewElement.querySelector(
                "[data-dz-uploadprogress]"
            );
            if (el) el.style.width = `${progress}%`;
        });

        let hasSubmitted = false;

        dz.on("addedfile", function () {
            updateSubmitButtonState();

            if (dz.files.length === 1 && !hasSubmitted) {
                setTimeout(() => {
                    document.getElementById("submit-gallery").click();
                    hasSubmitted = true;
                }, 200);
            }
        });
    },
});

function getTotalUploadedCount() {
    return document.querySelectorAll("#uploaded-gallery .dz-preview").length;
}

function updateRemainingSlots() {
    const uploadedCount = getTotalUploadedCount();
    const remaining = 15 - uploadedCount;
    dropzone.options.maxFiles = remaining;
}

function updateSubmitButtonState() {
    const hasFiles = dropzone.files && dropzone.files.length > 0;
    document.getElementById("submit-gallery").disabled = !hasFiles;
}

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

document.getElementById("submit-gallery").onclick = () => {
    const total = getTotalUploadedCount() + dropzone.files.length;
    if (total > 15) {
        alert("ลงอัปโหลดได้สูงสุด 14 รูปภาพเท่านั้น");
        return;
    }

    const spinner = document.getElementById("upload-spinner");
    spinner.style.display = "block";

    const progressText = document.getElementById("upload-progress-text");
    progressText.textContent = "0%";

    const previews = document.querySelectorAll("#media-uploader .dz-preview");
    const formData = new FormData();

    const tempId = document.getElementById("temp_id").value;
    formData.append("temp_id", tempId);

    if (!currentCoverFileName && previews.length > 0) {
        const firstPreview = previews[0];
        const firstFile = firstPreview.dropzoneFile;
        if (firstFile) {
            currentCoverFileName = firstFile.name;
            firstPreview.classList.add("is-cover");
            const iconHolder = firstPreview.querySelector(".cover-placeholder");
            if (iconHolder) iconHolder.innerHTML = coverStarSVG();
        }
    }

    previews.forEach((preview, index) => {
        const file = preview.dropzoneFile;
        if (file) {
            formData.append("images[]", file);
            formData.append("orders[]", index);
            formData.append(
                "cover[]",
                file.name === currentCoverFileName ? 1 : 0
            );
        }
    });

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "/user/gallery/upload", true);
    xhr.setRequestHeader(
        "X-CSRF-TOKEN",
        document.querySelector('meta[name="csrf-token"]').content
    );

    xhr.upload.onprogress = function (e) {
        if (e.lengthComputable) {
            const percent = Math.round((e.loaded / e.total) * 100);
            progressText.textContent = `${percent}%`;
        }
    };

    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            let data;
            try {
                data = JSON.parse(xhr.responseText);
                if (!data || typeof data !== "object")
                    throw new Error("Invalid response");
            } catch {
                throw new Error(
                    "Server error:\n" + xhr.responseText.slice(0, 300)
                );
            }

            ["media-uploader", "submit-gallery"].forEach((id) => {
                document.getElementById(id).style.display = "none";
            });
            document.getElementById("add-more-photos").style.display =
                "inline-block";

            const spinnerIcon = document.getElementById("spinner-icon");
            const check = document.getElementById("success-check");

            spinnerIcon.style.display = "none";
            check.style.display = "block";

            loadUploadedGallery().then(() => {
                setTimeout(() => {
                    spinner.classList.add("fade-out");
                    setTimeout(() => {
                        spinner.style.display = "none";
                        spinner.classList.remove("fade-out");
                        check.style.display = "none";
                        spinnerIcon.style.display = "block";
                        progressText.textContent = ""; // reset %
                    }, 1000);
                }, 500);
            });
        } else {
            handleUploadError("Upload failed with status " + xhr.status);
        }
    };

    xhr.onerror = function () {
        handleUploadError("Upload failed due to network error.");
    };

    xhr.send(formData);

    function handleUploadError(message) {
        document.getElementById("upload-error").textContent = message;
        spinner.style.display = "none";
        progressText.textContent = "";
    }
};

document.getElementById("add-files-input").onchange = (e) => {
    const total = getTotalUploadedCount() + dropzone.files.length;
    if (total > 15) {
        alert("อัปโหลดได้สูงสุด 14 รูปภาพเท่านั้น");
        return;
    }

    const files = e.target.files;
    if (!files.length) return;

    const formData = new FormData();
    [...files].forEach((file, i) => {
        formData.append("images[]", file);
        formData.append("orders[]", i);
        formData.append("cover[]", 0);
    });
    formData.append("temp_id", tempId);

    e.target.value = "";

    const spinner = document.getElementById("upload-spinner");
    const spinnerIcon = document.getElementById("spinner-icon");
    const check = document.getElementById("success-check");
    const progressText = document.getElementById("upload-progress-text");

    spinner.style.display = "block";
    spinnerIcon.style.display = "block";
    check.style.display = "none";
    progressText.textContent = "0%";

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "/user/gallery/upload", true);
    xhr.setRequestHeader(
        "X-CSRF-TOKEN",
        document.querySelector('meta[name="csrf-token"]').content
    );

    xhr.upload.onprogress = function (e) {
        if (e.lengthComputable) {
            const percent = Math.round((e.loaded / e.total) * 100);
            progressText.textContent = `${percent}%`;
        }
    };

    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            spinnerIcon.style.display = "none";
            check.style.display = "block";

            loadUploadedGallery().then(() => {
                setTimeout(() => {
                    spinner.classList.add("fade-out");
                    setTimeout(() => {
                        spinner.style.display = "none";
                        spinner.classList.remove("fade-out");
                        check.style.display = "none";
                        spinnerIcon.style.display = "block";
                        progressText.textContent = ""; // clear % text
                    }, 1000);
                }, 500);
            });
        } else {
            handleUploadError("Upload failed with status " + xhr.status);
        }
    };

    xhr.onerror = function () {
        handleUploadError("Upload failed due to network error.");
    };

    xhr.send(formData);

    function handleUploadError(message) {
        console.error("Upload error:", message);
        spinner.style.display = "none";
        progressText.textContent = "";
        alert("Failed to upload image(s): " + message);
    }
};

function loadUploadedGallery() {
    const gallery = document.getElementById("uploaded-gallery");
    gallery.innerHTML = "";
    gallery.style.display = "flex";

    return fetch(`/user/gallery/list?temp_id=${tempId}`)
        .then((res) => res.json())
        .then(
            (images) =>
                new Promise((resolve) => {
                    let loaded = 0;
                    images.forEach((image) => {
                        const wrapper = document.createElement("div");
                        wrapper.className = "dz-preview dz-file-preview";
                        wrapper.dataset.id = image.id;
                        if (parseInt(image.is_cover) === 1)
                            wrapper.classList.add("is-cover");

                        wrapper.innerHTML = `
                    <div class="dz-image"><img src="${
                        image.url
                    }" alt="Uploaded image" /></div>
                    <div class="cover-placeholder">${
                        parseInt(image.is_cover) === 1 ? coverStarSVG() : ""
                    }</div>
                    <button class="dz-remove-custom" type="button" title="Remove">×</button>
                `;

                        const img = wrapper.querySelector("img");
                        img.onload = img.onerror = () => {
                            loaded++;
                            if (loaded === images.length) resolve();
                        };

                        wrapper.onclick = () => {
                            if (gallery.style.display === "none") return;
                            gallery
                                .querySelectorAll(".dz-preview")
                                .forEach((el) => {
                                    el.classList.remove("is-cover");
                                    const icon =
                                        el.querySelector(".cover-placeholder");
                                    if (icon) icon.innerHTML = "";
                                });
                            wrapper.classList.add("is-cover");
                            wrapper.querySelector(
                                ".cover-placeholder"
                            ).innerHTML = coverStarSVG();

                            fetch("/user/gallery/set-cover", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": document.querySelector(
                                        'meta[name="csrf-token"]'
                                    ).content,
                                },
                                body: JSON.stringify({
                                    id: wrapper.dataset.id,
                                }),
                            }).catch((err) =>
                                alert(
                                    "Error setting cover image: " + err.message
                                )
                            );
                        };

                        wrapper.querySelector(".dz-remove-custom").onclick = (
                            e
                        ) => {
                            e.preventDefault();
                            e.stopPropagation();
                            showDialog("ต้องการลบรูปนี้?", () => {
                                fetch(
                                    `/user/gallery/delete/${wrapper.dataset.id}?temp_id=${tempId}`,
                                    {
                                        method: "DELETE",
                                        headers: {
                                            "Content-Type": "application/json",
                                            "X-CSRF-TOKEN":
                                                document.querySelector(
                                                    'meta[name="csrf-token"]'
                                                ).content,
                                        },
                                    }
                                )
                                    .then((res) => {
                                        if (!res.ok)
                                            throw new Error(
                                                "Failed to delete image"
                                            );
                                        gallery.removeChild(wrapper);
                                    })
                                    .catch((err) =>
                                        alert(
                                            "Error deleting image: " +
                                                err.message
                                        )
                                    );
                            });
                        };

                        gallery.appendChild(wrapper);
                    });
                    updateRemainingSlots();

                    new Sortable(gallery, {
                        items: ".dz-preview",
                        draggable: ".dz-preview",
                        onEnd: () => {
                            const order = [
                                ...gallery.querySelectorAll(".dz-preview"),
                            ].map((el, i) => ({
                                id: el.dataset.id,
                                order: i,
                            }));

                            fetch("/user/gallery/reorder", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": document.querySelector(
                                        'meta[name="csrf-token"]'
                                    ).content,
                                },
                                body: JSON.stringify({
                                    order,
                                }),
                            }).catch((err) => {
                                console.error("Order update error:", err);
                                alert("Error saving image order.");
                            });
                        },
                    });

                    if (images.length === 0) resolve();
                })
        )
        .catch((err) => {
            console.error("Gallery load error:", err);
            gallery.innerHTML =
                "<p style='color:red;'>Failed to load images</p>";
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
