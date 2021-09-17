<template>

    <div class="col">
        <div class="bg pa-4" v-if="loaded">
            <v-data-table
                :headers="headers"
                :items="users"
                :search="search"
                :loading="tableLoading"
                loading-text="Загрузка задач..."
                :hide-default-footer="true"
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
                <template #item.img="{ item }">
                    <v-avatar
                        left
                        size="28"
                    >
                        <v-img
                            :src=item.img
                            alt=item.name
                        ></v-img>
                    </v-avatar>
                </template>
                <template #item.name="{ item }">
                    <router-link
                        :to="'/team/users/' + item.id"
                    >
                        {{ item.lastname }} {{ item.name }}
                    </router-link>
<!--                    <v-btn-->
<!--                        plain-->
<!--                        @click="userTasks(item.id)"-->
<!--                    >-->
<!--                        {{ item.lastname }} {{ item.name }}-->
<!--                    </v-btn>-->
                </template>
            </v-data-table>
        </div>
    </div>
</template>

<script>
export default {
    name: "department-users",
    props: ['id'],
    watch: {
        id: function (val){
            this.init();
        }
    },
    data(){
        return{
            tableLoading: true,
            search: '',
            loaded: false,
            users: {},
            headers: [
                {
                    text: '',
                    align: 'start',
                    sortable: true,
                    value: 'img',
                },
                {
                    text: 'Имя',
                    align: 'start',
                    sortable: true,
                    value: 'name',
                },
                {
                    text: 'Должность',
                    align: 'start',
                    sortable: true,
                    value: 'position',
                },
                {
                    text: 'Отдел',
                    align: 'start',
                    sortable: true,
                    value: 'department.department',
                },
            ],
        }

    },
    created() {
        this.init();
    },
    methods: {
        init: function (){
            axios.get(`/api/team/departments/${this.id}`).then(response => {
                this.users = response.data;
                this.loaded = true;
                this.tableLoading = false;
            });
        },
        userTasks: function(value) {
            this.$emit('userTasks', value)
        }
    }

}
</script>

<style scoped>

</style>
