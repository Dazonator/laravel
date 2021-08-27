<template>
    <main class="col py-4">
        <div class="row">
            <div class="col">
                <h2 class="mb-4">Profile vue</h2>
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
                            <div class="row">
                                <div class="col-12">
                                    <div class="text-sm-body-2 font-weight-bold">
                                        Имя
                                    </div>
                                    <div class="value py-2 px-4">
                                        Марина Маринова
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
                                        katya_solnyshko86@yandex.by
                                    </div>

                                </div>
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
            tasks: []
        }
    },
    mounted(){
        this.axios.get('/api/user').then(response => {
            console.log(response);
            this.tasks = response.data;
        });
    }
}
</script>
