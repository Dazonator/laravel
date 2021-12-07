<template>
    <div class="row" v-if="loaded">
        <v-col
            cols="12"
            lg="4"
            class="team-nav"
        >
            <div class="bg py-6 px-2 mb-2">
                <div
                    class="d-flex"
                >
<!--                    <h5-->
<!--                        class="pl-4"-->
<!--                    >-->
<!--                        Структура-->
<!--                    </h5>-->
                    <v-spacer></v-spacer>
                    <v-btn
                        icon
                        color="grey lighten-1"
                        x-small
                        @click="dialogCreate = true"
                    >
                        <v-icon
                            x-small
                        >mdi-plus</v-icon>
                    </v-btn>
                </div>
                <v-treeview
                    :items="structure"
                    selected-color="indigo"
                    return-object
                >
                    <template slot="label" slot-scope="props">
                        <div
                            class="d-flex align-center"
                        >
                            <router-link

                                :to="'/structure/'+props.item.id"
                                style="white-space: normal;"
                            >
                                {{ props.item.name }}
                            </router-link>

                            <v-spacer></v-spacer>

                            <v-btn-toggle>
                                <v-btn
                                    icon
                                    x-small
                                    @click="clickRenameCategory(props.item.id, props.item.name)"
                                >
                                    <v-icon
                                        x-small
                                        color="grey"
                                    >mdi-pencil</v-icon>
                                </v-btn>
                                <v-btn
                                    icon
                                    x-small
                                    @click="clickAddCategory(props.item.id)"
                                >
                                    <v-icon
                                        x-small
                                        color="grey"
                                    >mdi-plus</v-icon>
                                </v-btn>
                                <v-btn
                                    icon
                                    x-small
                                    @click="deleteCategory(props.item.id)"
                                >
                                    <v-icon
                                        x-small
                                        color="grey"
                                    >mdi-delete</v-icon>
                                </v-btn>
                            </v-btn-toggle>
                        </div>
                    </template>
                </v-treeview>
                <v-dialog
                    v-model="dialogCreate"
                    max-width="500px"
                >
                    <v-card>
                        <v-card-title>
                            <h3>
                                Создать категорию
                            </h3>
                        </v-card-title>
                        <v-card-text>
                            <v-text-field
                                label="Название"
                                name="title"
                                v-model="newCategory.name"
                                v-on:keyup.enter="createCategory()"
                            ></v-text-field>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn
                                color="primary"
                                text
                                @click="dialogCreate = false"
                            >
                                Закрыть
                            </v-btn>

                            <v-spacer></v-spacer>

                            <v-btn
                                color="primary"
                                text
                                @click="createCategory()"
                            >
                                Создать
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-dialog
                    v-model="dialogRename"
                    max-width="500px"
                >
                    <v-card>
                        <v-card-title>
                            <h3>
                                Изменить название категории
                            </h3>
                        </v-card-title>
                        <v-card-text>
                            <v-text-field
                                label="Новое название"
                                name="title"
                                v-model="newCategory.name"
                                v-on:keyup.enter="createCategory()"
                            ></v-text-field>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn
                                color="primary"
                                text
                                @click="dialogRename = false"
                            >
                                Закрыть
                            </v-btn>

                            <v-spacer></v-spacer>

                            <v-btn
                                color="primary"
                                text
                                @click="renameCategory()"
                            >
                                Изменить
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </div>
        </v-col>
        <v-col
            cols="12"
            lg="8"
        >
            <structure-tasks
                v-if="categoryId"
            ></structure-tasks>
        </v-col>
    </div>
</template>

<script>
    export default{
        data(){
            return{
                categoryId: Number(this.$route.params.categoryId),
                dialogCreate: false,
                dialogRename: false,
                newCategory: {},
                structure: {},
                loaded: false,
            }
        },
        watch: {
            '$route.path' (to, from){
                this.categoryId = Number(this.$route.params.categoryId);
            },
            dialogCreate: function () {
                if (!this.dialogCreate){
                    this.newCategory = {};
                }
            },
            dialogRename: function () {
                if (!this.dialogRename){
                    this.newCategory = {};
                }
            }
        },
        methods: {
            init(){
                axios.post(`/api/structure/getStructure`).then(response => {
                    this.structure = response.data;
                    console.log(response.data);
                    this.loaded = true;
                });
            },
            createCategory(){

                axios.post(`/api/structure/create`, this.newCategory).then(response => {
                    this.dialogCreate = false;
                    this.init();
                });
            },
            renameCategory(){
                axios.post(`/api/structure/update`, this.newCategory).then(response => {
                    this.dialogRename = false;
                    this.init();
                });
            },
            deleteCategory(id){
                axios.post(`/api/structure/delete/${id}`).then(response => {
                    this.init();
                });
            },
            clickAddCategory(parent){
                this.newCategory.parent_id = parent;
                this.dialogCreate = true;
            },
            clickRenameCategory(id, name){
                this.newCategory.id = id;
                this.newCategory.name = name;
                this.dialogRename = true;
            }
        },
        created() {
            this.init();
        }

    }
</script>

<style scoped>
</style>
