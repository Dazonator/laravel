<template>
    <div>
        <ckeditor
            :editor="editor"
            v-model="editorData"
            :config="editorConfig"
            @input="postData"
        ></ckeditor>
    </div>
</template>

<script>

    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    class UploadAdapter {
        constructor(loader) {
            this.loader = loader;
        }

        upload() {
            return this.loader.file
                .then( uploadedFile => {
                    return new Promise( ( resolve, reject ) => {
                        const data = new FormData();
                        data.append( 'upload', uploadedFile );

                        axios.post('/api/ckeditor/upload', data).then(response => {
                            if ( response.data.result === 'success' ) {
                                resolve( {
                                    default: response.data.url
                                } );
                            } else {
                                reject( response.data.message );
                            }
                        }).catch(error => {
                            reject( 'Upload failed' );
                        });

                    } );
                } );
        }

        abort() {
        }
    }
    export default {
        name: '',
        props: ['defaultText'],
        watch: {
            defaultText: function (q) {
                if(this.defaultText){
                    this.editorData = this.defaultText;
                } else {

                    this.editorData = '';
                }
            }
        },
        data() {
            return {

                details: {},
                editor: ClassicEditor,
                editorData: '',
                editorConfig: {
                    placeholder: 'Текст',
                    extraPlugins: [ this.MyCustomUploadAdapterPlugin ],
                }
            }
        },
        components: {
        },

        methods: {
            postData(data){
                this.$emit('input', this.editorData);
            },
            MyCustomUploadAdapterPlugin ( editor ) {
                editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {

                    return new UploadAdapter( loader );
                };
            },
        }
    }
</script>
<style>
    /*.ck.ck-reset_all, .ck.ck-reset_all *{*/
    /*    display: none;*/
    /*}*/
</style>
