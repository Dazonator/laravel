<template>
    <main class="col py-4">
        <h1>Настройки</h1>
        <div class="row" v-if="loaded">
            <div class="col" >
                <div class="bg py-6 px-2 mb-2">
                    <v-list>
                        <v-list-item>
                            <router-link :to="{name: 'settings-users'}" class="nav-link">
                                Пользователи
                            </router-link>
                        </v-list-item>
                        <v-list-item>
                            <router-link :to="{name: 'settings-roles'}" class="nav-link">
                                Роли
                            </router-link>
                        </v-list-item>
                    </v-list>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    export default {
        name: "settings",
        data(){
            return{
                loaded: false,
            }
        },
        created() {
            this.init();
        },
        methods: {
            init(){
                this.loaded = true;
            }
        }
    }
</script>

<style scoped>

</style>
