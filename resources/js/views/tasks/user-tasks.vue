<template>

    <div class="col">
        <h1>{{title}}</h1>
        <!--            <pre v-html="tasks"></pre>-->
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
                    <v-toolbar
                        flat
                        class="align-items-center"
                    >
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
                    <td
                        :colspan="headers.length"
                        v-html="item.text"
                    >

                    </td>
                </template>
                <template v-slot:item.actions="{ item }">
                    <v-icon
                        small
                        class="mr-2"
                    >
                        mdi-pencil
                    </v-icon>
                    <v-icon
                        small
                    >
                        mdi-delete
                    </v-icon>
                </template>
            </v-data-table>
        </div>
    </div>
</template>

<script>
export default {
    name: 'user-tasks',
    props: {
        id: Number,
        title: String,
    },
    watch: {
        id: function (val){
            this.init();
        }
    },
    data() {
        return {
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
                {
                    text: 'Статус',
                    value: 'status.status',
                    sortable: true,
                },
                { text: '', value: 'actions', sortable: false },
                {
                    text: '',
                    value: 'data-table-expand'
                },
            ],
        }
    },
    created(){
        this.init();
    },
    methods: {
        init(){
            axios.get(`/api/tasks/statuses/${this.id}`).then(response => {
                console.log(response.data);
                this.tasks = response.data;
                this.tableloading = false;
            });
        }
    },
}
</script>

<style scoped>

</style>
