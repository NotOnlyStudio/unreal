<template>
    <div class="upload upload-bg" @keydown.esc="closeModal">
        <div class="upload-bg__inner">
            <b-card v-click-outside="closeModal" bg-variant="light" header="Upload avatar" class="text-center upload__inner">
                <b-icon-x @click="closeModal" class="close"/>
                <p class="text-center my-4">Select the image you want to use as your avatar.<br>
    Pictures in JPG, JPEG, GIF, PNG format are accepted</p>
                <button v-if="!uploadedPhoto" @click="activateFile" class="btn mb-5 btn-bordered">Upload photo</button>
                <img :src="imgSrc" class="border border-light mb-4" style="width: 90%; max-height: 65vh;height: auto; object-fit: cover;" alt="Uploaded avatar" v-if="imgSrc != '' && uploadedPhoto">
                <div v-if="uploadedPhoto" style="margin: 0 auto;" class="sm-100 d-flex justify-content-between flex-lg-row flex-column mb-5">
                    <b-button style="width: 160px" variant="red" @click="clearPhoto">Cancel</b-button>
                    <b-button style="width: 160px" variant="bordered" @click="uploadToServer">Save</b-button>
                </div>
                <form ref="avatarForm">
                    <input @change="uploadedPhotoMeth" accept=".jpg,.png,.gif,.jpeg" type="file" class="d-none" id="changePhotoInput" name="avatar">
                </form>
            </b-card>
        </div>
    </div>
</template>


<script>
export default {
    name:"UploadPhoto",
    data(){
        return{
            uploadedPhoto:false,
            imgSrc: "",
        }
    },
    methods:{
        uploadToServer(){
            let formData = new FormData(this.$refs.avatarForm)
            let config = { headers: { 'Content-Type': 'multipart/form-data' } }
            window.axios.post("/update-profile-photo",formData, config)
            .then(
                resp => {
                    if(resp.status == 200){
                        this.$emit("uploadedPhoto",{
                            "photo":resp.data.user_photo
                        })
                        this.closeModal();
                    }
                }
            )    
        },
        closeModal(){
            document.querySelector(".upload__inner").classList.add("inner-close")
            setTimeout(() => {
                this.$emit("closeModal")
            },500)
        },
        clearPhoto(){
            this.uploadedPhoto = false
            this.imgSrc = ""
            document.querySelector("#changePhotoInput").value = ""
        },
        activateFile(){
            document.querySelector("#changePhotoInput").click()
        },
        uploadedPhotoMeth(e){
            const image = e.target.files[0];
            const reader = new FileReader();
            reader.readAsDataURL(image);
            let parent = e.target.parentElement;
            let img = parent.children[1];
            reader.onload = e =>{
                let imgSrc = e.target.result;
                this.imgSrc = imgSrc;
            };
            this.uploadedPhoto = true;
        }
    }
}
</script>


<style scoped>

    .close{
        position: absolute;
        top: 12px;
        right: 12px;
        cursor: pointer;
        width: 20px;
        height: 20px;
        transition: .3s linear;
    }
    .sm-100{
        width: 50%;
    }
    @media(max-width: 650px){
        .sm-100{
            width: 100%;
            height: 100px;
            align-items: center!important;
            justify-content: space-between;
        }
    }
    .close:hover{
        opacity: .75;
        transform: scale(1.2);
    }
    .upload-bg__inner{
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }
    .upload-bg{
        position: fixed;
        top: 0;
        width: 100%;
        height: 100%;
        left: 0;
        z-index: 7000;
        backdrop-filter: blur(3.1px);
        /* background: rgba(0,0,0,.35); */
    }
    .upload__inner{
        animation: uploadInnerAnim;
        animation-duration: .75s;
        transition: .3s linear;
        width: 45rem;
        height: fit-content;
        animation-fill-mode: forwards;
    }
    .upload__inner p{
        font-family: 'GhothamPro';
        font-size: 16px;
    }
    .upload__inner .card-header{
        font-family: 'GhothamPro-Bold';
        font-size: 18px;
    }
    .inner-close{
        transition: .3s linear;
        animation: uploadInnerAnimClose;
        animation-duration: .5s;
        animation-fill-mode: forwards;
    }
    @keyframes uploadInnerAnim{
        0%{
            transform: scale(0);
        }
        50%{
            transform: scale(1.2);
        }
        100%{
            transform: scale(1);
        }
    }
    @keyframes uploadInnerAnimClose{
        0%{
            transform: scale(1);
        }
        50%{
            transform: scale(1.2);
        }
        100%{
            transform: scale(0);
        }
    }
</style>