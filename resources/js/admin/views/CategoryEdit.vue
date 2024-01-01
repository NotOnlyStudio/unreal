<template>
    <div>
        <spinner v-show="spinner"></spinner>
        <b-jumbotron v-show="!spinner">
            <b-form enctype="multipart/form-data" ref="editForm">
                <b-form-group
                    label="Title"
                    label-for="title"
                >
                    <b-form-input name="order" id="title" v-model="category.name" placeholder="Title"></b-form-input>
                </b-form-group>
                <b-form-group
                    label="Order"
                    label-for="order"
                >
                    <b-form-input name="order" id="order" v-model="category.order" placeholder="Order"></b-form-input>
                </b-form-group>
                <b-form-group
                    label="Parent"
                    label-for="parent"
                >
                    <select class="form-control" name="parent" v-model="category.parent" id="parent">
                        <option value="none" selected>None</option>
                        <option v-for="cat in categories"  :value="cat.id" :selected="cat.id == category.id ? 'selected' : 'none'">{{cat.name}}</option>
                    </select>
                </b-form-group>
                <b-form-group
                    label="Photo"
                    label-for="photo"
                >
                    <input name="_method" type="hidden" value="PUT">

                    <b-form-file  v-model="category.photo" id="photo" name="photo"></b-form-file>
                </b-form-group>
                <input type="hidden" name="_method" value="PUT">
                <div class="d-flex justify-content-end">
                    <b-button variant="success" @click.prevent="submitEdit" type="submit">Edit</b-button>
                </div>
            </b-form>
        </b-jumbotron>
    </div>
</template>

<script>
    import axios from 'axios';
    import spinner from "../components/Spinner";
    export default {
        name: "CategoryEdit",
        data(){
            return{
                categories:[],
                category: [],
                spinner: true,
            }
        },
        components:{
          spinner
        },
        methods:{
            submitEdit(){
                let config = { headers: { 'Content-Type': 'multipart/form-data' } }
                let formData = new FormData(this.$refs.editForm);
                if(this.category.name != ""){
                    axios.post('/api/category/'+this.category.id, formData, config).then( resp => {
                            console.log(resp)
                        }
                    )
                }
            }
        },
        mounted(){
            let id = this.$route.params.id;
            axios.get("/api/category/"+id)
            .then(
                resp => {
                    console.log(resp.data)
                    this.category = resp.data
                }
            )
            axios.get("/api/category")
            .then(
                resp => {
                    this.categories = resp.data.data;
                }
            )
            setTimeout(()=>{
                this.spinner = false;
            },1500)
        }
    }
</script>

<style scoped>
    form{
        width: 500px;
        max-width: 100%;
    }
</style>
