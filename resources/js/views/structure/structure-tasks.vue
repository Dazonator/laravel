<template>
    <div class="bg pa-4">
        <div
            class="d-flex mb-6"
        >
            <v-tabs
                v-model="tab"
            >
                <v-tab
                    v-for="status in statuses"
                    :key="status.id"
                >
                    {{ status.status }}
                </v-tab>
            </v-tabs>
            <v-spacer>

            </v-spacer>
            <v-text-field
                v-model="search"
                append-icon="mdi-magnify"
                label="Поиск..."
                single-line
                hide-details
            ></v-text-field>
        </div>

        <v-tabs-items v-model="tab">
            <v-tab-item
                v-for="status in statuses"
                :key="status.id"
            >
                <v-card flat>
                    <v-data-table
                        :headers="headers"
                        :items="status.status_tasks"
                        :single-expand="singleExpand"
                        :expanded.sync="expanded"
                        :search="search"
                        :items-per-page="30"
                        :loading="tableloading"
                        loading-text="Загрузка задач..."
                    >
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
                    </v-data-table>
                </v-card>
            </v-tab-item>
        </v-tabs-items>
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
                statuses: [],
                tab: null,
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
                        text: 'Приоритет',
                        value: 'priority.priority',
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
                    axios.post(`/api/tasks/structure/${this.categoryId}`).then(response => {
                        // console.log(response.data);
                        this.statuses = response.data;
                        this.tableloading = false;
                    });
                }
            },
        }
    }
</script>

<style scoped>

</style>
