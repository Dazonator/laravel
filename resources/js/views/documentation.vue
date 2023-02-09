<template>
    <div
        v-if="loaded"
        class="documentation"
    >
        <div
            class="mb-4"
        >
            <v-btn
                v-if="isPermission('notifications')"
                @click="openDialog = !openDialog"
                color="primary"
            >
                Добавить документацию
            </v-btn>
            <documentation-dialog
                :open="openDialog"
                @close="closeDialog()"
                :updateId="updateId"
            ></documentation-dialog>
        </div>
        <div>
            <v-tabs
                vertical
                elevation="1"
            >

                <template v-for="(item, index) in documentations">
                    <v-tab class="justify-start">
                        <v-icon left>
                            {{ item.icon ? item.icon : 'mdi-information'}}
                        </v-icon>
                        {{ item.title }}
                    </v-tab>

                    <v-tab-item>
                        <v-card flat>
                            <div
                                class="d-flex pa-2"
                                v-if="isPermission('update-documentation')"
                            >
                                <v-spacer></v-spacer>
                                <v-btn
                                    icon
                                    @click="updateDocumentation(item.id)"
                                >
                                    <v-icon
                                        small
                                    >mdi-pencil</v-icon>
                                </v-btn>
                                <v-btn
                                    icon
                                    @click="deleteId = item.id"
                                >
                                    <v-icon
                                        small
                                    >mdi-delete</v-icon>
                                </v-btn>
                            </div>

                            <v-card-text v-html="item.text"></v-card-text>
                        </v-card>
                    </v-tab-item>
                </template>
            </v-tabs>
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
                        @click="deleteDocumentation()"
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
        name: "documentation",
        data() {
            return{
                loaded: false,
                documentations: [],

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
                axios.post(`/api/documentation/getAllDocumentations`).then(response => {
                    this.documentations = response.data;
                    this.loaded = true;
                });
            },
            closeDialog(){
                this.updateId = null;
                this.openDialog = false;
                this.init();
            },
            deleteDocumentation(){
                axios.post(`/api/documentation/delete/${this.deleteId}`).then(response => {
                    this.deleteId = null;
                    this.dialogDelete = false;
                    this.init();
                });
            },
            updateDocumentation(q){
                this.updateId = q;
                this.openDialog = true;
            }
        },
    }
</script>

<style>
    .documentation .v-tabs--vertical>.v-tabs-bar .v-tabs-bar__content{
        border-right: 2px solid #aaa;
    }
    .documentation-text img{
        max-width: 100%;
    }
</style>
