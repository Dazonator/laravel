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
                offset-x="20"
                offset-y="20"
                :content="$store.getters['notifications/notifications']"
                :value="$store.getters['notifications/notifications']"
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
        <v-snackbar
            v-model="snackbar"
            :timeout="timeout"
            :vertical=true
        >
            {{ snackbarText }}

            <template v-slot:action="{ attrs }">
                <v-btn
                    color="pink"
                    text
                    v-bind="attrs"
                    @click="snackbar = false"
                >
                    Close
                </v-btn>
            </template>
        </v-snackbar>
        <div class="snacks">
            <v-alert
                shaped
                v-for="item in items" :key="item.id"
                :type="item.template === 'create-task' ? 'success' : 'info'"
            >
                <h6>{{item.title}}</h6>
                {{item.body}}
            </v-alert>
        </div>
    </v-app>
</template>

<script>
    export default {
        name: "index",
        data(){
            return{
                drawer: null,
                isTest: true,
                snacks: null,
                snacksIndex: 0,
                snackbarText: '',
                snackbar: false,
                timeout: 5000,
                index: 0,
                items: [],
            }
        },
        computed: {
            user: function (){
                return this.$store.getters['user/authUser'];
            },
        },
        watch: {
            $route: function (val){
            }
        },
        created() {
            // console.log(this.$store.getters['notifications/notifications']);
        },
        mounted() {
            window.Echo.private('App.Models.User.' + this.user.id)
                .notification((notification) => {
                    switch (notification['template']) {
                        case 'create-meeting':
                        case 'update-meeting':
                        case 'delete-meeting':
                            this.$store.dispatch('user/plusCalendarIndex');
                            break;
                        case 'new-task-message':
                            this.$store.dispatch('user/getAppParameters');
                            break;
                        default:
                            break;

                    }
                    this.items.unshift(notification);
                    // if(notification['template'] === 'new-task-message'){
                    //     this.$store.dispatch('user/getAppParameters');
                    // }
                    // console.log(notification);
                    // else {
                    setTimeout(() => this.items.pop(), this.timeout);
                    // }
                });
        },
        destroyed() {
            window.Echo.leaveChannel('private-App.Models.User.' + this.user.id);
        }
    }
</script>

<style>
    .snacks{
        position: fixed;
        right: 0;
        bottom: 0;
        z-index: 999999;
        max-width: 350px;
        min-height: 50px;
        min-width: 50px;
    }
</style>
