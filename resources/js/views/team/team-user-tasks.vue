<template>
    <v-col
        v-if="loaded"
    >
        <div class="user-inform mb-4">
            <router-link
                :to="'/profile/' + user.id"
            >
                <v-avatar
                    left
                    size="28"
                    class=""
                >
                    <v-img
                        :src=user.img
                        alt=item.name
                    ></v-img>
                </v-avatar>
                {{user.name}} {{user.lastname}}
            </router-link>
        </div>
        <div class="bg pa-4">
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
                                    v-if="item.initiator"
                                >
                                    <v-avatar left>
                                        <v-img :src="item.initiator.img"></v-img>
                                    </v-avatar>
                                    {{item.initiator.name}}
                                    {{item.initiator.lastname}}

                                </v-chip>
                            </template>

                            <template #item.deadline="{ item }">
                                <span
                                    :class="{'dedline-end': new Date() > new Date(item.deadline)}"
                                >{{item.deadline}}</span>
                            </template>
                        </v-data-table>
                    </v-card>
                </v-tab-item>
            </v-tabs-items>
        </div>
    </v-col>
</template>

<script>
export default {
    name: "team-user-tasks",
    props: ['id'],
    data(){
        return{
            loaded: false,
            tableLoading: true,
            tab: null,
            user: [],
            data: [],
            statuses: [],
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
                    text: 'Дата старта',
                    value: 'startdate',
                    sortable: true,
                },
                {
                    text: 'Дедлайн',
                    value: 'deadline',
                    sortable: true,
                },
            ],
            expanded: [],
            singleExpand: true,
        }
    },
    created() {
        axios.post(`/api/team/users/${this.id}`).then(response => {
            this.data = response.data;
            this.user = this.data.user;
            this.statuses = this.data.status;
            this.loaded = true;
            this.tableLoading = false;
        });
    },
    methods:{

    }
}
</script>

<style scoped>
    .dedline-end{
        color: red;
    }
</style>
