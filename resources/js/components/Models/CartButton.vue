<template>
        <a :class="['btn',buttonType]" v-if="loader" href="javascript:;">
            <span class="spinner"></span>
        </a>
        <a :class="['btn',buttonType]" @click.prevent="cartMethod" v-else-if="isFree && !loader">Download</a>
        <a :class="['btn',buttonType]" @click.prevent="cartMethod" v-else-if="buyModel && !loader">Buy Now 3$ (291₽)</a>
        <a :class="['btn',buttonType]" @click.prevent="cartMethod" v-else-if="!loader">Download</a>

    </template>


<script>
export default {
  name:"CartButton",
  props:[
    "itemId",
    "price",
    "type",
    "text",
    "isFree",
    "buttonDownload",
    "buyText"
  ],
  computed:{
    buttonType(){
      if(this.type == null)
        return "btn-buy"
      else
        return this.type
    }
  },
  data(){
    return{
      buyModel: !this.buttonDownload,
      buttonText: this.text,
      bt: this.buyText,
      loader: false,
    }
  },
  methods:{
    cartMethod(){

      if(this.isFree){
        let user = $cookies.get("user");
        console.log(user);
        if (user == null) {
          alert("To download the asset, please log in to your account.");
          document.location.href="/login";
          return;
        }
        this.loader = true;
        axios({
          url: '/free-model/download/'+this.itemId,
          method: 'GET',
          responseType: 'blob',
        }).then((response) => {
            this.loader = false;
          if(response.status == 202){
            alert("Only three \"FREE\" model can be downloaded per day")
          }
          else {
            var fileURL = window.URL.createObjectURL(new Blob([response.data]));
            var fileLink = document.createElement('a');

            fileLink.href = fileURL;
            fileLink.setAttribute('download', 'model.zip');
            document.body.appendChild(fileLink);

            fileLink.click();
          }
        }).catch(
            err => {
              this.loader = false;
              console.log(err)

              if(err.response.status == 401){
                  alert("To download the asset, please log in to your account.")
                  document.location.href="/login";
              }
              else{
                  alert("You have reached the limit of free models for today.")
              }
            }
        );
      }else{
        if(this.buyModel){
          let user = $cookies.get("user")
          if(user){
            window.axios.post("/model-buy",{
              "product_id":this.itemId,
              "price":this.price ? this.price : 1,
            })
                .then(
                    resp => {
                      console.log(resp)
                      if(resp.status == 200 && resp.data.answer == true){
                        this.$emit("show-alert",{
                          "text":"Model added to personal library",
                          "variant":"success"
                        })
                        this.buyModel = false;
                        this.buttonText = "Download";
                        this.bt = "Download";
                        console.log(this.buyText);
                      }else{

                        if(resp.data.answer == false){
                          document.location.href="/basket"
                        }else{
                          console.error("Server send error code")
                        }
                      }
                    }
                ).catch(
                err => {
                  if(err.response.status == 401){
                    document.location.href="/login"
                  }
                  if(err.response.status == 402){
                    document.location.href="/basket"
                  }
                  if(err.response.status == 409){
                    this.$emit("show-alert",{
                      "text":"The model was purchased earlier.",
                      "variant":"warning "
                    })
                  }
                }
            )
          }else{
            document.location.href="/basket"
          }
        }else{
          document.location.href=`/downloads/model/id-${this.itemId}`
        }
      }
    }
  }
}
</script>
<style scoped>
.spinner {
    animation: spin 1s linear infinite;
    background: url(data:image/svg+xml;utf-8;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHdpZHRoPSIzMiIgaGVpZ2h0PSIzMiIgdmlld0JveD0iMCAwIDMyIDMyIj4NCjxnIGlkPSJpY29tb29uLWlnbm9yZSI+DQoJPGxpbmUgc3Ryb2tlLXdpZHRoPSIxIiB4MT0iIiB5MT0iIiB4Mj0iIiB5Mj0iIiBzdHJva2U9IiM0NDlGREIiIG9wYWNpdHk9IiI+PC9saW5lPg0KPC9nPg0KCTxwYXRoIGQ9Ik0xMiA0YzAtMi4yMDkgMS43OTEtNCA0LTRzNCAxLjc5MSA0IDRjMCAyLjIwOS0xLjc5MSA0LTQgNHMtNC0xLjc5MS00LTR6TTIwLjQ4NSA3LjUxNWMwLTIuMjA5IDEuNzkxLTQgNC00czQgMS43OTEgNCA0YzAgMi4yMDktMS43OTEgNC00IDRzLTQtMS43OTEtNC00ek0yNiAxNmMwLTEuMTA1IDAuODk1LTIgMi0yczIgMC44OTUgMiAyYzAgMS4xMDUtMC44OTUgMi0yIDJzLTItMC44OTUtMi0yek0yMi40ODUgMjQuNDg1YzAtMS4xMDUgMC44OTUtMiAyLTJzMiAwLjg5NSAyIDJjMCAxLjEwNS0wLjg5NSAyLTIgMnMtMi0wLjg5NS0yLTJ6TTE0IDI4YzAgMCAwIDAgMCAwIDAtMS4xMDUgMC44OTUtMiAyLTJzMiAwLjg5NSAyIDJjMCAwIDAgMCAwIDAgMCAxLjEwNS0wLjg5NSAyLTIgMnMtMi0wLjg5NS0yLTJ6TTUuNTE1IDI0LjQ4NWMwIDAgMCAwIDAgMCAwLTEuMTA1IDAuODk1LTIgMi0yczIgMC44OTUgMiAyYzAgMCAwIDAgMCAwIDAgMS4xMDUtMC44OTUgMi0yIDJzLTItMC44OTUtMi0yek00LjUxNSA3LjUxNWMwIDAgMCAwIDAgMCAwLTEuNjU3IDEuMzQzLTMgMy0zczMgMS4zNDMgMyAzYzAgMCAwIDAgMCAwIDAgMS42NTctMS4zNDMgMy0zIDNzLTMtMS4zNDMtMy0zek0xLjc1IDE2YzAtMS4yNDMgMS4wMDctMi4yNSAyLjI1LTIuMjVzMi4yNSAxLjAwNyAyLjI1IDIuMjVjMCAxLjI0My0xLjAwNyAyLjI1LTIuMjUgMi4yNXMtMi4yNS0xLjAwNy0yLjI1LTIuMjV6IiBmaWxsPSIjMzEzNTQ3Ij48L3BhdGg+DQo8L3N2Zz4=)
    no-repeat
    left center;
    background-size: cover;
    display: block;
    height: 25px;
    margin: 0 auto;
    width: 25px;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>
