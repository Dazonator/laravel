<template>
    <div>
<!--        <h1>{{title}}</h1>-->
<!--        <div class="bg pa-4">-->
<!--            <v-data-table-->
<!--                :headers="headers"-->
<!--                :items="tasks"-->
<!--                :single-expand="singleExpand"-->
<!--                :expanded.sync="expanded"-->
<!--                :search="search"-->
<!--                :loading="tableloading"-->
<!--                :items-per-page="30"-->
<!--                loading-text="Загрузка задач..."-->
<!--            >-->
<!--                <template v-slot:top>-->
<!--                    <v-toolbar-->
<!--                        flat-->
<!--                        class="align-items-center"-->
<!--                    >-->
<!--                        <v-spacer></v-spacer>-->
<!--                        <v-text-field-->
<!--                            v-model="search"-->
<!--                            append-icon="mdi-magnify"-->
<!--                            label="Поиск..."-->
<!--                            single-line-->
<!--                            hide-details-->
<!--                        ></v-text-field>-->
<!--                    </v-toolbar>-->
<!--                </template>-->
<!--                <template #item.title="{ item }">-->
<!--                    <router-link-->

<!--                        :to="'/tasks/task/' + item.id"-->
<!--                    >-->
<!--                        {{ item.title }}-->
<!--                    </router-link>-->
<!--                </template>-->

<!--                <template #item.responsibles="{ item }">-->
<!--                    <router-link-->
<!--                        v-for="i in item.responsibles"-->
<!--                        :key="i.id"-->
<!--                        :to="'/profile/' + i.id"-->
<!--                    >-->
<!--                        <v-chip-->
<!--                            pill-->
<!--                        >-->
<!--                            <v-avatar left>-->
<!--                                <v-img :src="i.img"> </v-img>-->
<!--                            </v-avatar>-->
<!--                            {{i.lastname}}-->
<!--                        </v-chip>-->
<!--                    </router-link>-->
<!--                </template>-->

<!--                <template #item.initiator="{ item }">-->
<!--                    <v-chip-->
<!--                        pill-->
<!--                        v-if="item.initiator"-->
<!--                    >-->
<!--                        <v-avatar left>-->
<!--                            <v-img :src="item.initiator.img"></v-img>-->
<!--                        </v-avatar>-->
<!--                        {{item.initiator.lastname}}-->

<!--                    </v-chip>-->
<!--                </template>-->

<!--                <template #item.deadline="{ item }">-->
<!--                    <span-->
<!--                        :class="{'dedline-end': new Date() > new Date(item.deadline)}"-->
<!--                    >{{item.deadline}}</span>-->
<!--                </template>-->
<!--                <template #item.actions="{ item }">-->
<!--                    <div>-->
<!--                        <v-btn-toggle-->
<!--                            v-model="toggle_exclusive"-->
<!--                            rounded-->
<!--                            small-->
<!--                        >-->

<!--                            <v-btn x-small @click="updateTask(item.id)">-->
<!--                                <v-icon x-small>mdi-pencil</v-icon>-->
<!--                            </v-btn>-->

<!--                            <v-btn x-small @click="deleteTask=item" v-if="item.status_id !== 3">-->
<!--                                <v-icon x-small>mdi-delete</v-icon>-->
<!--                            </v-btn>-->

<!--                            <v-btn x-small @click="pauseTask(item.id)" v-if="item.status_id === 2">-->
<!--                                <v-icon x-small>mdi-pause</v-icon>-->
<!--                            </v-btn>-->

<!--                            <v-btn x-small @click="startTask(item)" v-if="item.status_id !== 2">-->
<!--                                <v-icon x-small>mdi-play</v-icon>-->
<!--                            </v-btn>-->

<!--                            <v-btn x-small @click="taskCompleted(item)" v-if="item.status_id !== 3">-->
<!--                                <v-icon x-small>mdi-check-bold</v-icon>-->
<!--                            </v-btn>-->

<!--                        </v-btn-toggle>-->
<!--                    </div>-->
<!--                </template>-->
<!--            </v-data-table>-->


<!--        </div>-->
        <h1>{{title}}</h1>

        <v-row>
            <v-col
                v-for="task in tasks" :key="task.id"
                cols="12"
                sm="6"
                lg="4"
                xl="3"
            >
                <task-card
                    :task="task"
                    @needInit="init()"
                ></task-card>
            </v-col>
        </v-row>
    </div>

</template>

<script>
import moment from 'moment';
export default {
    name: 'user-tasks',
    props: {
        id: Number,
        title: String,
    },
    watch: {
        id: function (val){
            this.init();
        },
    },
    data() {
        return {
            toggle_exclusive: null,

            tab: null,
            addTask: false,
            tableloading: true,
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
                { text: '', value: 'actions', sortable: false },
            ],
        }
    },
    created(){
        moment.locale('ru');
        this.init();
    },
    methods: {
        init(){
            axios.post(`/api/tasks/statuses/${this.id}`).then(response => {
                this.tasks = response.data;
                this.tableloading = false;
            });
        },
        // updateTask(id){
        //     this.isUpdate = true;
        //     this.updateId = id;
        //     this.dialogUpdate = true;
        // },
    },
    filters: {
        deadLine: function (date) {
            return moment(date).format('LL');
        },
        startDate: function (date) {
            return moment(date).format('ll');
        }
    }
}
</script>

<style scoped>

</style>
