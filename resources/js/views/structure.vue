<template>
    <main class="col py-4">
        <div class="row">
            <div class="col" >
                <h1>{{text}}</h1>
                <v-dialog
                    v-model="dialog"
                    persistent
                    max-width="600px"

                    :retain-focus="false"
                    :eager="true"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            color="primary"
                            dark
                            v-bind="attrs"
                            v-on="on"
                        >
                            Open Dialog
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                            <span class="text-h5">User Profile</span>
                        </v-card-title>
                        <v-card-text>
                            <ck-editor @input="getEditorData"></ck-editor>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="dialog = false"
                            >
                                Close
                            </v-btn>
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="dialog = false"
                            >
                                Save
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

            </div>
        </div>
    </main>
</template>

<script>
    export default{
        data(){
            return{
                text: '',
                dialog: false
            }
        },
        methods: {
            getEditorData (data) {
                this.text = data;
            }
        }

    }
</script>

<style scoped>
</style>
