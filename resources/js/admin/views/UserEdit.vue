<template>
    <b-form ref="editForm" v-if="loaded">
        <b-form-group
            label-for="name"
            label="Name"
        >
            <b-form-input name="name" id="name" placeholder="Name" :value="user.name"></b-form-input>
        </b-form-group>
        <b-form-group
            label-for="email"
            label="E-mail"
        >
            <b-form-input name="email" id="email" placeholder="E-mail" type="email" :value="user.email"></b-form-input>
        </b-form-group>
        <b-form-group
            label-for="password"
            label="Password"
            description="Enter a new password if you want to change it."
        >
            <b-form-input name="password" id="password" type="password" placeholder="Password" :value="user.password"></b-form-input>
        </b-form-group>
        <b-form-group
            label="Specialization"
            label-for="specialization"
        >
            <b-form-input name="specialization" id="specialization" placeholder="Specialization"/>
        </b-form-group>
        <b-form-group
            label="Website"
            label-for="website"
        >
            <b-form-input name="website" id="website" placeholder="Website"/>
        </b-form-group>
        <b-form-group
            label="Signature"
            label-for="signature"
        >
            <b-form-input name="signature" id="signature" placeholder="Signature"/>
        </b-form-group>
        <b-form-group
            label="Location"
            label-for="location"
        >
            <b-form-input name="location" id="location" placeholder="Location"/>
        </b-form-group>
        <b-form-group
            label-for="photo"
            label="Avatar"
        >
            <b-form-file name="avatar" accept="image/jpeg,image/png,image/gif" id="photo" placeholder="Upload photo" ></b-form-file>
        </b-form-group>
        <input type="hidden" name="user_id" :value="user.id">


        <b-button variant="primary btn-md" class="float-right"  @click="submitEdit">Save</b-button>
    </b-form>
    <spinner v-else></spinner>
</template>

<script>
    import spinner from "../components/Spinner";
    import axios from "axios"
    export default {
        name: "UserEdit",
        components:{
            spinner,
        },
        data(){
            return{
                loaded: false,
                user: null,

            }
        },
        methods:{
            submitEdit(){
                let formData = new FormData(this.$refs.editForm)
                let config = { headers: { 'Content-Type': 'multipart/form-data' } }
                axios.post("/user/change", formData, config)
                .then(
                    resp => {
                        if(resp.status == 200)
                            document.location.href="/admin/users"
                    }
                )
            }
        },
        mounted(){
            document.querySelector("h1").innerText = "Edit profile"
            let id = this.$route.params.id;
            axios.get("/user-info/"+id)
            .then(
                resp => {
                    console.log(resp)
                    if(resp.data.success == true){
                        this.user = resp.data.user
                        this.loaded = true;
                    }else{
                        alert("Пользователь не найден");
                    }
                }
            )
        }
    }
</script>

<style scoped>

</style>
