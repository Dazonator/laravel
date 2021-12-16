<template>
    <v-row v-if="loaded">
        <v-col
            cols="12"
            md="3"
        >
            <div class="">
                <div class="bg py-4 px-4 mb-2">
                    <h5 class="team-nav__title">ОДЕЖДА</h5>
                    <v-list-item-group
                        color="primary"
                    >
                        <v-list>
                            <v-list-item
                                v-for="(department, i) in departments"
                                :key="i"main steps
                                :to="'/team/' + (i+1)"
                            >
                                {{ department.department }}
                            </v-list-item>
                        </v-list>
                    </v-list-item-group>
                </div>

<!--                    <add-user></add-user>-->
            </div>
        </v-col>
        <component
            v-bind:is="getComponent"
            v-bind:id="postData"
        ></component>
    </v-row>
</template>

<script>
export default {
    props: [],
    data() {
        return {
            getComponent: '',
            postData: [],
            loaded: false,
            departments: [],
            // users: [],
            activeDepartment: 0,
            userId: Number(this.$route.params.userId),
            department: Number(this.$route.params.department),
        }
    },
    watch:{
        '$route.path' (to, from){
            this.userId = Number(this.$route.params.userId);
            this.department = Number(this.$route.params.department);
            this.init();
        },
    },
    created(){
        axios.post('/api/team/departments').then(response => {
            this.departments = response.data;
        });
        this.init();
    },
    methods: {
        init(){

            this.loaded = true;
            if(this.department){
                this.departmentUsers(this.department);
            }
            if(this.userId){
                this.openUserTasks(this.userId);
            }
        },
        departmentUsers (i){
            this.postData = i;
            this.getComponent = 'department-users';
        },
        openUserTasks(i){
            this.postData = i;
            this.getComponent = 'team-user-tasks';
        },
    },
}
</script>
