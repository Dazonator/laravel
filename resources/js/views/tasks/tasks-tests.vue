<template>

    <div>
        <h1>{{title}}</h1>
        <div class="bg pa-4">
            <v-data-table
                :headers="headers"
                :items="tasks"
                :single-expand="singleExpand"
                :expanded.sync="expanded"
                :search="search"
                :loading="tableloading"
                :items-per-page="30"
                loading-text="Загрузка задач..."
            >
                <template v-slot:top>
                    <v-toolbar
                        flat
                        class="align-items-center"
                    >
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
                    <router-link

                        :to="'/tasks/task/' + item.id"
                    >
                        {{ item.title }}
                    </router-link>
                </template>

                <template #item.responsibles="{ item }">
                    <router-link
                        v-for="i in item.responsibles"
                        :key="i.id"
                        :to="'/profile/' + i.id"
                    >
                        <v-chip
                            pill
                        >
                            <v-avatar left>
                                <v-img :src="i.img"> </v-img>
                            </v-avatar>
                            {{i.lastname}}
                        </v-chip>
                    </router-link>
                </template>

                <template #item.initiator="{ item }">
                    <v-chip
                        pill
                        v-if="item.initiator"
                    >
                        <v-avatar left>
                            <v-img :src="item.initiator.img"></v-img>
                        </v-avatar>
                        {{item.initiator.lastname}}

                    </v-chip>
                </template>

                <template #item.deadline="{ item }">
                    <span
                        :class="{'dedline-end': new Date() > new Date(item.deadline)}"
                    >{{item.deadline}}</span>
                </template>
            </v-data-table>
        </div>
    </div>
</template>
<script>
    export default {
        name: "tasks-tests",
        props: {
            id: Number,
            title: String,
        },
        data(){
            return{
                tasks: [],
                tableloading: true,
                expanded: [],
                singleExpand: true,
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
                        value: 'responsibles',
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
                ],
            }
        },
        created(){
            this.init();
        },
        methods: {
            init(){
                axios.post(`/api/tasks/tests`).then(response => {
                    this.tasks = response.data;
                    this.tableloading = false;
                });
            },
        },

    }
</script>

<style scoped>

</style>
