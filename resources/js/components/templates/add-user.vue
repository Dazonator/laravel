
<template>
    <div>
        <v-dialog
            v-model="dialog"
            max-width="800px"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    color="primary"
                    large
                    v-bind="attrs"
                    v-on="on"
                >
                    {{updateId ? 'Изменить пользователя' : 'Добавить пользователя'}}
                </v-btn>
            </template>

            <v-card>
                <form @submit.prevent="submit">
                    <v-card-title>
                        <span class="text-h5">Добавить пользователя</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col
                                    cols="12"
                                    sm="6"
                                    md="4"
                                >
                                    {{errors.login}}
                                    <v-text-field
                                        label="Логин*"
                                        required
                                        v-model="fields.login"
                                    ></v-text-field>
                                </v-col>
                                <v-col
                                    cols="12"
                                    sm="6"
                                    md="4"
                                >
                                    {{errors.name}}
                                    <v-text-field
                                        label="Имя*"
                                        required
                                        v-model="fields.name"
                                    ></v-text-field>
                                </v-col>
                                <v-col
                                    cols="12"
                                    sm="6"
                                    md="4"
                                >
                                    {{errors.lastname}}
                                    <v-text-field
                                        label="Фамилия*"
                                        required
                                        v-model="fields.lastname"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    {{errors.email}}
                                    <v-text-field
                                        label="Email*"
                                        type="email"
                                        required
                                        v-model="fields.email"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    {{errors.password}}
                                    <v-text-field
                                        label="Password*"
                                        type="password"
                                        v-model="fields.password"
                                    ></v-text-field>
                                </v-col>
                                <v-col
                                    cols="12"
                                >
                                    <v-select
                                        v-model="fields.roles"
                                        :items="roles"
                                        item-text="name"
                                        item-value="id"
                                        :menu-props="{ maxHeight: '400' }"
                                        label="Роли"
                                        multiple
                                    ></v-select>
                                </v-col>
                                <v-col
                                    cols="12"
                                    sm="6"
                                >
                                    {{errors.department_id}}
                                    <v-select
                                        :items="departments"
                                        item-text="department"
                                        item-value="id"
                                        label="Отдел*"
                                        required
                                        v-model="fields.department_id"
                                    ></v-select>
                                </v-col>
                                <v-col
                                    cols="12"
                                    sm="6"
                                >
                                    {{errors.position}}
                                    <v-text-field
                                        label="Должность*"
                                        type="text"
                                        required
                                        v-model="fields.position"
                                    ></v-text-field>
                                </v-col>

                                <v-col
                                    cols="12"
                                    sm="6"
                                >
                                    {{errors.img}}
                                    <v-file-input
                                        label="Фотография"
                                        accept="image/*"
                                        prepend-icon="mdi-camera"
                                        v-on:change="imgUpload($event)"
                                    ></v-file-input>
                                </v-col>

                                <v-col
                                    cols="12"
                                    sm="6"
                                >

                                    <v-text-field
                                        label="Телефон*"
                                        type="text"
                                        required
                                        v-model="fields.phone"
                                    ></v-text-field>
                                </v-col>
                                <v-col
                                    cols="12"
                                >
                                    {{fields}}
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="yellow darken-3"
                            dark
                            @click="dialog = false"
                            min-width="200px"
                        >
                            Отмена
                        </v-btn>
                        <v-btn
                            type="submit"
                            color="primary"
                            min-width="200px"
                        >
                            {{updateId ? 'Изменить' : 'Добавить'}}
                        </v-btn>
                    </v-card-actions>

                </form>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
    data: () => ({
        fields: {},
        departments: [],
        roles: [],
        dialog: false,
        errors: {},
    }),
    props: ['updateId'],
    computed: {

    },
    created() {
        axios.post('/api/add-user-parameters').then(response => {
            this.departments = response.data.departments;
            this.roles = response.data.roles;
            console.log(response.data);
        });
    },
    watch:{
        dialog: function (q){
            if(!this.dialog){
                this.fields = {};
                this.$emit('close', true);
            }
        },
        updateId: function (q) {
            if (this.updateId){
                axios.post(`/api/settings/users/getUser/${this.updateId}`).then(response => {
                    console.log(response.data);
                    this.dialog = true;
                    this.fields = response.data;

                    let roles = [];
                    for (let role of this.fields.roles) {
                        roles.push(role.id);
                    }
                    this.fields.roles = [];
                    this.fields.roles = roles;
                    delete this.fields.img;
                    console.log(response.data);
                });
            }
        }
    },
    methods: {
        imgUpload(file){
            if(file){
                console.log(file);
                let formData = new FormData();
                formData.append('img', file);
                axios.post('/api/settings/users/addUser/uploadPhoto', formData).then(response => {
                    this.$set(this.fields, 'img', response.data);
                }).catch(error => {
                    if (error.response.status == 422) {
                        if(error.response.data.errors) {
                            alert('Не удалось загрузить изображение!');
                        }
                    }
                });
            }
        },
        submit(){
            if(this.updateId){
                axios.post('/api/settings/users/update', this.fields).then(response => {
                    this.dialog = false;
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            } else {
                axios.post('/api/settings/users/addUser', this.fields).then(response => {
                    this.dialog = false;
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
