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
                            :search="search"
                        >
                            <template v-slot:top>
                                <v-toolbar flat>
                                    <v-btn
                                        color="primary"
                                        class="mb-2"
                                        @click="addTask = !addTask"
                                    >
                                        Добавить задачу
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

                            <template #item.initiator="{ item }">
                                <v-chip
                                >
                                    <v-avatar left>
                                        <v-img :src="item.initiator.img"></v-img>
                                    </v-avatar>
                                    {{item.initiator.name}}
                                    {{item.initiator.lastname}}

                                </v-chip>
                            </template>

                            <template v-slot:expanded-item="{ headers, item }">
                                <td :colspan="headers.length">
                                    {{ item.text }}
                                </td>
                            </template>
                        </v-data-table>
                    </template>
                </div>
            </div>
            <add-task
                v-show="addTask"
            ></add-task>
        </div>
    </main>
</template>

<script>
export default {
    data() {
        return {
            addTask: false,
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
                    text: 'Ответственные',
                    value: 'performers_id',
                    sortable: true,
                },
                {
                    text: 'Постановщик',
                    value: 'initiator',
                    sortable: true,
                },
                {
                    text: 'Дедлайн',
                    value: 'deadline',
                    sortable: true,
                },
                {
                    text: 'Приоритет',
                    value: 'priority.priority',
                    sortable: true,
                },
                {
                    text: 'Статус',
                    value: 'status.status',
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
            console.log(response.data);
            this.tasks = response.data;
        });
    },
    methods: {
    },
}
</script>
