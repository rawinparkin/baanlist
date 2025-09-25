<template>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="messages-container margin-top-0">
                <!-- Message List -->
                <div v-if="!selectedUser">
                    <div class="messages-headline">
                        <h4>ข้อความทั้งหมด</h4>
                    </div>

                    <!-- User list -->
                    <div class="messages-inbox">
                        <ul>
                            <li
                                v-for="(user, index) in filteredUsers"
                                :key="index"
                                :class="{ unread: user.is_read === 0 }"
                            >
                                <a
                                    href="#"
                                    @click.prevent="openConversation(user)"
                                >
                                    <div class="message-avatar">
                                        <img
                                            :src="
                                                user.photo
                                                    ? '/upload/users/' +
                                                      user.id +
                                                      '/' +
                                                      user.photo
                                                    : '/upload/users/boy.png'
                                            "
                                            alt="User Avatar"
                                        />
                                    </div>
                                    <div class="message-by">
                                        <div class="message-by-headline">
                                            <h5>
                                                {{ user.name }}
                                                <i v-if="user.is_read === 0"
                                                    >ยังไม่ได้อ่าน</i
                                                >
                                            </h5>
                                            <span>{{
                                                user.last_message_time
                                            }}</span>
                                        </div>
                                        <p>
                                            {{
                                                user.last_message ||
                                                "ยังไม่มีข้อความ"
                                            }}
                                        </p>
                                        <!-- <p>
                                            {{ user.name }} - is_read:
                                            {{ user.is_read }} ({{
                                                typeof user.is_read
                                            }})
                                        </p> -->
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Conversation Window -->
                <div v-else class="conversation-window">
                    <div class="messages-headline">
                        <h4>{{ selectedUser.name }}</h4>
                        <!-- <a
                            href="#"
                            class="message-action"
                            @click.prevent="deleteConversation"
                        >
                            <i class="fa fa-trash"></i> ลบบทสนทนา
                        </a> -->
                    </div>

                    <div class="messages-container-inner">
                        <!-- Messages List (Sidebar) -->
                        <div class="messages-inbox conver">
                            <ul>
                                <li
                                    v-for="(user, index) in filteredUsers"
                                    :key="index"
                                    :class="{
                                        'active-message':
                                            selectedUser &&
                                            selectedUser.id === user.id,
                                    }"
                                >
                                    <a
                                        href="#"
                                        @click.prevent="openConversation(user)"
                                    >
                                        <div class="message-avatar">
                                            <img
                                                :src="
                                                    user.photo
                                                        ? '/upload/users/' +
                                                          user.id +
                                                          '/' +
                                                          user.photo
                                                        : '/upload/users/boy.png'
                                                "
                                                alt="User Avatar"
                                            />
                                        </div>
                                        <div class="message-by">
                                            <div class="message-by-headline">
                                                <h5>
                                                    {{ user.name }}
                                                    <i v-if="user.is_read === 0"
                                                        >ยังไม่ได้อ่าน</i
                                                    >
                                                </h5>

                                                <span>{{
                                                    user.last_message_time
                                                }}</span>
                                            </div>
                                            <p>{{ user.last_message }}</p>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Chat Content -->
                        <div class="message-content">
                            <div
                                class="message-scroll-wrapper"
                                ref="messageScroll"
                            >
                                <div
                                    v-for="(msg, index) in conversationMessages"
                                    :key="index"
                                    :class="[
                                        'message-bubble',
                                        Number(msg.sender_id) === Number(myId)
                                            ? 'me'
                                            : '',
                                    ]"
                                >
                                    <div class="message-avatar">
                                        <img
                                            :src="getUserAvatar(msg.sender_id)"
                                            alt="User Avatar"
                                        />
                                    </div>
                                    <div class="message-text">
                                        <p>
                                            {{
                                                decodeURIComponent(msg.message)
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Fixed Reply Area -->
                            <div class="message-reply">
                                <textarea
                                    v-model="newMessage"
                                    cols="40"
                                    rows="3"
                                    placeholder="Your Message"
                                ></textarea>
                                <button class="button" @click="sendMessage">
                                    ส่งข้อความ
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            users: [],
            selectedUser: null,
            conversationMessages: [],
            newMessage: "",
            myId: null,
            myPhoto: null,
        };
    },

    computed: {
        filteredUsers() {
            return this.users.filter((u) => u.id !== this.myId);
        },
    },

    async created() {
        await this.getMyId();
        this.getAllUser();
    },

    mounted() {
        this.pollInterval = setInterval(() => {
            this.pollForUpdates();
        }, 5000);
    },

    beforeUnmount() {
        clearInterval(this.pollInterval);
    },

    methods: {
        // --- Helpers ---
        normalizeUser(user) {
            return {
                ...user,
                id: Number(user.id),
                is_read: Number(user.is_read),
            };
        },

        normalizeMessage(m) {
            return {
                ...m,
                sender_id: Number(m.sender_id),
                receiver_id: Number(m.receiver_id),
            };
        },

        // --- API Calls ---
        async getMyId() {
            try {
                const res = await axios.get("/api/auth-id");
                this.myId = Number(res.data.id);
                this.myPhoto = res.data.photo;
            } catch (err) {
                console.error("Failed to get my ID", err);
            }
        },

        getAllUser() {
            axios
                .get("/user-all")
                .then((res) => {
                    this.users = res.data.map(this.normalizeUser);
                })
                .catch((err) => {
                    console.error("Failed to load users", err);
                });
        },

        openConversation(user) {
            this.selectedUser = this.normalizeUser(user);

            if (user.is_read === 0) {
                axios
                    .post(`/conversation/read/${user.id}`)
                    .then(() => {
                        // Update local users array immediately
                        const idx = this.users.findIndex(
                            (u) => u.id === user.id
                        );
                        if (idx !== -1) {
                            this.users[idx] = {
                                ...this.users[idx],
                                is_read: 1,
                            };
                        }
                        this.getAllUser(); // still refresh from backend
                    })
                    .catch((err) => {
                        console.error("Failed to mark messages as read", err);
                    });
            }

            axios
                .get(`/conversation/${user.id}`)
                .then((res) => {
                    this.conversationMessages = res.data.map(
                        this.normalizeMessage
                    );
                    this.scrollToBottom();
                })
                .catch((err) => {
                    console.error("Error fetching conversation", err);
                });
        },

        pollForUpdates() {
            if (this.selectedUser) {
                axios
                    .get(`/conversation/${this.selectedUser.id}`)
                    .then((res) => {
                        this.conversationMessages = res.data.map(
                            this.normalizeMessage
                        );
                        this.scrollToBottom();

                        //If user is currently active, mark as read immediately
                        axios
                            .post(`/conversation/read/${this.selectedUser.id}`)
                            .then(() => {
                                const idx = this.users.findIndex(
                                    (u) => u.id === this.selectedUser.id
                                );
                                if (idx !== -1) {
                                    this.users[idx] = {
                                        ...this.users[idx],
                                        is_read: 1,
                                    };
                                }
                            });
                    })
                    .catch((err) => {
                        console.error("Error polling conversation", err);
                    });
            }

            this.getAllUser();
        },

        sendMessage() {
            if (!this.newMessage.trim()) return;

            axios
                .post("/send-message", {
                    msg: this.newMessage,
                    receiver_id: this.selectedUser.id,
                })
                .then(() => {
                    this.conversationMessages.push(
                        this.normalizeMessage({
                            message: this.newMessage,
                            sender_id: this.myId,
                            receiver_id: this.selectedUser.id,
                            created_at: new Date(),
                        })
                    );
                    this.newMessage = "";
                    this.scrollToBottom();
                    this.getAllUser();
                })
                .catch((err) => {
                    console.error("Error sending message", err);
                });
        },

        deleteConversation() {
            Swal.fire({
                title: "คุณแน่ใจหรือไม่?",
                text: "คุณต้องการลบบทสนทนานี้หรือไม่?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#aaa",
                confirmButtonText: "ลบเลย",
                cancelButtonText: "ยกเลิก",
                customClass: {
                    popup: "swal-wide",
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    axios
                        .delete(`/conversation/delete/${this.selectedUser.id}`)
                        .then(() => {
                            Swal.fire({
                                title: "ลบแล้ว!",
                                text: "บทสนทนาได้ถูกลบเรียบร้อยแล้ว",
                                icon: "success",
                                customClass: "swal-wide",
                            });

                            this.closeConversation();
                            this.getAllUser();
                        })
                        .catch((err) => {
                            console.error("Error deleting conversation", err);
                            Swal.fire(
                                "เกิดข้อผิดพลาด",
                                "ไม่สามารถลบบทสนทนาได้",
                                "error"
                            );
                        });
                }
            });
        },

        // --- UI Helpers ---
        closeConversation() {
            this.selectedUser = null;
            this.conversationMessages = [];
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const el = this.$refs.messageScroll;
                if (el) {
                    el.scrollTo({ top: el.scrollHeight, behavior: "smooth" });
                }
            });
        },

        getUserAvatar(senderId) {
            if (Number(senderId) === Number(this.myId)) {
                return this.myPhoto
                    ? `/upload/users/${this.myId}/${this.myPhoto}`
                    : "/upload/users/boy.png";
            } else {
                return this.selectedUser && this.selectedUser.photo
                    ? `/upload/users/${this.selectedUser.id}/${this.selectedUser.photo}`
                    : "/upload/users/boy.png";
            }
        },
    },
};
</script>

<style>
.messages-container {
    max-width: 90vw;
}
.message-content {
    display: flex;
    flex-direction: column;
    max-height: 60vh;
}
.message-scroll-wrapper {
    flex-grow: 1;
    overflow-y: auto;
    padding: 10px;
}
.conver {
    display: flex;
    flex-direction: column;
    max-height: 60vh;
}
.message-reply {
    flex-shrink: 0;
}
</style>
