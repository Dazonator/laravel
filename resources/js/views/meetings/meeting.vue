<template>

    <div v-if="loaded">
        <h1 class="mb-6">Собрание №{{meeting.number}}</h1>

        <div
            v-if="!is_completed"
        >
            <h3>Распределить</h3>
            <v-data-table
                class="mb-6"
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
                            v-model="toggle_exclusive"
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
                class="mb-6"
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


        <h3>Распределённое на этом собрании</h3>
        <v-simple-table>
            <template v-slot:default>
                <thead>
                <tr>
                    <th class="text-left">
                        Заголовок
                    </th>
                    <th class="text-left">
                        текст
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
                    <td>{{ item.text }}</td>
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
            class="mt-6"
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
                    Вы действительно хотите удалить событие?
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
                updateId: null,
                isDistribution: false,

                deleteId: null,
                departments: {},
                dialogDelete: false,
                dialog: false,
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
                axios.get(`/api/departments`).then(response => {
                    this.departments = response.data;
                });

                axios.post(`/api/meetings/${this.meetingId}`).then(response => {
                    this.meeting = response.data.meeting;
                    this.initialTasks = response.data.tasksInitial;
                    this.distributionTasks = response.data.tasksDistribution;
                    this.distributionTasksTrue = response.data.distributionTasksTrue;
                    // console.log(this.meeting);

                    if (this.meeting.completed_at){
                        this.is_completed = true;
                    }
                    // console.log(this.is_completed);


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
            completedMeeting(){
                axios.post(`/api/meetings/completed/${this.meetingId}`).then(response => {
                    this.init();
                });
            },
            changeDepartment(item){
                if((item.initial_department === item.distribution_department) && (item.initial_department > 0) && (item.distribution_department > 0)){
                    item.distribution_department = null;
                }
                axios.post(`/api/tasks/update/${item.id}`, item).then(response => {
                    this.init();
                });
            }

        }
    }
</script>

<style scoped>

</style>
