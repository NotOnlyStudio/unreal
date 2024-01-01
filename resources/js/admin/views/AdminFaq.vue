<template>
<div v-if="loaded">
    <div class="d-flex">
        <b-button variant="primary" v-b-modal.editForm><b-icon-plus-circle class="mr-2"/>  new</b-button>
        <b-button variant="success" target="_blank" class="ml-lg-3 ml-0" href="/faq"><b-icon-link class="mr-2"/>To page</b-button>
    </div>
    <b-modal ref="editForm" id="editForm" @ok="newAccordion" title="Add FAQ tab">
        <b-form-group
            label="Input title"
            label-for="title-modal"
        >   
            <b-form-input id="title-modal" placeholder="Title"/>
        </b-form-group>
    </b-modal>
    <div class="d-flex flex-column">

    </div>
   <b-card no-body v-if="accordions.length != 0" class="my-4 shadow">

        <faq-tabs
            :content="accordions"
            v-if="accordions.length != 0"
        />
    </b-card>
    <b-alert variant="warning" class="my-5" v-else show>
        FAQ infos not found
    </b-alert>
    <b-button variant="success position-fixed" @click="saveFAQ" style="bottom: 30px; right: 30px;" v-if="accordions.length != 0"><b-icon-cloud-upload class="mr-2"/>Save</b-button>
    
</div>
<spinner v-else/>
</template>


<script>
import spinner from "../components/Spinner";
import FaqTabs from "../components/FaqTabs";
import axios from "axios"
export default {
    name:"AdminFaq",
    components:{
        spinner,
        "faq-tabs":FaqTabs,
    },
    data(){
        return{
            accordions:[],
            loaded:true
        }
    },
    methods:{
        newAccordion(){
            this.accordions.push({
                "title": document.querySelector("#title-modal").value,
                "content": JSON.stringify([]),
            })
        },
       

        saveFAQ(){
            let titles = document.querySelectorAll('input[name="faqtitles[]"]')
            let names = [];
            let forms = [];
            titles.forEach(item => {
                let key = item.getAttribute("data-key")
                let titles = [];
                let videos = [];
                let descriptions = [];
                document.querySelectorAll('#form-'+key+' input[name="titles[]"]')
                .forEach(
                    elem => {
                        titles.push(elem.value ? elem.value : null)
                    }
                )
                document.querySelectorAll('#form-'+key+' textarea[name="videos[]"]')
                .forEach(
                    elem => {
                        videos.push(elem.value ? elem.value : null)
                    }
                )
                document.querySelectorAll('#form-'+key+' textarea[name="descriptions[]"]')
                .forEach(
                    elem => {
                        descriptions.push(elem.value ? elem.value : null)
                    }
                )
                let content = []
                for(let  i = 0; i< titles.length; i++){
                    content.push({
                        "title":titles[i],
                        "video":videos[i],
                        "description":descriptions[i]
                    })
                }
                forms.push({
                    "title":item.value,
                    "content": JSON.stringify(content)
                })
            });
            axios.post("/faq-control",forms)
            .then(
                resp => {
                    console.log(resp)
                    this.makeToast("FAQ info was saved");
                }
            )
        },
        makeToast(message,append = false) {
            this.$bvToast.toast(message, {
                title: 'UnrealShop notification',
                autoHideDelay: 5000,
                appendToast: append,
                variant: "success"
            })
        }
    },
    mounted(){
        axios.get("/api/faq")
            .then(
                resp => {
                    this.accordions = resp.data.faq
                    console.clear()
                    console.log(resp)
                }
            )
        }
}
</script>
