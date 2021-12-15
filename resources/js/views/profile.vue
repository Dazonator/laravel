<template>
    <div v-if="loaded">
        <h2 class="mb-4">{{user.name}} {{user.lastname}}</h2>
        <div class="bg pa-4">
            <div class="row profile">
                <v-col
                    cols="12"
                    sm="3"
                    class="text-center profile__img"
                >
                    <label>
                        <v-img
                            :src=user.img
                            alt=""
                            :aspect-ratio="1/1"
                        >
                        </v-img>
                        <span href="" v-if="isUser">Изменить фото</span>
                        <input type="file" id="file" ref="file" v-on:change="changePhoto">
                    </label>
                </v-col>
                <v-col
                    cols="12"
                    sm="9"
                    class="profile__information"
                >
                    <div class="row mb-6">
                        <v-col
                            cols="12"
                        >
                            <div class="text-sm-body-2 font-weight-bold">
                                Имя
                            </div>
                            <div class="value py-2 px-4">
                                {{user.name}} {{user.lastname}}
                            </div>
                        </v-col>
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <div class="text-sm-body-2 font-weight-bold">
                                Должность
                            </div>
                            <div class="value py-2 px-4">
                                {{user.position}}
                            </div>

                        </v-col>
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <div class="text-sm-body-2 font-weight-bold">
                                Отдел
                            </div>
                            <div class="value py-2 px-4">

                                {{user.department.department}}
                            </div>

                        </v-col>
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <div class="text-sm-body-2 font-weight-bold">
                                Телефон
                            </div>
                            <div class="value py-2 px-4">
                                {{user.phone}}
                            </div>
                        </v-col>
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <div class="text-sm-body-2 font-weight-bold">
                                E-mail
                            </div>
                            <div class="value py-2 px-4">
                                {{user.email}}
                            </div>

                        </v-col>
                    </div>

                    <div class="change-password" v-if="isUser">

                        <div class="change-password__title mb-4 ">
                            <span v-on:click="changePasswordShow=!changePasswordShow">Сменить пароль</span>
                        </div>

                        <v-form
                            v-show="changePasswordShow"
                            @submit.prevent="changePassword"
                            v-model="valid"
                            ref="form"
                            lazy-validation
                        >
                            {{errors}}
                            <v-row>
                                <v-col
                                    cols="12"
                                    sm="6"
                                >
                                    <v-text-field
                                        v-model="changePasswordFields.old_password"
                                        label="Старый пароль"
                                        type="password"
                                        :rules="[v => !!v || 'Обязательное поле']"
                                        required
                                    ></v-text-field>
                                </v-col>

                                <v-col
                                    cols="12"
                                    sm="6"
                                >
                                    <v-text-field
                                        v-model="changePasswordFields.new_password"
                                        label="Новый пароль"
                                        type="password"
                                        :rules="[v => !!v || 'Обязательное поле']"
                                        required
                                    ></v-text-field>
                                </v-col>

                                <v-col
                                    cols="12"
                                    sm="6"
                                >

                                    <v-btn
                                        type="submit"
                                        color="primary"
                                        block
                                    >
                                        Изменить пароль
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-form>
                    </div>
                </v-col>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return{
            file: '',
            errors: {},
            user: [],
            isUser: false,
            changePasswordShow: false,
            changePasswordFields: {},
            loaded: false,
            id: Number(this.$route.params.id),

            valid: false,
        }
    },
    watch: {
        $route (to, from){
            this.id = Number(this.$route.params.id)
            this.init();
        }
    },
    created(){
        this.init();
    },
    methods:{
        init(){
            if(!this.id){
                axios.get(`/api/profile`).then(response => {
                    // console.log(response.data);
                    this.user = response.data.user;
                    this.isUser = response.data.is_user;
                    this.loaded = true;
                });
                // console.log(
                // this.$store.getters['user/authUser']);
                // this.user = this.$store.getters['user/authUser'];
                // console.log(this.user);
                // this.isUser = true;
                // this.loaded = true;
            } else {
                axios.get(`/api/profile/${this.id}`).then(response => {
                    // console.log(response.data);
                    this.user = response.data.user;
                    this.isUser = false;
                    this.loaded = true;
                });


            }
        },
        changePassword(){
            if(this.$refs.form.validate()){
                axios.post('/api/profile/change-password', this.changePasswordFields).then(response => {
                    alert('Пароль изменен!!!');
                    this.changePasswordFields = {};
                    this.changePasswordShow = false;
                    console.log(response);
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                        console.log(this.errors);
                    }
                });
            }
        },
        changePhoto(e){

            this.file = e.target.files[0];
            let formData = new FormData();
            formData.append('file', this.file);
            axios.post('/api/profile/change-photo', formData).then(response => {
                this.init();
                this.$store.dispatch('user/getAppParameters');
            }).catch(error => {
                if (error.response.status == 422) {
                    if(error.response.data.errors) {
                        alert('Не удалось загрузить изображение!');
                    }
                }
            });
        }
    }

}
</script>

<style>
.profile__img {
}
.profile__img label{
    display: block;
    position: relative;
    color: #0000FF;
    text-decoration: underline;
    cursor: pointer;
}
.profile__img label:hover{
    color: cornflowerblue;
}
.profile__img input[type=file]{
    outline: 0;
    opacity: 0;
    pointer-events: none;
    user-select: none;
}
</style>
