<template>
    <main class="col py-4">
        <div class="row">
            <div class="col" >
                <h1>{{text}}</h1>
<!--                <tinymce></tinymce>-->
<!--                <tiny-editor-->
<!--                    @content="text = $event"-->
<!--                ></tiny-editor>-->
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
                            <tiny-editor
                                @content="text = $event"
                            ></tiny-editor>
<!--                            <textarea name="" id="mytextarea" cols="30" rows="10"></textarea>-->
<!--                            <editor api-key="API_KEY" :init="{plugins: 'wordcount'}" />-->
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
        created() {
        }
        // ready: function() {
        //     tinyMCE.execCommand('mceAddEditor');
        // },
        // beforeDestroy: function() {
        //     tinyMCE.execCommand('mceRemoveEditor');
        // }

    }
</script>

<style scoped>
</style>
