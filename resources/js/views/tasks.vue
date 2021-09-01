<template>

    <main class="col py-4">
        <div class="row">
            <div class="col">
                <h1>Задачи</h1>
                <!--            <pre v-html="tasks"></pre>-->
                <div class="bg pa-4">
                    <template>
                        <v-data-table
                            :headers="headers"
                            :items="tasks"
                            :single-expand="singleExpand"
                            :expanded.sync="expanded"
                            show-expand
                            class="elevation-1"
                            :search="search"
                        >
                            <template v-slot:top>
                                <v-toolbar flat>
                                    <v-btn
                                        color="primary"
                                        dark
                                        class="mb-2"
                                    >
                                        Создать задачу
                                    </v-btn>
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
                            <template #item.title="{ item }">
                                <a :href="`tasks/${item.id}`">
                                    {{ item.title }}
                                </a>
                            </template>

                            <template v-slot:expanded-item="{ headers, item }">
                                <td :colspan="headers.length">
                                    More info about {{ item.text }}
                                </td>
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
            expanded: [],
            singleExpand: true,
            tasks: [],
            search: '',
            headers: [
                {
                    text: 'Название',
                    align: 'start',
                    sortable: true,
                    value: 'title',
                },
                {
                    text: 'Постановщик',
                    value: 'initiator',
                    sortable: true,
                },
                {
                    text: 'Задача создана',
                    value: 'startdate',
                    sortable: true,
                },
                {
                    text: 'Приоритет',
                    value: 'priority',
                    sortable: true,
                },
                {
                    text: '',
                    value: 'data-table-expand'
                },
            ],
        }
    },
    created(){
        axios.get('/api/tasks').then(response => {
            // console.log(response.data);
            this.tasks = response.data;
        });
    },
    methods: {
    },
}
</script>
