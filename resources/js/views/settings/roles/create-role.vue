<template>

    <main class="col py-4" v-if="loaded">
        <h1>{{id ? 'Изменить роль' : 'Создать роль'}}</h1>
        <div class="row">
            <div class="col" >
                <div class="bg pa-6 mb-2">
                    <v-form
                        @submit.prevent="submit"
                    >

                        <v-text-field
                            class="mb-4"
                            v-model="fields.name"
                            label="Название роли"
                            hide-details
                        ></v-text-field>
                        <v-row>
                            <v-col
                                cols="3"
                                v-for="permission in permissions"
                                :key="permission.id"
                            >
                                <v-checkbox
                                    v-model="fields.permissions"
                                    :label="permission.name"
                                    :value="permission.id"
                                    input-value="fields.permissions"
                                ></v-checkbox>
                            </v-col>

                        </v-row>
                        <v-btn
                            color="primary"
                            elevation="2"
                            type="submit"
                        >
                            {{id ? 'Изменить роль' : 'Создать роль'}}
                        </v-btn>
                    </v-form>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    export default {
        name: "create-role",
        data(){
            return{
                id: Number(this.$route.params.roleId),
                loaded: false,
                fields: {
                    name: '',
                    permissions: []
                },
                permissions: [],
                selected: [],
            }
        },
        created() {
            this.init();
        },
        watch:{
            $route (to, from){
                this.id = Number(this.$route.params.roleId);
                this.init();
            }
        },
        methods: {
            init(){

                axios.post(`/api/settings/getPermissions`).then(response => {
                    console.log(response.data);
                    this.permissions = response.data;
                    this.loaded = true;
                });

                if (this.id){
                    axios.post(`/api/settings/getRoleForUpdate/${this.id}`).then(response => {
                        // this.fields = response.data;
                        console.log(response.data);
                        this.fields.name = '';
                        this.fields.id = response.data.id;
                        this.fields.name = response.data.name;
                        this.fields.permissions = [];
                        for (let permission of response.data.permissions) {
                            this.fields.permissions.push(permission.id);
                        }
                        this.loaded = true;
                    });
                }
            },
            submit(){
                if(this.id){
                    axios.post(`/api/settings/role/update`, this.fields).then(response => {
                        this.$store.dispatch('user/getAppParameters');
                        this.$router.push('./../');
                    });
                } else {
                    axios.post(`/api/settings/role/create`, this.fields).then(response => {
                        this.$router.push('./');
                    });

                }
            }
        }
    }
</script>

<style scoped>

</style>
