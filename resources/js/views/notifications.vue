<template>
    <div
        v-if="loaded"
    >
        <div
            class="mb-4"
        >
            <v-btn
                v-if="isPermission('notifications')"
                @click="openDialog = !openDialog"
                color="primary"
            >
                Добавить уведомление
            </v-btn>
            <add-notification
                :open="openDialog"
                @close="closeDialog()"
                :updateId="updateId"
            ></add-notification>
        </div>
        <div
            class="bg px-6 py-4 mb-4"
            v-for="notification in notifications"
            :key="notification.id"
        >
            <div class="d-flex">
                <h5>
                    {{notification.title}}
                </h5>
                <v-spacer></v-spacer>
                <v-btn-toggle
                    shaped
                >
                    <v-btn
                        v-if="isPermission('notifications')"
                        x-small
                        @click="updateNotification(notification.id)"
                    >
                        <v-icon
                            x-small
                        >mdi-pencil</v-icon>
                    </v-btn>
                    <v-btn
                        v-if="isPermission('notifications')"
                        x-small
                        @click="deleteId = notification.id"
                    >
                        <v-icon
                            x-small
                        >mdi-delete</v-icon>
                    </v-btn>
                </v-btn-toggle>
            </div>
            <div
                v-html="notification.text"
                class="notification-text"
            ></div>
        </div>

        <v-dialog
            v-model="dialogDelete"
            max-width="290"
        >
            <v-card>
                <v-card-title class="text-h5">
                    Вы действительно хотите удалить уведомление?
                </v-card-title>

                <v-card-actions>

                    <v-btn
                        color="green darken-1"
                        text
                        @click="dialogDelete = false"
                    >
                        Нет
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="green darken-1"
                        text
                        @click="deleteNotification()"
                    >
                        Да
                    </v-btn>

                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    export default {
        name: "notifications",
        data(){
            return{
                loaded: false,
                notifications: [],
                toggle_one: false,

                openDialog: false,
                dialogDelete: false,
                deleteId: null,
                updateId: null,
            }
        },
        watch: {
            deleteId: function () {
                if(this.deleteId){
                    this.dialogDelete = true;
                }
            }
        },
        computed:{
            permissions: function (){
                return this.$store.getters['user/permissions'];
            }
        },
        created() {
            this.init();
            window.Echo.channel('channel-notifications-page').listen('.event-notifications', (e) => {
                // console.log('@!!!!!!!!!!!!!!!!!!!!');
                this.init();
            });
        },
        methods: {
            isPermission(per){
                let permissions = this.permissions;
                if(String(permissions).indexOf(per) >= 0){
                    return true;
                }
                return false;
            },
            init(){
                axios.post(`/api/notifications/getAllNotifications`).then(response => {
                    this.$store.dispatch('notifications/getNotifications');
                    this.notifications = response.data;
                    this.loaded = true;
                });
            },
            closeDialog(){
                this.updateId = null;
                this.openDialog = false;
                this.init();
            },
            deleteNotification(){
                axios.post(`/api/notifications/deleteNotification/${this.deleteId}`).then(response => {
                    this.deleteId = null;
                    this.dialogDelete = false;
                    this.init();
                });
            },
            updateNotification(q){
                this.updateId = q;
                this.openDialog = true;
            }
        },
        destroyed() {
            window.Echo.leaveChannel('channel-notifications-page');
        }

    }
</script>

<style>
    .notification-text img{
        max-width: 100%;
    }
</style>
