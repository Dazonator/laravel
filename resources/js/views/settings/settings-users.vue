<template>
    <main class="col py-4" v-if="loaded">
        <h1>Пользователи</h1>

        <add-user
            class="mb-4"
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
                        @click:close=""
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
                    <v-icon
                        small
                        class="mr-2"
                        @click="editItem(item)"
                    >
                        mdi-pencil
                    </v-icon>
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
        name: "settings-users",
        data(){
            return{
                loaded: false,
                search: '',
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
            }
        }
    }
</script>

<style scoped>

</style>
