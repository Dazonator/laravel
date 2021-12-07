<template>
    <v-dialog
        v-model="isOpen"
        fullscreen
        hide-overlay
        transition="dialog-bottom-transition"
        scrollable

        persistent
        :retain-focus="false"
    >
        <v-card
            tile
        >
            <v-toolbar
                class="toolbar-header"
                flat
                dark
                extension-height="48"
                color="primary"
            >
                <v-btn
                    icon
                    dark
                    @click="close"
                >
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-toolbar-title>
                    {{isUpdate ? 'Изменить задачу' : ''}}
                    {{isDistribution ? 'Распределить задачу' : ''}}
                    {{newTask ? 'Создать задачу' : ''}}
                </v-toolbar-title>
                <v-spacer></v-spacer>
            </v-toolbar>
            <v-card-text
                class="py-6"
            >

                <form
                    v-if="loaded"
                    @submit.prevent="submit"
                >
                    <v-row>
                        <v-col
                            cols="12"
                        >
                            <v-text-field
                                label="Название"
                                name="title"
                                v-model="fields.title"
                            ></v-text-field>
                        </v-col>

                        <v-col
                            cols="12"
                        >
                            <ck-editor
                                @input="ckeditorText"
                                :defaultText = fields.text
                            ></ck-editor>
                        </v-col>

                        <v-col
                            cols="4 d-flex flex-wrap align-center"
                        >
                            <v-menu
                                v-model="menuStructure"
                                :close-on-content-click="false"
                                :nudge-width="200"
                                offset-x
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn
                                        color="primary"
                                        dark
                                        v-bind="attrs"
                                        v-on="on"
                                        block
                                    >
                                        Структура
                                    </v-btn>
                                </template>
                                <v-card>
                                    <v-radio-group
                                        v-model="fields.structure"
                                        @change="structureValueChenge($event)"
                                    >
                                        <v-treeview
                                            dense
                                            :items="structures"
                                        >
                                            <template v-slot:label="props">
                                                <div>
                                                    <v-radio
                                                        :label="props.item.name"
                                                        :value="props.item"
                                                    >

                                                    </v-radio>
                                                </div>
                                            </template>
                                        </v-treeview>
                                    </v-radio-group>
                                </v-card>
                            </v-menu>
                            <span
                                class="mx-1"
                            >
                                {{structureValue}}
                            </span>
                        </v-col>

                        <v-col
                            cols="4"
                        >
                            <v-autocomplete
                                :items="employees"
                                label="Исполнители"
                                item-text="name"
                                item-value="id"
                                multiple
                                name="performers_id"
                                v-model=fields.performers_id
                            >
                                <template v-slot:selection="data">
                                    <v-chip
                                        v-bind="data.attrs"
                                        :input-value="data.selected"
                                        close
                                        @click="data.select"
                                        @click:close="removePerformers(data.item)"
                                    >
                                        <v-avatar left>
                                            <v-img :src="data.item.img"></v-img>
                                        </v-avatar>
                                        {{ data.item.lastname }}
                                    </v-chip>
                                </template>
                                <template v-slot:item="data">
                                    <template v-if="typeof data.item !== 'object'">
                                        <v-list-item-content v-text="data.item"></v-list-item-content>
                                    </template>
                                    <template v-else>
                                        <v-list-item-avatar>
                                            <v-img
                                                :src="data.item.img"
                                                :aspect-ratio="1/1"
                                            >
                                            </v-img>
                                        </v-list-item-avatar>
                                        <v-list-item-content>
                                            <v-list-item-title v-html="data.item.name + ' ' + data.item.lastname"></v-list-item-title>
                                            <v-list-item-subtitle v-html="data.item.position"></v-list-item-subtitle>
                                        </v-list-item-content>
                                    </template>
                                </template>
                            </v-autocomplete>
                        </v-col>
                        <v-col
                            cols="4"
                        >
                            <v-select
                                :items=priorities
                                item-text="priority"
                                item-selection="priority"
                                item-value="id"
                                label="Приоритет"
                                name="priority_id"
                                v-model="fields.priority_id"
                            ></v-select>
                        </v-col>
                        <v-col
                            cols="4"
                        >
                            <v-file-input
                                v-model="fields.loadingFiles"
                                show-size
                                multiple

                                :loading="fileLoading"
                                label="File input"
                                @click:clear="fields.files = null"
                                v-on:change="fileUpload($event)"
                            ></v-file-input>
                        </v-col>
                        <v-col
                            cols="4"
                        >
                            <v-menu
                                v-model="menu"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                transition="scale-transition"
                                offset-y
                                min-width="auto"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="fields.startdate"
                                        label="Дата старта"
                                        append-icon="mdi-calendar"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="fields.startdate"
                                    @input="menu = false"
                                ></v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-col
                            cols="4"
                        >
                            <v-menu
                                v-model="menu2"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                transition="scale-transition"
                                offset-y
                                min-width="auto"

                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="fields.deadline"
                                        label="Дедлайн"
                                        append-icon="mdi-calendar"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="fields.deadline"
                                    @input="menu2 = false"
                                ></v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-col
                            cols="4"
                        >
                            <v-select
                                v-if="fields.parent_id"
                                :items=steps
                                item-text="title"
                                item-selection="title"
                                item-value="id"
                                label="Этап"
                                dense
                                name="in_step"
                                v-model="fields.in_step"
                            ></v-select>
                        </v-col>
                        <v-col
                            cols="12"
                        >
                            <v-btn
                                type="submit"
                                color="primary"
                                large
                                block
                            >
                                {{isUpdate ? 'Изменить' : ''}}
                                {{isDistribution ? 'Распределить' : ''}}
                                {{newTask ? 'Создать' : ''}}
                            </v-btn>
                        </v-col>

                        <v-col
                            cols="12"
                        >
                            <pre>
                                {{fields}}
                            </pre>
                        </v-col>
                    </v-row>
                </form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        name: "department-task",
        data(){
          return{
              isOpen: false,

          }
        },
        props: ['open'],
        watch: {
            open: function(q){
                this.isOpen = q;
                if(!this.isDistribution && !this.isUpdate){
                    this.newTask = true;
                }
            },
        }
    }
</script>

<style scoped>

</style>
