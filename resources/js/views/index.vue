<template>
    <v-app>
        <v-navigation-drawer
            app
            width="100"
            v-model="drawer"
            :permanent="$vuetify.breakpoint.lgAndUp"
        >
            <example-component></example-component>
        </v-navigation-drawer>

        <v-app-bar app>
            <v-app-bar-nav-icon
                @click.stop="drawer = !drawer"
                class="d-lg-none d-block"
            ></v-app-bar-nav-icon>

            <v-toolbar-title>
                <h2>
                    {{$route.meta.title}}
                </h2>
            </v-toolbar-title>

            <v-spacer></v-spacer>
            <v-badge
                bordered
                bottom
                color="red"
                dot
                offset-x="20"
                offset-y="20"

                :value="isTest"
            >
                <v-btn
                    icon
                    :to="{name: 'notifications'}"
                >
                    <v-icon>mdi-bell-outline</v-icon>
                </v-btn>
            </v-badge>
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
                drawer: null,
                isTest: true
            }
        },
        watch: {
            $route: function (val){
            }
        }
    }
</script>

<style scoped>

</style>
