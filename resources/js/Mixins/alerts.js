export const alertConfig = {
    data(){
        return{
            alert:false,
            errorMsg:"",
            alertType:"",
        }
    },
    methods:{
        showAlert(message,alertType = "warning"){
            this.alertType = alertType+" my-2"
            this.errorMsg = message
            this.alert = true
        }
    },

}
