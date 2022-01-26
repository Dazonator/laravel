<template>

    <div v-if="loaded">
        <h1 class="mb-6">Собрание №{{meeting.number}}</h1>

        <v-btn
            class="mb-4"
            color="primary"
            @click="newTask()"
            v-if="!is_completed && isPermission('distributed-tasks')"
        >
            Создать задачу
        </v-btn>
        <div
            v-if="!is_completed"
        >
            <h3>Распределить</h3>
            <v-data-table
                class="mb-6 bg"
                :headers="headers"
                :items="initialTasks"
                :single-expand="singleExpand"
                :expanded.sync="expanded"
                show-expand
                :search="search"
                loading-text="Загрузка задач..."
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
                <template #item.title="{ item }">
                    <router-link

                        :to="'/tasks/task/' + item.id"
                    >
                        {{ item.title }}
                    </router-link>
                </template>
                <template #item.initiator="{ item }">
                    <v-chip
                        v-if="item.initiator"
                    >
                        <v-avatar left>
                            <v-img :src="item.initiator.img"></v-img>
                        </v-avatar>
                        {{item.initiator.lastname}}

                    </v-chip>
                </template>
                <template v-slot:item.actions="{ item }">
                    <div>
                        <v-btn-toggle
                            v-if="isPermission('distributed-tasks')"
                            small
                        >
                            <v-btn
                                x-small
                                @click="distributionTask(item.id)"
                            >
                                <v-icon
                                    x-small
                                >
                                    mdi-pencil
                                </v-icon>
                            </v-btn>
                            <v-btn
                                x-small
                                @click="deleteId = item.id"
                            >
                                <v-icon
                                    x-small
                                >
                                    mdi-delete
                                </v-icon>
                            </v-btn>
                        </v-btn-toggle>
                    </div>
                </template>
                <template
                    v-slot:item.department_id="{ item }"
                >
                    <v-select
                        v-model="item.distribution_department"
                        :items="departments"
                        item-text="department"
                        item-value="id"
                        label="Для отдела"
                        dense
                        solo
                        hide-details
                        @change="changeDepartment(item)"
                    ></v-select>


                    <!--                    {{initialTasks}}-->
                </template>
                <template v-slot:expanded-item="{ headers, item }">
                    <td :colspan="headers.length" v-html="item.text">
                    </td>
                </template>
            </v-data-table>
        </div>

        <div
            v-if="!is_completed"
        >
            <h3>Внедрить</h3>
            <v-data-table
                class="mb-6 bg"
                :headers="headers"
                :items="distributionTasks"
                :single-expand="singleExpand2"
                :expanded.sync="expanded2"
                show-expand
                :search="search2"
                loading-text="Загрузка задач..."
            >
                <template v-slot:top>
                    <v-toolbar flat>
                        <v-spacer></v-spacer>
                        <v-text-field
                            v-model="search2"
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
                <template #item.initiator="{ item }">
                    <v-chip
                        v-if="item.initiator"
                    >
                        <v-avatar left>
                            <v-img :src="item.initiator.img"></v-img>
                        </v-avatar>
                        {{item.initiator.lastname}}

                    </v-chip>
                </template>
                <template v-slot:item.actions="{ item }">
                    <div>
                        <v-btn-toggle
                            v-if="isPermission('distributed-tasks')"
                            v-model="toggle_exclusive"
                            rounded
                            small
                        >

                            <v-btn
                                x-small
                                @click="distributionTask(item.id)"
                            >
                                <v-icon
                                    x-small
                                >
                                    mdi-pencil
                                </v-icon>
                            </v-btn>
                            <v-btn
                                x-small
                                @click="deleteId = item.id"
                            >
                                <v-icon
                                    x-small
                                >
                                    mdi-delete
                                </v-icon>
                            </v-btn>
                        </v-btn-toggle>
                    </div>
                </template>
                <template
                    v-slot:item.department_id="{ item }"
                >
                    <v-select
                        v-model="item.distribution_department"
                        :items="departments"
                        item-text="department"
                        item-value="id"
                        label="Для отдела"
                        dense
                        solo
                        hide-details
                        @change="changeDepartment(item)"
                    ></v-select>
                    <!--                    {{initialTasks}}-->
                </template>
                <template v-slot:expanded-item="{ headers, item }">
                    <td :colspan="headers.length" v-html="item.text">
                    </td>
                </template>
            </v-data-table>
        </div>


        <h3>Задачи участников</h3>
        <div
            v-if="meeting.users"
            class="mb-4"
        >

            <v-chip
                v-for="user in meeting.users"
                :key="user.id"
                @click="showUserTasks(user.id)"
            >
                <v-avatar left>
                    <v-img :src="user.img"></v-img>
                </v-avatar>
                {{user.lastname}}
            </v-chip>
        </div>
        <template>
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
                            :headers="headersUser"
                            :items="status.status_tasks"
                            :single-expand="singleExpandUser"
                            :expanded.sync="expandedUser"
                            :search="searchUser"
                            :items-per-page="30"
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
                            <template v-slot:item.actions="{ item }">
                                <v-btn
                                    x-small
                                    @click="openTaskDialog(item.id)"
                                >
                                    <v-icon
                                        x-small
                                    >
                                        mdi-eye
                                    </v-icon>
                                </v-btn>
                            </template>
                        </v-data-table>
                    </v-card>
                </v-tab-item>
            </v-tabs-items>
        </template>
        <v-dialog
            v-model="dialogTask"
            fullscreen
            hide-overlay
            transition="dialog-bottom-transition"
            scrollable
        >
            <v-card tile>
                <v-toolbar
                    class="toolbar-header"
                    extension-height="48"
                    flat
                    dark
                    color="primary"
                >
                    <v-btn
                        icon
                        dark
                        @click="closeTaskDialog()"
                    >
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-spacer></v-spacer>
                </v-toolbar>
                <task
                    v-if="taskId"
                    :taskId="taskId"
                    class="pa-4"
                ></task>
                <div style="flex: 1 1 auto;"></div>
            </v-card>
        </v-dialog>


        <h3>Распределённое на этом собрании</h3>
        <v-simple-table
            class="mb-6 bg"
        >
            <template v-slot:default>
                <thead>
                <tr>
                    <th class="text-left">
                        Заголовок
                    </th>
                    <th class="text-left">
                        Постановщик
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="item in distributionTasksTrue"
                    :key="item.id"
                >
                    <td>
                        <router-link

                            :to="'/tasks/task/' + item.id"
                        >
                            {{ item.title }}
                        </router-link>
                    </td>
                    <td>
                        <v-chip
                            v-if="item.initiator"
                        >
                            <v-avatar left>
                                <v-img :src="item.initiator.img"></v-img>
                            </v-avatar>
                            {{item.initiator.lastname}}
                        </v-chip>
                    </td>
                </tr>
                </tbody>
            </template>
        </v-simple-table>

        <update-task
            :open="dialog"
            @close="closeDialog()"
            :updateId = updateId
            :isDistribution = isDistribution
            :meetingId = meetingId
        ></update-task>


        <v-btn
            v-if="!is_completed && isPermission('distributed-tasks')"
            color="error"
            block
            @click="completedMeeting()"
        >
            Завершить собрание
        </v-btn>


        <v-dialog
            v-model="dialogDelete"
            max-width="290"
        >
            <v-card>
                <v-card-title class="text-h5">
                    Вы действительно хотите удалить задание?
                </v-card-title>

                <v-card-actions>

                    <v-btn
                        color="green darken-1"
                        text
                        @click="dialogDelete = false"
                    >
                        Нет
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="green darken-1"
                        text
                        @click="deleteTask()"
                    >
                        Да
                    </v-btn>

                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    export default {
        name: "meeting",
        data(){
            return{
                data: [],
                user: [],
                statuses: [],
                tab: null,
                tableLoading: false,
                updateId: null,
                isDistribution: false,
                taskId: null,

                deleteId: null,
                departments: {},
                dialogDelete: false,
                dialog: false,
                dialogTask: false,
                loaded: false,
                getComponent: 'update-task',
                meeting: {},
                is_completed: false,
                // tasks: {},
                initialTasks: {},
                distributionTasks: {},
                distributionTasksTrue: {},
                search: '',
                singleExpand: true,
                expanded: [],
                search2: '',
                singleExpand2: true,
                expanded2: [],
                meetingId: Number(this.$route.params.meetingId),
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
                        text: 'Действия',
                        value: 'actions',
                        sortable: false
                    },
                    {
                        text: '',
                        value: 'department_id',
                        sortable: false
                    },
                    {
                        text: '',
                        value: 'data-table-expand'
                    },
                ],

                searchUser: '',
                headersUser: [
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
                    {
                        text: 'Действия',
                        value: 'actions',
                        sortable: false
                    },
                ],
                expandedUser: [],
                singleExpandUser: true,
            }
        },
        watch:{
            '$route.path' (to, from){
                this.meetingId = Number(this.$route.params.meetingId);
                this.init();
            },
            deleteId: function (q){
                if (this.deleteId){
                    this.dialogDelete = true;
                }
            },
        },
        computed:{
            permissions: function (){
                return this.$store.getters['user/permissions'];
            }
        },
        created() {
            this.init();
        },
        methods: {
            isPermission(per){
                let permissions = this.permissions;
                if(String(permissions).indexOf(per) >= 0){
                    return true;
                }
                return false;
            },
            init(){
                axios.post(`/api/departments`).then(response => {
                    this.departments = response.data;
                });

                axios.post(`/api/meetings/${this.meetingId}`).then(response => {
                    this.meeting = response.data.meeting;
                    this.initialTasks = response.data.tasksInitial;
                    this.distributionTasks = response.data.tasksDistribution;
                    this.distributionTasksTrue = response.data.distributionTasksTrue;
                    if (this.meeting.completed_at){
                        this.is_completed = true;
                    }
                    this.loaded = true;
                });
            },
            deleteTask(){
                axios.post(`/api/tasks/delete/${this.deleteId}`).then(response => {
                    this.init();
                    this.deleteId = null;
                    this.dialogDelete = false;
                });
            },
            closeDialog(data){
                if(!data){
                    this.dialog = false;
                    this.updateId = null;
                    this.isDistribution = false;
                    this.init();
                }
            },
            distributionTask(id){
                this.updateId = id;
                this.isDistribution = true;
                this.dialog = true;
            },
            newTask(){
                this.updateId = null;
                this.isDistribution = true;
                this.dialog = true;
            },
            completedMeeting(){
                axios.post(`/api/meetings/completed/${this.meetingId}`).then(response => {
                    this.init();
                });
            },
            changeDepartment(item){
                if((item.initial_department === item.distribution_department) && (item.initial_department > 0) && (item.distribution_department > 0)){
                    item.distribution_department = null;
                }
                axios.post(`/api/tasks/distribution-department`, item).then(response => {
                    this.init();
                });
            },
            showUserTasks(id){
                axios.post(`/api/team/users/${id}`).then(response => {
                    this.data = response.data;
                    this.user = this.data.user;
                    this.statuses = this.data.status;
                });
            },
            openTaskDialog(id){
                this.taskId = id;
                this.dialogTask = true;
            },
            closeTaskDialog(id){
                this.taskId = null;
                this.dialogTask = false;
            }
        }
    }
</script>

<style>
    .toolbar-header{
        flex: 0;
    }
</style>
