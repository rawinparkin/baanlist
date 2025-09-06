<template>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="messages-container margin-top-0">
                <!-- Message List -->
                <div v-if="!selectedUser">
                    <!-- Headline only -->
                    <div class="messages-headline">
                        <h4>ข้อความทั้งหมด</h4>
                    </div>

                    <!-- User list -->
                    <div class="messages-inbox">
                        <ul>
                            <li
                                v-for="(user, index) in filteredUsers"
                                :key="index"
                                :class="{ unread: !user.is_read }"
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
                                                <i v-if="!user.is_read"
                                                    >ไม่ได้อ่าน</i
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
                        <a
                            href="#"
                            class="message-action"
                            @click.prevent="deleteConversation"
                        >
                            <i class="fa fa-trash"></i> ลบบทสนทนา
                        </a>
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
                                                    <i v-if="!user.is_read"
                                                        >ไม่ได้อ่าน</i
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
                            <!-- Scrollable messages -->
                            <div
                                class="message-scroll-wrapper"
                                ref="messageScroll"
                            >
                                <div
                                    v-for="(msg, index) in conversationMessages"
                                    :key="index"
                                    :class="[
                                        'message-bubble',
                                        msg.sender_id === myId ? 'me' : '',
                                    ]"
                                >
                                    <div class="message-avatar">
                                        <img
                                            :src="
                                                msg.sender_id === myId
                                                    ? myPhoto
                                                        ? '/upload/users/' +
                                                          myId +
                                                          '/' +
                                                          myPhoto
                                                        : '/upload/users/boy.png'
                                                    : selectedUser.photo
                                                    ? '/upload/users/' +
                                                      selectedUser.id +
                                                      '/' +
                                                      selectedUser.photo
                                                    : '/upload/users/boy.png'
                                            "
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
                                    Send Message
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
        };
    },

    computed: {
        filteredUsers() {
            return this.users.filter((u) => u.id !== this.myId);
        },
    },

    created() {
        this.getAllUser();
        this.getMyId();
    },

    methods: {
        scrollToBottom() {
            this.$nextTick(() => {
                const el = this.$refs.messageScroll;
                if (el) {
                    el.scrollTo({ top: el.scrollHeight, behavior: "smooth" });
                }
            });
        },
        getAllUser() {
            axios
                .get("/user-all")
                .then((res) => {
                    this.users = res.data;
                })
                .catch((err) => {
                    console.error("Failed to load users", err);
                });
        },

        getMyId() {
            axios.get("/api/auth-id").then((res) => {
                this.myId = res.data.id;
                this.myPhoto = res.data.photo;
            });
        },

        openConversation(user) {
            this.selectedUser = user;
            if (user.is_read === 0) {
                // Mark messages as read only if unread
                axios
                    .post(`/conversation/read/${user.id}`)
                    .then(() => {
                        user.is_read = 1; // Update locally on success
                    })
                    .catch((err) => {
                        console.error("Failed to mark messages as read", err);
                    });
            }

            // ✅ Load conversation
            axios
                .get(`/conversation/${user.id}`)
                .then((res) => {
                    this.conversationMessages = res.data;
                    this.scrollToBottom();

                    // ✅ Optionally update is_read on the selected user
                    const userToUpdate = this.users.find(
                        (u) => u.id === user.id
                    );
                    if (userToUpdate) {
                        userToUpdate.is_read = true;
                    }
                })
                .catch((err) => {
                    console.error("Error fetching conversation", err);
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
                    popup: "swal-wide", // Optional custom width class
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    axios
                        .delete(`/conversation/delete/${this.selectedUser.id}`)
                        .then((res) => {
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
        closeConversation() {
            this.selectedUser = null;
            this.conversationMessages = [];
        },
        sendMessage() {
            if (!this.newMessage.trim()) return;

            axios
                .post("/send-message", {
                    msg: this.newMessage,
                    receiver_id: this.selectedUser.id,
                })
                .then(() => {
                    this.conversationMessages.push({
                        message: this.newMessage,
                        sender_id: this.myId,
                        receiver_id: this.selectedUser.id,
                        created_at: new Date(),
                    });
                    this.newMessage = "";
                    // Refresh sidebar users to update last_message, last_message_time
                    this.getAllUser();
                    this.scrollToBottom();
                })
                .catch((err) => {
                    console.error("Error sending message", err);
                });
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
    max-height: 60vh; /* or fixed px like 500px */
}

.message-scroll-wrapper {
    flex-grow: 1;
    overflow-y: auto;
    padding: 10px;
}
.conver {
    display: flex;
    flex-direction: column;
    max-height: 60vh; /* or fixed px like 500px */
}
.message-reply {
    flex-shrink: 0; /* prevent shrinking */
}
</style>
