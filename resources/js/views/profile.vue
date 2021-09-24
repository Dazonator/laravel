<template>
    <main class="col py-4">
        <div class="row">
            <div class="col">
                <h2 class="mb-4">{{user.name}} {{user.lastname}}</h2>
                <div class="bg pa-4" v-if="loaded">
                    <div class="row profile">
                        <form class="col-3 text-center profile__img">
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
                        </form>
                        <div class="col-9 profile__information">
                            <div class="row mb-6">
                                <div class="col-12">
                                    <div class="text-sm-body-2 font-weight-bold">
                                        Имя
                                    </div>
                                    <div class="value py-2 px-4">
                                        {{user.name}} {{user.lastname}}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-sm-body-2 font-weight-bold">
                                        Должность
                                    </div>
                                    <div class="value py-2 px-4">
                                        {{user.position}}
                                    </div>

                                </div>
                                <div class="col-6">
                                    <div class="text-sm-body-2 font-weight-bold">
                                        Отдел
                                    </div>
                                    <div class="value py-2 px-4">

                                        {{user.department.department}}
                                    </div>

                                </div>
                                <div class="col-6">
                                    <div class="text-sm-body-2 font-weight-bold">
                                        Телефон
                                    </div>
                                    <div class="value py-2 px-4">
                                        {{user.phone}}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-sm-body-2 font-weight-bold">
                                        E-mail
                                    </div>
                                    <div class="value py-2 px-4">
                                        {{user.email}}
                                    </div>

                                </div>
                            </div>

                            <div class="change-password" v-if="isUser">

                                <div class="change-password__title mb-4 ">
                                    <span v-on:click="changePasswordShow=!changePasswordShow">Сменить пароль</span>
                                </div>

                                <v-form
                                    v-show="changePasswordShow"
                                    @submit.prevent="changePassword"
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
                                                required
                                                type="password"
                                            ></v-text-field>
                                        </v-col>

                                        <v-col
                                            cols="12"
                                            sm="6"
                                        >
                                            <v-text-field
                                                v-model="changePasswordFields.new_password"
                                                label="Новый пароль"
                                                required
                                                type="password"
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
                        </div>


                    </div>
                </div>
            </div>
            <add-task></add-task>
        </div>
    </main>
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
                    console.log(response.data);
                    this.user = response.data.user;
                    this.isUser = response.data.is_user;
                    this.loaded = true;
                });
            } else {
                axios.get(`/api/profile/${this.id}`).then(response => {
                    console.log(response.data);
                    this.user = response.data.user;
                    this.isUser = response.data.is_user;
                    this.loaded = true;
                });
            }
        },
        changePassword(){
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
        },
        changePhoto(e){

            this.file = e.target.files[0];
            let formData = new FormData();
            formData.append('file', this.file);
            console.log(formData);
            axios.post('/api/profile/change-photo', formData).then(response => {
                console.log(response.data);
                this.init();
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
