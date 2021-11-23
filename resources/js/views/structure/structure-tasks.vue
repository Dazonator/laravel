<template>

    <div class="col">
        <div class="bg pa-4">
            <v-data-table
                :headers="headers"
                :items="tasks"
                :single-expand="singleExpand"
                :expanded.sync="expanded"
                show-expand
                :search="search"
                :loading="tableloading"
                loading-text="Загрузка задач..."
            >
                <template v-slot:top>
                    <v-toolbar flat>
                        <h3>{{structure.name}}</h3>
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
                        <v-chip>
                            <v-avatar left>
                                <v-img :src="i.img"> </v-img>
                            </v-avatar>
                        </v-chip>
                    </router-link>
                </template>

                <template #item.initiator="{ item }">
                    <v-chip
                        v-if="item.initiator"
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
        </div>
    </div>
</template>

<script>
    export default {
        name: "structure-tasks",
        props: {
            title: String,
        },
        data(){
            return{
                categoryId: Number(this.$route.params.categoryId),
                structure: {},
                tasks: [],
                tableloading: false,
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
                expanded: [],
                singleExpand: true,

            }
        },
        watch: {
            '$route.path' (to, from){
                this.categoryId = Number(this.$route.params.categoryId);
                this.init();
            }
        },
        created() {
            this.init();
        },
        methods: {
            init(){
                if (this.categoryId){
                    axios.get(`/api/tasks/structure/${this.categoryId}`).then(response => {
                        console.log(response.data);
                        this.tasks = response.data.tasks;
                        this.structure = response.data.structure;
                        this.tableloading = false;
                    });
                }
            },
        }
    }
</script>

<style scoped>

</style>
