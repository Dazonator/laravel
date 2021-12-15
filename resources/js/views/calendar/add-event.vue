<template>
    <v-dialog
        v-model="dialog"
        max-width="600px"
    >
        <template v-slot:activator="{ on, attrs }">
            <v-btn
                color="primary"
                dark
                v-bind="attrs"
                v-on="on"
            >
                Добавить событие
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
                    <span class="text-h5">Добавить событие</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col
                                cols="12"
                            >
                                <v-text-field
                                    v-model="fields.title"
                                    label="Заголовок"
                                    name="title"
                                    :rules="[v => !!v || 'Обязательное поле']"
                                    required
                                ></v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                            >
                                <v-textarea
                                    v-model="fields.text"
                                    name="text"
                                    label="Текст"
                                ></v-textarea>
                            </v-col>
                            <v-col cols="6">
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
                                            label="Дата"
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
                                sm="6"
                            >
                                <v-menu
                                    ref="timeMenu"
                                    v-model="timeMenu"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    :return-value.sync="time"
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
                                cols="6"
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
                                            v-model="fields.endDate"
                                            label="Дата окончания"
                                            prepend-icon="mdi-calendar"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                            name="endDate"
                                            :rules="[v => !!v || 'Обязательное поле']"
                                            required
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="fields.endDate"
                                        first-day-of-week=1
                                        @input="menu2 = false"
                                    ></v-date-picker>
                                </v-menu>

                            </v-col>

                            <v-col
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
                        {{updateId ? 'Изменить' : 'Добавить'}}
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    name: "add-event",
    props: ['updateId'],
    watch: {
        dialog: function (q){
            if(!this.dialog) {
                this.fields = {
                    startDate: '',
                    endDate: ''
                };
                this.clearId();
            }
        },
        updateId: function (q){
            if(this.updateId){
                this.getById(this.updateId);
            }
        },
        'fields.startDate': function (q){
            if(this.fields.startDate){
                if(!this.fields.endDate){
                    this.fields.endDate = this.fields.startDate;
                } else {
                    let d1 = new Date(this.fields.startDate);
                    let d2 = new Date(this.fields.endDate);
                    if(d1 > d2){
                        this.fields.endDate = this.fields.startDate;
                    }
                }
            }
        },
        'fields.endDate': function (q){
            if(this.fields.endDate){
                if(!this.fields.startDate){
                    this.fields.startDate = this.fields.endDate;
                } else {
                    let d1 = new Date(this.fields.startDate);
                    let d2 = new Date(this.fields.endDate);
                    if(d1 > d2){
                        this.fields.startDate = this.fields.endDate;
                    }
                }
            }
        }

    },
    data: () => ({
        dialog: false,
        fields: {
            startDate: '',
            endDate: ''
        },

        date: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
        date2: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
        menu: false,
        menu2: false,
        timeMenu: false,
        timeMenu2: false,
        time: null,
        time2: null,

        menuColor: false,


        valid: false,
    }),
    methods: {
        submit(){
            if(this.$refs.form.validate()){
                if (!this.updateId){
                    this.fields.start = this.fields.startDate + ' ' + this.fields.startTime;
                    this.fields.end = this.fields.endDate + ' ' + this.fields.endTime;
                    axios.post('/api/calendar/create', this.fields).then(response => {
                        window.location.reload();
                    }).catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors || {};
                        }
                    });
                } else {
                    this.fields.start = this.fields.startDate + ' ' + this.fields.startTime;
                    this.fields.end = this.fields.endDate + ' ' + this.fields.endTime;
                    axios.post(`/api/calendar/update/${this.updateId}`, this.fields).then(response => {
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
            axios.post(`/api/calendar/event/${id}`).then(response => {
                this.fields = response.data;
                let date1 = this.fields.start.split(" ");
                let date2 = this.fields.end.split(" ");
                this.fields.startDate = date1[0];
                this.fields.startTime = date1[1];
                this.fields.endDate = date2[0];
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


/*.add-event-btn{*/
/*    position: fixed;*/
/*    top: 54px;*/
/*    right: 10px;*/
/*    z-index: 10;*/
/*}*/
</style>
