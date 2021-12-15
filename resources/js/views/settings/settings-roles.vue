<template>
    <div v-if="loaded">
        <v-btn
            color="primary"
            elevation="2"
            :to="{name: 'create-role'}"
            class="mb-4"
        >Создать роль</v-btn>
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
                :items="roles"
                :search="search"
            >
                <template #item.users="{ item }">
                    <v-chip
                        v-if="item.users.length > 0"
                        v-for="user in item.users"
                        :key="user.id"
                        class="ma-2"
                        close
                        @click:close="removeUser(user.id, item.id)"

                    >
                        {{user.name}} {{user.lastname}}
                    </v-chip>
                    <span
                        v-if="item.users.length === 0"
                    >
                        Нет пользователей
                    </span>
                </template>
                <template #item.permissions="{ item }">
                    <v-chip
                        v-for="permission in item.permissions"
                        :key="permission.id"
                        class="ma-2"
                        close
                        @click:close="removePermission(item.id, permission.id)"

                    >
                        {{permission.name}}
                    </v-chip>
                </template>
                <template v-slot:item.actions="{ item }">
                    <v-btn-toggle>
                        <v-btn
                            x-small
                            :to="{path: `/settings/roles/update-role/${item.id}`}"
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
            v-model="dialogDelete"
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
                        @click="deleteRole()"
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
        name: "settings-roles",
        data(){
            return{
                loaded: false,
                dialogDelete: false,
                deleteId: null,
                roles: [],
                search: '',
                headers: [
                    {
                        text: 'Роль',
                        align: 'start',
                        value: 'name',
                    },
                    {
                        text: 'Пользователи',
                        align: 'center',
                        value: 'users',
                    },
                    {
                        text: 'Разрешения',
                        align: 'center',
                        value: 'permissions',
                    },
                    {
                        text: '',
                        value: 'actions',
                        sortable: false,
                        align: 'end',
                    },
                ],
            }
        },
        watch:{
            deleteId: function (q){
                if (this.deleteId){
                    this.dialogDelete = true;
                } else {
                    this.dialogDelete = false;
                }
            },
        },
        created() {
            this.init();
        },
        methods: {
            init(){
                axios.post(`/api/settings/getRoles`).then(response => {
                    console.log(response.data);
                    this.roles = response.data;
                    this.loaded = true;
                });
            },
            deleteRole(){
                axios.post(`/api/settings/role/delete/${this.deleteId}`).then(response => {
                    this.deleteId = null;
                    this.init();
                    this.$store.dispatch('user/getAppParameters');
                });
            },
            removeUser(user, role){
                let fields = {
                    user: user,
                    role: role,
                };
                axios.post(`/api/settings/removeRoleFromUser`, fields).then(response => {
                    this.init();
                    this.$store.dispatch('user/getAppParameters');
                });
            },
            removePermission(role, permission){
                let fields = {
                    role: role,
                    permission: permission,
                };
                axios.post(`/api/settings/removePermissionFromRole`, fields).then(response => {
                    this.init();
                    this.$store.dispatch('user/getAppParameters');
                });
            }
        }
    }
</script>

<style scoped>

</style>
