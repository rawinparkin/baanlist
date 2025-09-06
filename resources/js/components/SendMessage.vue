<template>
    <div id="small-dialog" class="zoom-anim-dialog mfp-hide">
        <div class="small-dialog-header">
            <h3>ข้อความถึง {{ ownerName }}</h3>
        </div>
        <div class="message-reply margin-top-0">
            <textarea
                ref="chatTextarea"
                v-model="form.msg"
                name="message"
                cols="40"
                rows="3"
                placeholder="ข้อความ..."
            ></textarea>
            <button type="button" class="button border" @click="sendMsg">
                ส่งข้อความ
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        ownerName: String,
        receiverId: [String, Number],
        pageUrl: String,
    },
    data() {
        return {
            form: {
                msg: "",
            },
            errors: {},
            succMessage: {},
        };
    },
    mounted() {
        console.log("Chat with:", this.ownerName, "ID:", this.receiverId);
    },
    methods: {
        sendMsg() {
            // Prevent sending message to self (client-side check)
            if (parseInt(this.receiverId) === parseInt(window.authUserId)) {
                Toastify({
                    text: "ไม่สามารถส่งข้อความถึงตัวเองได้",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#f44336",
                    stopOnFocus: true,
                }).showToast();
                return;
            }
            if (!this.form.msg.trim()) {
                Toastify({
                    text: "กรุณากรอกข้อความก่อนส่ง",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#f44336",
                    stopOnFocus: true,
                }).showToast();
                return;
            }
            let fullMessage = `${this.form.msg} (ส่งจากประกาศ: ${this.pageUrl})`;

            if (fullMessage.length > 1000) {
                fullMessage = fullMessage.slice(0, 997) + "...";
            }

            axios

                .post("/send-message", {
                    msg: fullMessage,
                    receiver_id: parseInt(this.receiverId),
                })
                .then((res) => {
                    this.form.msg = "";
                    this.succMessage = res.data;

                    Toastify({
                        text: res.data.message,
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#84c015",
                        stopOnFocus: true,
                    }).showToast();

                    setTimeout(() => {
                        $.magnificPopup.close();
                    }, 1000);
                })
                .catch((err) => {
                    if (err.response && err.response.status === 422) {
                        // Show backend validation error message (like "Cannot send message to yourself")
                        Toastify({
                            text: err.response.data.message || "เกิดข้อผิดพลาด",
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#f44336",
                            stopOnFocus: true,
                        }).showToast();
                    } else if (
                        err.response &&
                        err.response.data &&
                        err.response.data.errors
                    ) {
                        this.errors = err.response.data.errors;

                        Toastify({
                            text: Object.values(this.errors).join("\n"),
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#f44336",
                            stopOnFocus: true,
                        }).showToast();
                    }
                });
        },
    },
};
</script>

<style scoped>
/* Optional: add highlight or custom styles here */
</style>
