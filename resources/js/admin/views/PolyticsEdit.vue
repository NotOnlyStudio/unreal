<template>
    <preloader-wrapper v-if="!loaded"/>
    <div v-else>
          <editor
            name="content"
            id="content"
            api-key="ho2xahb38lo8560uw0zpjbbxg62ko9plmy3vt47sex0p2vgj"
            :init="init"
        />
        <div class="d-flex justify-content-end my-3">
            <b-button variant="success" @click="savePolicy"><b-icon-upload class="mr-2"/>Save</b-button>
        </div>
    </div>
</template>

<script>
import spinner from "../components/Spinner"
import editor from "@tinymce/tinymce-vue";
import axios from "axios";
export default {
    name:'PolyticsEdit',
    data(){
        return{
            loaded: false,
            content:"",
            model: false,
            alias: "",
            init:{

                height: 500,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount',
                    'link image code preview imagetools table lists textcolor hr wordcount',"media"
                ],
                images_upload_url: 'postAcceptor.php',
                    file_picker_types: 'image',
                    images_upload_handler:function(blobInfo, success, failure, progress) {
                        let formdata = new FormData()
                        formdata.append('file',blobInfo.blob())
                        axios.post('/upload-img',formdata)
                        .then((res) => {
                            if (res.status == 200) {
                                console.log(res.data)
                                success(res.data.location)
                            } else {
                                failure('upload failed!')
                            }
                        })
                },
                init_instance_callback: (item) => {
                    item.setContent(this.content);
                },
                toolbar: 'undo redo | styleselect | forecolor | bold italic underline | alignleft aligncenter alignright alignjustify | outdent indent | link image media'

            },
        }
    },
    components:{
        "preloader-wrapper":spinner,
        editor
    },
    methods:{
        savePolicy(){
            let content = tinymce.activeEditor.getContent();
            axios.post("/set-policy/"+this.alias,{
                "content":content
            })
            .then(
                resp => {
                    console.log(resp)
                    // document.location.href="/admin/polytics"
                }
            )
        },
    },
    mounted(){
        this.alias = window.location.pathname.split("/")[3];
        axios.get(`/get-policy?name=${this.alias}`)
        .then(
            resp => {
                document.querySelector("h1").innerText = resp.data.title
                console.log(resp.data)
                this.content = resp.data.content
                this.loaded = true
            }
        )
    }
}
</script>
