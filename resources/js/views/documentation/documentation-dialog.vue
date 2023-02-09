<template>
    <v-dialog
        v-model="isOpen"
        fullscreen
        hide-overlay
        transition="dialog-bottom-transition"
        scrollable
        persistent
        :retain-focus="false"
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
                    @click="isOpen = false"
                >
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-toolbar-title>
                    {{updateId ? 'Редактировать уведомление' : 'Создать уведомление'}}
                </v-toolbar-title>
                <v-spacer></v-spacer>
            </v-toolbar>
            <v-card-text
                class="py-6"
            >

                <v-form
                    v-if="loaded"
                    ref="form"
                    @submit.prevent="submit"
                    v-model="valid"
                    lazy-validation
                >
                    <v-row>
                        <v-col
                            cols="12"
                        >
                            <v-text-field
                                label="Заголовок"
                                name="title"
                                v-model="fields.title"
                                :rules="[v => !!v || 'Обязательное поле']"
                                required
                            ></v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                        >
                            <v-text-field
                                label="Mdi иконка"
                                name="icon"
                                v-model="fields.icon"
                                placeholder="mdi-information"
                            ></v-text-field>
                            <a href="https://pictogrammers.com/library/mdi/" target="_blank">Ссылка где смотреть иконки. Должно быть mdi-название</a>
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
                            cols="12"
                        >
                            <v-btn
                                type="submit"
                                color="primary"
                                large
                                block
                            >
                                {{updateId ? 'Редактировать' : 'Создать'}}
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        name: "documentation-dialog",
        props: ['updateId', 'open'],
        data() {
            return{
                fields: {
                    title: '',
                    icon: '',
                    text: '',
                },
                isOpen: this.open,
                valid: false,
                loaded: false,
            }
        },
        watch: {
            open: function(q){
                if (q){
                    this.isOpen = q;
                } else {
                    this.fields = {};
                }

            },
            isOpen: function(q){
                if (!q){
                    this.fields = {};
                    this.$emit('close', false);
                }

            },
            updateId: function (q){
                if(this.updateId){
                    axios.post(`/api/documentation/getById/${this.updateId}`).then(response => {
                        this.fields = response.data;
                    });
                }
            },
        },
        created() {
            this.init();
        },
        methods: {
            init(){
                this.loaded = true;
            },
            ckeditorText(data){
                this.fields.text = data;
            },
            submit() {
                if (!this.updateId){
                    this.axios.post(`/api/documentation/create`, this.fields).then(response => {
                        this.isOpen = false;
                    }).catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors || {};
                        }
                    });
                } else {
                    this.axios.post(`/api/documentation/update`, this.fields).then(response => {
                        this.isOpen = false;
                    }).catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors || {};
                        }
                    });
                }
            },
        }
    }
</script>

<style scoped>

</style>
