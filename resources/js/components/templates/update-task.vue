<template>
    <v-dialog
        v-model="open"
        fullscreen
        hide-overlay
        transition="dialog-bottom-transition"
        scrollable
    >
        <v-card
            tile
        >
            <v-toolbar
                class="toolbar-header"
                flat
                dark
                extension-height="48"
                color="primary"
            >
                <v-btn
                    icon
                    dark
                    @click="close"
                >
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-toolbar-title>Изменить задание</v-toolbar-title>
                <v-spacer></v-spacer>
<!--                <v-toolbar-items>-->
<!--                    <v-btn-->
<!--                        dark-->
<!--                        text-->
<!--                        @click="$emit('close', false)"-->
<!--                    >-->
<!--                        Сохранить-->
<!--                    </v-btn>-->
<!--                </v-toolbar-items>-->
            </v-toolbar>
            <v-card-text
                class="py-6"
            >

                <form
                    v-if="loaded"
                    @submit.prevent="submit"
                >
                    <v-row>
                        <v-col
                            cols="12"
                        >
                            <v-text-field
                                label="Название"
                                name="title"
                                v-model="fields.title"
                            ></v-text-field>
                        </v-col>

                        <v-col
                            cols="12"
                        >
                            <ck-editor
                                @input="ckeditorText"
                                :defaultText = fields.text
                            ></ck-editor>
                        </v-col>
                        <v-col
                            cols="6"
                        >
                            <v-autocomplete
                                :items="employees"
                                label="Исполнители"
                                item-text="name"
                                item-value="id"
                                multiple
                                name="performers_id"
                                v-model=fields.performers_id
                            >
                                <template v-slot:selection="data">
                                    <v-chip
                                        v-bind="data.attrs"
                                        :input-value="data.selected"
                                        close
                                        @click="data.select"
                                        @click:close="removePerformers(data.item)"
                                    >
                                        <v-avatar left>
                                            <v-img :src="data.item.img"></v-img>
                                        </v-avatar>
                                        {{ data.item.lastname }}
                                    </v-chip>
                                </template>
                                <template v-slot:item="data">
                                    <template v-if="typeof data.item !== 'object'">
                                        <v-list-item-content v-text="data.item"></v-list-item-content>
                                    </template>
                                    <template v-else>
                                        <v-list-item-avatar>
                                            <v-img
                                                :src="data.item.img"
                                                :aspect-ratio="1/1"
                                            >
                                            </v-img>
                                        </v-list-item-avatar>
                                        <v-list-item-content>
                                            <v-list-item-title v-html="data.item.name + ' ' + data.item.lastname"></v-list-item-title>
                                            <v-list-item-subtitle v-html="data.item.position"></v-list-item-subtitle>
                                        </v-list-item-content>
                                    </template>
                                </template>
                            </v-autocomplete>
                        </v-col>
                        <v-col
                            cols="6"
                        >
                            <v-select
                                :items=priorities
                                item-text="priority"
                                item-selection="priority"
                                item-value="id"
                                label="Приоритет"
                                name="priority_id"
                                v-model="fields.priority_id"
                            ></v-select>
                        </v-col>
                        <v-col
                            cols-6
                        >
                            <v-menu
                                v-model="menu"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                transition="scale-transition"
                                offset-y
                                min-width="auto"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="fields.startdate"
                                        label="Дата старта"
                                        append-icon="mdi-calendar"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="fields.startdate"
                                    @input="menu = false"
                                ></v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-col
                            cols-6
                        >
                            <v-menu
                                v-model="menu2"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                transition="scale-transition"
                                offset-y
                                min-width="auto"

                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="fields.deadline"
                                        label="Дедлайн"
                                        append-icon="mdi-calendar"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="fields.deadline"
                                    @input="menu2 = false"
                                ></v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-col
                            cols="12"
                        >
                            <v-btn
                                type="submit"
                                color="primary"
                                large
                                block
                            >
    <!--                                {{isEdit ? 'Сохранить изменения' : ''}}-->
    <!--                                {{isSubtask ? 'Создать подзадачу' : ''}}-->
    <!--                                {{isTask ? 'Создать задачу' : ''}}-->
    <!--                                {{isDistribution ? 'Создать задачу для отдела' : ''}}-->
                            </v-btn>
                        </v-col>

                        <v-col
                            cols="12"
                        >
                            {{fields}}
                        </v-col>
                    </v-row>
                </form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
    var clearFields = {
        text: '',
    };
    export default {
        name: "update-task",
        props: ['open', 'updateId', 'isDistribution', 'meetingId'],
        data () {
            return{
                employees: [],
                priorities: [],
                departments: [],

                loaded: false,
                // isDistribution: false,
                fields: {
                    text: '',
                },
                errors: {},

                date: null,
                date2: null,
                menu: false,
                menu2: false,
            }
        },
        watch: {
            isDistribution: function (q){
                if (this.isDistribution){
                    axios.get(`/api/tasks/task/${this.updateId}`).then(response => {
                        console.log(response.data);
                        this.fields = response.data;
                    });
                }
            },
        },
        methods: {
            init(){
                axios.post('/api/add-task-params').then(response => {
                    this.employees = response.data.employees;
                    this.priorities = response.data.priorities;
                    this.departments = response.data.departments;
                    this.loaded = true;
                });
            },
            close(){
                this.fields = clearFields;
                this.$emit('close', false)
            },
            removePerformers (item) {
                const index = this.fields.performers_id.indexOf(item.id);
                if (index >= 0) {
                    this.fields.performers_id.splice(index, 1)
                }
            },
            ckeditorText(data){
                this.fields.text = data;
            },
            submit() {
                this.errors = {};
                if(this.isDistribution){
                    this.fields.meeting_id = this.meetingId;
                    axios.post(`/api/tasks/update/${this.fields.id}`, this.fields).then(response => {
                        // window.location.href = `/tasks/${this.fields.id}`;
                        alert('задача распределена успешно!!!');
                    }).catch(error => {

                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors || {};
                        }
                    });
                }
            },
        },
        created(){
            this.init();
        },
    }
</script>

<style scoped>
    .toolbar-header{
        flex: 0;
    }
</style>
