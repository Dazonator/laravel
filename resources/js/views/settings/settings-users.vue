<template>
    <div v-if="loaded">
        <add-user
            class="mb-4"
            :updateId="updateId"
            @close="closeDialog()"
        ></add-user>
        <v-card>
            <v-card-title>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="поиск..."
                    single-line
                    hide-details
                ></v-text-field>
            </v-card-title>

            <v-data-table
                :headers="headers"
                :items="users"
                :search="search"
            >

                <template #item.name="{ item }">
                    {{item.name}} {{item.lastname}}
                </template>
                <template v-slot:item.roles="{ item }">
                    <v-chip
                        v-if="item.roles.length > 0"
                        v-for="role in item.roles"
                        :key="role.id"
                        class="ma-2"
                        close
                        @click:close="removeRole(item, role.id)"
                    >
                        {{role.name}}
                    </v-chip>
                    <span
                        v-if="item.roles.length === 0"
                    >
                        Нет ролей
                    </span>
                </template>
                <template v-slot:item.actions="{ item }">
                    <v-btn-toggle>
                        <v-btn
                            x-small
                            @click="updateId=item.id"
                        >
                            <v-icon
                                small
                            >
                                mdi-pencil
                            </v-icon>
                        </v-btn>
                        <v-btn
                            x-small
                            @click="deleteId = item.id"
                        >
                            <v-icon
                                small
                            >
                                mdi-delete
                            </v-icon>
                        </v-btn>
                    </v-btn-toggle>
                </template>
            </v-data-table>
        </v-card>

        <v-dialog
            v-model="userDelete"
            max-width="290"
        >
            <v-card>
                <v-card-title class="text-h5">
                    Вы действительно хотите удалить роль?
                </v-card-title>

                <v-card-actions>

                    <v-btn
                        color="green darken-1"
                        text
                        @click="deleteId = null"
                    >
                        Нет
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="green darken-1"
                        text
                        @click="deleteUser()"
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
        name: "settings-users",
        data(){
            return{
                loaded: false,
                search: '',
                updateId: null,
                deleteId: null,
                userDelete: false,
                headers: [
                    {
                        text: 'Пользователь ФИО',
                        align: 'start',
                        value: 'name',
                    },
                    {
                        text: 'Роли',
                        value: 'roles',
                        sortable: false,
                    },
                    {
                        text: '',
                        value: 'actions',
                        sortable: false,
                        align: 'end',
                    },
                ],
                users: [],
            }
        },
        watch:{
            deleteId: function (q){
                if (this.deleteId){
                    this.userDelete = true;
                } else {
                    this.userDelete = false;
                }
            },
        },
        created() {
            this.init();
        },
        methods: {
            init(){
                axios.post(`/api/settings/getUsers`).then(response => {
                    console.log(response.data);
                    this.users = response.data;
                    this.loaded = true;
                });
            },
            closeDialog(){
                this.updateId = null;
                this.init();
            },
            deleteUser(q){
                axios.post(`/api/settings/users/delete/${this.deleteId}`).then(response => {
                    this.deleteId = null;
                    this.init();
                });
            },
            removeRole(user, role){
                let fields = {
                    user: user,
                    role: role,
                };
                axios.post(`/api/settings/removeRoleFromUser`, fields).then(response => {
                    this.init();

                    this.$store.dispatch('user/getAppParameters');
                });
            }
        }
    }
</script>

<style scoped>

</style>
