<template>
    <div class="search__bg" style="" @keydown.esc="closeModal">
        <div class="search__inner"  >
            <div :class="[ !sended ? 'py-5 px-5' : '','reportWrapper d-flex flex-column']" v-click-outside="closeModal" style="background: white">
                <p v-if="sended" class="text-bold text-orange fsz17">Support</p>
                <b-form  v-if="sended">
                    <b-form-group>
                        <b-form-input placeholder="Subject" required max="500" v-model="title"/>
                    </b-form-group>
                    <b-form-group>
                        <b-form-input placeholder="Email" required max="500" v-model="email"/>
                    </b-form-group>
                    <b-form-group>
                        <b-form-textarea placeholder="Your message" required v-model="description"/>
                    </b-form-group>
                    <b-button variant="bordered sm " @click.prevent="sendReport">Send</b-button>
                </b-form>
                <div v-else class="w-100 h-100 d-flex justify-content-center">
                    <p class="px-0">Thank you for contacting us</p>
                </div>
            </div>
        </div>
    </div>
</template>



<script>
export default {
    name:"ReportWrapper",
    data(){
        return{
            title:"",
            description:"",
            email:"",
            sended: true,
        }
    },
    methods:{
        sendReport(){
            if(this.title != "" && this.description != "" && this.email != ""){
                let descr = this.description.replace('\n','<br>')
                descr = descr.replace('\t','<br>')
                descr = descr.replace(/\r?\n/g, '<br />')
                window.axios.post("/reports",{
                    "title":this.title,
                    "description":descr,
                    "email":this.email,
                }).then(
                    resp => {
                        if(resp.status == 200){
                            this.sended = false
                            setTimeout(() => {
                                this.closeModal(); 
                            }, 3000);
                        }
                    }
                )
            }else{
                alert("Not all required fields are filled")
            }
        },
        closeModal(){
            this.$emit("close-modal")
        }
    }
}
</script>


<style scoped>
    .reportWrapper{
        width: auto;
        max-width: 100%;
        height: auto;
        border-radius: 5px;
        padding-top: 50px;
        padding-right: 80px;
        padding-bottom: 55px;
    }
    .reportWrapper p{
        padding-left: 33px;
    }
    form{
        max-width: 650%;
        padding-left: 63px;
        width: 650px;
    }
    input{
        height: 40px;
        width: 650px;
        padding: 10px 8px;
    }
    .form-group:first-child input{
        margin-top: 50px;
    }
    textarea,input,textarea::placeholder,input::placeholder{
        color:  #BFBFBF;
        font-family: GhothamPro;
        font-size: 17px;
    }
    textarea,input{
        width: 100%;
    }
    textarea{
        height: 200px;
        resize: none;
        padding: 10px;
    }
    button{
        width: 85px;
        height: 30px;
        margin-top: 53px;
        float: right;
    }
    @media(max-width: 750px){
        form{
            padding-left: 0;
        }
        .reportWrapper {
            padding-right: 0;
        }
    }
</style>