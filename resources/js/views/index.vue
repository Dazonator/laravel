<template>
    <v-app>
        <v-navigation-drawer
            app
            width="100"
            v-model="drawer"
        >
            <example-component></example-component>
        </v-navigation-drawer>

        <v-app-bar app>
            <v-app-bar-nav-icon
                @click="drawer = !drawer"
                class="d-lg-none d-block"
            ></v-app-bar-nav-icon>

            <v-toolbar-title>
                {{$route.meta.title}}
            </v-toolbar-title>

            <v-spacer></v-spacer>
        </v-app-bar>

        <v-main>
            <v-container fluid>
                <router-view></router-view>
            </v-container>
        </v-main>
    </v-app>
</template>

<script>
    export default {
        name: "index",
        data(){
            return{
                drawer: true,
            }
        },
        watch: {
            $route: function (val){
                console.log(this.$route);
            }
        }
    }
</script>

<style scoped>

</style>
