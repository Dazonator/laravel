<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="600px"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    class="mx-2 add-event-btn"
                    fab
                    dark
                    large
                    color="indigo"
                    v-bind="attrs"
                    v-on="on"
                >
                    <v-icon dark>
                        mdi-plus
                    </v-icon>
                </v-btn>
            </template>
            <v-card>

                <form @submit.prevent="submit">
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
                                <v-col cols="12">
                                    <v-color-picker
                                        v-model="color"
                                        max-width="100%"
                                        class="ma-2"
                                        :swatches="swatches"
                                        show-swatches
                                        hide-mode-switch
                                        hide-canvas
                                        hide-sliders
                                        hide-controls
                                        flat
                                    ></v-color-picker>
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
                                                label="Дата начала"
                                                prepend-icon="mdi-calendar"
                                                readonly
                                                v-bind="attrs"
                                                v-on="on"
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
                                                prepend-icon="mdi-clock-time-four-outline"
                                                readonly
                                                v-bind="attrs"
                                                v-on="on"
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
                                                prepend-icon="mdi-clock-time-four-outline"
                                                readonly
                                                v-bind="attrs"
                                                v-on="on"
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
                        <span>{{fields}}</span>
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
                        >
                            Добавить
                        </v-btn>
                    </v-card-actions>
                </form>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
export default {
    name: "add-event",
    watch: {
        color: function (q){
          this.fields.color = q.hex;
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
            endDate: '',
            color: ''
        },

        date: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
        date2: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
        menu: false,
        menu2: false,
        timeMenu: false,
        timeMenu2: false,
        time: null,
        time2: null,

        color: {},
        swatches: [
            ['#FF0000', '#AA0000', '#550000'],
            ['#AAAA00', '#827717', '#555500'],
            ['#43A047', '#2E7D32', '#1B5E20'],
            ['#F57C00', '#FF6D00', '#E65100'],
            ['#8D6E63', '#4E342E', '#3E2723'],
            ['#455A64', '#37474F', '#263238'],
            ['#7E57C2', '#512DA8', '#311B92'],
            ['#D500F9', '#7B1FA2', '#4A148C'],
            ['#0000FF', '#0000AA', '#000055'],
        ],
    }),
    methods: {
        submit(){

            axios.post('/api/team/adduser', this.fields).then(response => {
                alert('Событие добавлено!!!');
                this.dialog = false;
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
            });
        }
    }

}
</script>

<style scoped>


.add-event-btn{
    position: fixed;
    top: 10px;
    right: 10px;
    z-index: 10;
}
</style>
