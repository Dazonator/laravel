<template>
    <v-dialog
        v-model="dialog"
        persistent
        max-width="600px"
    >
        <template v-slot:activator="{ on, attrs }">
            <v-btn
                color="primary"
                dark
                v-bind="attrs"
                v-on="on"
            >
                Создать собрание
            </v-btn>
        </template>
        <v-card>

            <v-form
                ref="form"
                @submit.prevent="submit"
                v-model="valid"
                lazy-validation
            >
                <v-card-title>
                    <span class="text-h5">Создать собрание</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col
                                cols="12"
                                sm="6"
                            >
                                <v-text-field
                                    label="Номер собрания"
                                    type="number"
                                    v-model="fields.number"
                                    disabled
                                ></v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                            >
                                <v-select
                                    :items="departments"
                                    item-text="department"
                                    item-selection="department"
                                    item-value="id"
                                    label="Отдел"
                                    v-model="fields.department_id"
                                    @input="getMeetingNumber($event)"
                                ></v-select>
                            </v-col>
                            <v-col
                                cols="12"
                            >
                                <v-autocomplete
                                    :items="users"
                                    chips
                                    label="Дополнительные сотрудники"
                                    item-text="name"
                                    item-value="id"
                                    multiple
                                    name="performers_id"
                                    v-model=fields.additional_staff
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
                            <v-col cols="12">
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
                                            v-model="fields.startDate"
                                            label="Дата начала"
                                            prepend-icon="mdi-calendar"
                                            name="startDate"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                            :rules="[v => !!v || 'Обязательное поле']"
                                            required
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="fields.startDate"
                                        first-day-of-week=1
                                        @input="menu = false"
                                    ></v-date-picker>
                                </v-menu>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                            >
                                <v-menu
                                    ref="timeMenu"
                                    v-model="timeMenu"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    :return-value.sync="time"
                                    prepend-icon=false
                                    transition="scale-transition"
                                    offset-y
                                    max-width="290px"
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="fields.startTime"
                                            label="Время начала"
                                            prepend-icon="mdi-clock-outline"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                            name="startTime"
                                            :rules="[v => !!v || 'Обязательное поле']"
                                            required
                                        ></v-text-field>
                                    </template>
                                    <v-time-picker
                                        v-if="timeMenu"
                                        v-model="fields.startTime"
                                        format="24hr"
                                        full-width
                                        @click:minute="$refs.timeMenu.save(time)"
                                    ></v-time-picker>
                                </v-menu>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                            >
                                <v-menu
                                    ref="timeMenu2"
                                    v-model="timeMenu2"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    :return-value.sync="time2"
                                    transition="Время окончания"
                                    offset-y
                                    max-width="290px"
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="fields.endTime"
                                            label="Время окончания"
                                            prepend-icon="mdi-clock-outline"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                            name="endTime"
                                            :rules="[v => !!v || 'Обязательное поле']"
                                            required
                                        ></v-text-field>
                                    </template>
                                    <v-time-picker
                                        v-if="timeMenu2"
                                        v-model="fields.endTime"
                                        format="24hr"
                                        full-width
                                        @click:minute="$refs.timeMenu2.save(time2)"
                                    ></v-time-picker>
                                </v-menu>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="dialog = false"
                    >
                        Закрыть
                    </v-btn>

                    <v-btn
                        color="blue darken-1"
                        text
                        type="submit"
                    >
                        {{updateId ? 'Изменить' : 'Создать'}}
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        name: "add-meeting",
        props: ['updateId'],
        watch: {
            dialog: function (q){
                if(!this.dialog) {
                    this.fields = {
                        startDate: '',
                        endDate: '',
                        department_id: 0,
                        number: '',
                    };
                    this.clearId();
                }
            },
            updateId: function (q){
                if(this.updateId){
                    this.getById(this.updateId);
                }
            },
        },
        data: () => ({
            dialog: false,
            fields: {
                startDate: '',
                endDate: '',
                department_id: 0,
                number: '',
            },
            departments: {},
            users: {},
            date: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
            menu: false,

            timeMenu: false,
            timeMenu2: false,
            time: null,
            time2: null,

            errors: null,
            valid: false,
        }),
        created() {
            this.init();
        },
        methods: {
            init(){
                axios.post(`/api/departments`).then(response => {
                    this.departments = response.data;
                });
                axios.post(`/api/meetings-users`).then(response => {
                    this.users = response.data;
                });
                this.getMeetingNumber(null);
            },
            removePerformers (item) {
                const index = this.fields.additional_staff.indexOf(item.id);
                if (index >= 0) {
                    this.fields.additional_staff.splice(index, 1)
                }
            },
            getMeetingNumber(event) {
                axios.post(`/api/calendar/meetings/max-number/${event}`).then(response => {
                    if (response.data) {
                        this.fields.number = response.data + 1;
                    } else {
                        this.fields.number = 1;
                    }
                });
            },
            submit(){

                if(this.$refs.form.validate()){
                    if (!this.updateId){
                        this.fields.start = this.fields.startDate + ' ' + this.fields.startTime;
                        this.fields.end = this.fields.startDate + ' ' + this.fields.endTime;
                        axios.post('/api/calendar/meetings/create', this.fields).then(response => {
                            window.location.reload();
                        }).catch(error => {
                            if (error.response.status === 422) {
                                this.errors = error.response.data.errors || {};
                            }
                        });
                    } else {
                        this.fields.start = this.fields.startDate + ' ' + this.fields.startTime;
                        this.fields.end = this.fields.startDate + ' ' + this.fields.endTime;
                        axios.post(`/api/calendar/meetings/update/${this.updateId}`, this.fields).then(response => {
                            window.location.reload();
                        }).catch(error => {
                            if (error.response.status === 422) {
                                this.errors = error.response.data.errors || {};
                            }
                        });
                    }
                }
            },
            getById(id){
                axios.post(`/api/meetings/get-by-id-for-update/${id}`).then(response => {
                    this.fields = response.data;
                    let date1 = this.fields.start.split(" ");
                    let date2 = this.fields.end.split(" ");
                    this.fields.startDate = date1[0];
                    this.fields.startTime = date1[1];
                    this.fields.endTime = date2[1];
                    this.dialog = true;
                });
            },
            clearId(){
                this.$emit('updateId', true);
            }
        }
    }
</script>

<style scoped>

</style>
