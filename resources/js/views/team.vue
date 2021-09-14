<template>
    <main class="col py-4">
        <div class="row" v-if="loaded">
            <div class="col team-nav" >
                <div class="">
                    <div class="bg py-6 px-10 mb-2">
                        <h5 class="team-nav__title">ОДЕЖДА</h5>
                        <v-list-item-group
                            color="primary"
                        >
                            <v-list>
                                <v-list-item
                                    v-for="(department, i) in departments"
                                    :key="i"
                                    @click="activeDepartment = i"
                                >
                                    <v-list-item-title
                                        v-text="department.department"
                                    >
                                    </v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-list-item-group>
                    </div>

                    <add-user></add-user>
                </div>

            </div>

            <div class="col">
                <div class="bg pa-4">
                    <template>
                        <v-data-table
                            :headers="headers"
                            :items="departments[activeDepartment].users"
                            :search="search"
                            :loading="tableloading"
                            loading-text="Загрузка задач..."
                            :hide-default-footer="true"
                        >
                            <template v-slot:top>
                                <v-toolbar flat>
                                    <v-spacer></v-spacer>
                                    <v-text-field
                                        v-model="search"
                                        append-icon="mdi-magnify"
                                        label="Поиск..."
                                        single-line
                                        hide-details
                                    ></v-text-field>
                                </v-toolbar>
                            </template>
                            <template #item.img="{ item }">
                                <v-avatar
                                    left
                                    size="28"
                                >
                                    <v-img
                                        :src=item.img
                                        alt=item.name
                                    ></v-img>
                                </v-avatar>
                            </template>
                            <template #item.full_name="{ item }">
                                {{ item.lastname }} {{ item.name }}
                            </template>
                        </v-data-table>
                    </template>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
export default {
    data() {
        return {
            loaded: false,
            tableloading: true,
            departments: [],
            activeDepartment: 0,
            search: '',
            headers: [
                {
                    text: '',
                    align: 'start',
                    sortable: true,
                    value: 'img',
                },
                {
                    text: 'Имя',
                    align: 'start',
                    sortable: true,
                    value: 'full_name',
                },
                {
                    text: 'Должность',
                    align: 'start',
                    sortable: true,
                    value: 'position',
                },
                {
                    text: 'Отдел',
                    align: 'start',
                    sortable: true,
                    value: 'department.department',
                },
            ],
        }
    },
    created(){
        axios.get('/api/team').then(response => {
            console.log(response.data);
            this.departments = response.data;
            this.loaded = true;
            this.tableloading = false;
        });

    },
    methods: {
    },
}
</script>
