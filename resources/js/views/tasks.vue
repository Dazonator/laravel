<template>
    <div class="row">
        <v-col
            cols="12"
            lg="4"
            class="tasks-nav"
        >
            <div class="">
                <div class="bg py-6 px-10 mb-2">
                    <v-list-item-group
                        color="primary"
                    >
                        <v-list>

                            <h5 class="team-nav__title">Мои задачи</h5>
                            <v-list-item
                                v-for="(status, i) in statuses"
                                :key="i"
                            >
                                <router-link
                                    :to="'/tasks/status/' + status.id"
                                >
                                    {{status.status}}
                                </router-link>
                            </v-list-item>
                            <v-divider></v-divider>
                            <h5 class="team-nav__title">Задачи отдела</h5>
                            <v-list-item>
                                <router-link
                                    :to="'/tasks/department/' + department.id"
                                >
                                   {{department.department}}
                                </router-link>
                            </v-list-item>
                        </v-list>
                    </v-list-item-group>
                </div>
                <v-row
                    class="mb-2"
                >
                    <v-col
                        cols="12"
                        sm="6"
                        lg="12"
                        class="pb-0"
                    >
                        <v-btn
                            color="primary"
                            @click="newDepartmentTask()"
                            block
                        >
                            Создать задачу для отдела
                        </v-btn>
                    </v-col>
                    <v-col
                        cols="12"
                        sm="6"
                        lg="12"
                        class="pb-0 or"
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
            lg="8"
        >
            <component
                :is="getComponent"
                :id="postId"
                :title="childrenTitle"
            ></component>
        </v-col>

<!--            <add-task-->
<!--                v-if="addTask"-->
<!--                :whatCreate="whatCreate"-->
<!--            ></add-task>-->

        <update-task
            :open="dialog"
            @close="closeDialog()"
            :forDistribution="forDistribution"
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
        }
    },
    watch:{
        '$route.path' (to, from){
            this.statusId = Number(this.$route.params.statusId);
            this.departmentId = Number(this.$route.params.departmentId);
            this.init();
        }
    },
    created(){
        // axios.get('/api/tasks/statuses').then(response => {
        //     this.statuses = response.data;
        //     // console.log(response.data);
        //     console.log(this.statuses);
        //
        // });
        // axios.get('/api/tasks/departments').then(response => {
        //     this.departments = response.data;
        //     // console.log(this.departments);
        // });
        axios.get('/api/tasks/statuses-departments').then(response => {
            this.statuses = response.data.statuses;
            this.department = response.data.department;
            // console.log(this.departments);
            this.init();
            this.loaded = true;
        });


        // this.init();
    },
    methods: {
        init(){
            if(this.statusId){
                this.statusTasks(this.statusId);
            }
            if(this.departmentId){
                this.departmentTasks(this.departmentId);
            }
        },
        closeDialog(data){
            if(!data){
                this.dialog = false;
                this.forDistribution = false;
                this.init();
            }
        },
        statusTasks(i){
            this.postId = i;
            // this.childrenTitle = 'Мои текущие задачи';
            this.childrenTitle = this.statuses[i-1].status;
            this.getComponent = 'user-tasks';
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
        }

    },
}
</script>
