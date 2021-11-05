<template>

    <main class="col py-4" v-if="loaded">
        <h1 class="mb-6">Собрание №{{meeting.number}}</h1>


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
                    {{item.initiator.name}}
                    {{item.initiator.lastname}}

                </v-chip>
            </template>
            <template v-slot:item.actions="{ item }">
                <v-icon
                    small
                    class="mr-2"
                    @click="distributionTask(item.id)"
                >
                    mdi-pencil
                </v-icon>
                <v-icon
                    small
                    @click="deleteItem(item)"
                >
                    mdi-delete
                </v-icon>
            </template>
            <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length" v-html="item.text">
                </td>
            </template>
        </v-data-table>


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
                    {{item.initiator.name}}
                    {{item.initiator.lastname}}

                </v-chip>
            </template>
            <template v-slot:item.actions="{ item }">
                <v-icon
                    small
                    class="mr-2"
                    @click="dialog = true"
                >
                    mdi-pencil
                </v-icon>
                <v-icon
                    small
                    @click="deleteItem(item)"
                >
                    mdi-delete
                </v-icon>
            </template>
            <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length" v-html="item.text">
                </td>
            </template>
        </v-data-table>


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
                    <td>{{ item.title }}</td>
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

    </main>
</template>

<script>
    export default {
        name: "meeting",
        data(){
            return{
                updateId: null,
                isDistribution: false,

                dialog: false,
                loaded: false,
                getComponent: 'update-task',
                meeting: {},
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
                    { text: 'Actions', value: 'actions', sortable: false },
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
            }
        },
        created() {
            this.init();
        },
        methods: {
            init(){
                axios.post(`/api/meetings/${this.meetingId}`).then(response => {
                    console.log(response.data);
                    this.meeting = response.data.meeting;
                    this.initialTasks = response.data.tasksInitial;
                    this.distributionTasks = response.data.tasksDistribution;
                    this.distributionTasksTrue = response.data.distributionTasksTrue;
                    this.loaded = true;
                });
            },
            closeDialog(data){
                if(!data){
                    this.dialog = false;
                    this.updateId = null;
                    this.isDistribution = false;
                }
            },
            distributionTask(id){
                this.updateId = id;
                this.isDistribution = true;
                this.dialog = true;
            }

        }
    }
</script>

<style scoped>

</style>
