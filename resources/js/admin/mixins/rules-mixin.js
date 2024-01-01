import axios from "axios"
export let rules = {
    data(){
        return{
            rules:[],
        }
    },
    methods:{
        addRule(){
            this.rules.push({
                "title":"",
                "description":"",
            })
        },  
        deleteLastRule(key){
            this.rules.splice(key, 1)
        },
        saveRules(link){
            let text = tinymce.activeEditor.getContent();
            axios.post(link,{
                "rules":JSON.stringify(text)
            }).then(
                resp => {
                    if(resp.status == 200){
                        this.makeToast("Rules was saved")
                    }
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
        },
      
    }
}