<template>
    <div class="row">
        <v-col
            cols="12"
            md="3"
        >
            <div class="">
                <div class="bg py-4 px-4 mb-2">
                    <v-list-item-group
                        color="primary"
                    >
                        <v-list>
                            <h5 class="team-nav__title">Мои задачи</h5>
                            <v-list-item
                                v-for="(status, i) in statuses"
                                :key="i"
                                :to="'/tasks/status/' + status.id"
                            >
                                {{status.status}}
                            </v-list-item>

                            <v-divider></v-divider>

                            <h5 class="team-nav__title">Созданные задачи</h5>
                            <v-list-item
                                :to="'/tasks/initiator'"
                            >
                                Созданные задачи
                            </v-list-item>

                            <v-divider></v-divider>

                            <h5 class="team-nav__title">Тестирование</h5>
                            <v-list-item
                                :to="'/tasks/tests'"
                            >
                                Задачи на тест
                            </v-list-item>

                            <v-divider></v-divider>

                            <h5 class="team-nav__title">Задачи отдела</h5>
                            <v-list-item
                                :to="'/tasks/department/' + department.id"
                            >
                                {{department.department}}
                            </v-list-item>
                        </v-list>
                    </v-list-item-group>
                </div>
                <v-row
                    class="mb-2"
                >
                    <v-col
                        v-if="isPermission('create-tasks-for-department')"
                        cols="12"
                        class="pb-0"
                    >
                        <v-btn
                            color="primary"
                            @click="newDepartmentTask()"
                            block
                        >
                            Задача в собрание
                        </v-btn>
                    </v-col>
                    <v-col
                        cols="12"
                        class="pb-0 or"
                        v-if="isPermission('create-tasks')"
                    >
                        <v-btn
                            color="primary"
                            @click="newTask()"
                            block
                        >
                            Создать задачу
                        </v-btn>
                    </v-col>
                </v-row>
            </div>
        </v-col>
        <v-col
            cols="12"
            md="9"
        >
            <component
                :is="getComponent"
                :id="postId"
                :title="childrenTitle"
            ></component>
        </v-col>
        <update-task
            :open="dialog"
            @close="closeDialog()"
            :forDistribution="forDistribution"
            :newTask="isNewTask"
        ></update-task>
    </div>
</template>

<script>
export default {
    data() {
        return {
            dialog: false,
            whatCreate: '',
            loaded: false,
            addTask: false,
            statuses: {},
            department: [],
            postId: null,
            getComponent: '',
            statusId: Number(this.$route.params.statusId),
            departmentId: Number(this.$route.params.departmentId),
            childrenTitle: '',
            test: [],
            forDistribution: false,
            isNewTask: false,
        }
    },
    watch:{
        '$route.path' (to, from){
            this.statusId = Number(this.$route.params.statusId);
            this.departmentId = Number(this.$route.params.departmentId);
            this.init();
        }
    },
    computed:{
        permissions: function (){
            return this.$store.getters['user/permissions'];
        }
    },
    created(){
        axios.post('/api/tasks/statuses-departments').then(response => {
            this.statuses = response.data.statuses;
            this.department = response.data.department;
            this.init();
            this.loaded = true;
        });
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
            if(this.statusId){
                this.statusTasks(this.statusId);
            }
            if(this.departmentId){
                this.departmentTasks(this.departmentId);
            }
            if(this.$route.name === 'tasks-initiator'){
                this.tasksInitiator();
            }
            if(this.$route.name === 'tasks-tests'){
                this.tasksTests();
            }
        },
        closeDialog(data){
            if(!data){
                this.dialog = false;
                this.forDistribution = false;
                this.isNewTask = false;
                this.init();
            }
        },
        statusTasks(i){
            this.postId = i;
            // this.childrenTitle = 'Мои текущие задачи';
            this.childrenTitle = this.statuses[i-1].status;
            this.getComponent = 'user-tasks';
        },
        tasksInitiator(){
            this.getComponent = 'tasks-initiator';
            this.childrenTitle = 'Созданные задачи';
        },
        tasksTests(){
            this.getComponent = 'tasks-tests';
            this.childrenTitle = 'Задачи на тест';
        },
        departmentTasks(){
            this.postId = this.department.id;
            this.childrenTitle = this.department.department;
            // this.childrenTitle = 'Задачи отделов';
            this.getComponent = 'department-tasks';
        },
        // newTask(){
        //     if (!this.addTask){
        //         this.addTask = true;
        //         this.whatCreate = 'task';
        //     } else if(this.addTask && this.whatCreate !== 'task') {
        //         this.whatCreate = 'task';
        //     } else {
        //         this.whatCreate = '';
        //         this.addTask = false;
        //     }
        //
        // },
        // newDistributionDepartment(){
        //     if (!this.addTask){
        //         this.addTask = true;
        //         this.whatCreate = 'distribution';
        //     } else if(this.addTask && this.whatCreate !== 'distribution') {
        //         this.whatCreate = 'distribution';
        //     } else {
        //         this.whatCreate = '';
        //         this.addTask = false;
        //     }
        // }
        newDepartmentTask(){
            this.forDistribution = true;
            this.dialog = true;
        },
        newTask(){
            this.forDistribution = false;
            this.dialog = true;
            this.isNewTask = true;
        }

    },
}
</script>
