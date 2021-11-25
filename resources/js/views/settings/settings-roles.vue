<template>
    <main class="col py-4" v-if="loaded">
        <h1>Роли</h1>
        <router-link :to="{name: 'create-role'}" class="nav-link">
            <v-btn
                color="primary"
                elevation="2"
            >Создать роль</v-btn>
        </router-link>
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
                        @click:close=""

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
                        @click:close=""

                    >
                        {{permission.name}}
                    </v-chip>
                </template>
                <template v-slot:item.actions="{ item }">
                    <router-link :to="{path: `/settings/roles/update-role/${item.id}`}" class="nav-link">
                        <v-icon
                            small
                            class="mr-2"
                        >
                            mdi-pencil
                        </v-icon>
                    </router-link>
                    <v-icon
                        small
                        @click="deleteItem(item)"
                    >
                        mdi-delete
                    </v-icon>
                </template>
            </v-data-table>
        </v-card>
    </main>
</template>

<script>
    export default {
        name: "settings-roles",
        data(){
            return{
                loaded: false,
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
            }
        }
    }
</script>

<style scoped>

</style>
