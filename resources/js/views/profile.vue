<template>
    <main class="col py-4">
        <div class="row">
            <div class="col">
                <h2 class="mb-4">{{user.name}}</h2>
                <div class="bg pa-4">
                    <div class="row profile">
                        <div class="col-3 text-center profile__img">
                            <v-img
                                src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTPUrabYnhmhW-R0ruChIf03eExU4ETJhJYRA&usqp=CAU"
                                alt=""
                                :aspect-ratio="1/1"
                            >
                            </v-img>
                            <a href="">Изменить фото</a>
                        </div>
                        <div class="col-9 profile__information">
                            <div class="row mb-6">
                                <div class="col-12">
                                    <div class="text-sm-body-2 font-weight-bold">
                                        Имя
                                    </div>
                                    <div class="value py-2 px-4">
                                        {{user.name}}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-sm-body-2 font-weight-bold">
                                        Должность
                                    </div>
                                    <div class="value py-2 px-4">
                                        Менеджер
                                    </div>

                                </div>
                                <div class="col-6">
                                    <div class="text-sm-body-2 font-weight-bold">
                                        Отдел
                                    </div>
                                    <div class="value py-2 px-4">
                                        Дизайнер
                                    </div>

                                </div>
                                <div class="col-6">
                                    <div class="text-sm-body-2 font-weight-bold">
                                        Телефон
                                    </div>
                                    <div class="value py-2 px-4">
                                        +375 29 6538974
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

                            <div class="change-password">

                                <div class="change-password__title mb-4 ">
                                    <span v-on:click="changePassword=!changePassword">Сменить пароль</span>
                                </div>

                                <form
                                    class="row change-password__form"
                                    method="post"
                                    action="/profile/change-password"
                                    v-show="changePassword"
                                >
                                    <input type="hidden" name="_token" :value="csrf" />
                                    <div class="col-6">
                                        <label class="form-label">Старый пароль</label>
                                        <input type="password" class="form-control" name="old-password">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Новый пароль</label>
                                        <input type="password" class="form-control" name="new-password">
                                    </div>
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary d-block">Изменить пароль</button>
                                    </div>
                                </form>

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
            user: [],
            changePassword: false,
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
    },
    mounted(){
        this.axios.post('/api/profile').then(response => {
            console.log(response.data);
            this.user = response.data;
        });
    }
}
</script>
