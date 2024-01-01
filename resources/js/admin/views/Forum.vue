<template>
    <div v-if="loaded">
        <div class="d-flex justify-content-start mb-3">
            <b-button variant="primary"  v-b-modal.modal-add-forum><b-icon-plus-circle class="mr-2"/>Add new</b-button>
            <b-button variant="info" v-b-modal.modal-add-group class="mx-2"><b-icon-bookshelf/><span class="ml-2">Add section</span></b-button>
        </div>
        <table v-if="forums.length != 0 && rerender" class="table table-stripped">
            <thead class="thead-dark">
                <th>ID</th>
                <th>Title</th>
                <th title="If checked, it is visible on the site.">Show</th>
                <th title="Count topics">Parent</th>

                <th title="Count topics">Topics</th>
                <th title="Count posts">Posts</th>
                <th>Edit</th>
                <th>Delete</th>
            </thead>
            <tbody>
                <tr v-for="(item,key) in forums">
                    <td>{{item.id}}</td>
                    <td>{{item.title}}</td>
                    <td>
                        <b-form-checkbox  v-bind:checked="item.showing ? true : false" :data-id="item.id" class="forums-checkbox" @change="changeShowing(key)" />
                    </td>
                    <td>{{getParent(item.forum_section_id)}}</td>
                    <td>{{item.countTopic}}</td>
                    <td>{{item.countPosts}}</td>
                    <td><b-button variant="success" @click="editGet(key)"><b-icon-vector-pen/></b-button></td>
                    <td><b-button @click="deleteItem(key)" variant="danger"><b-icon-x/></b-button></td>
                </tr>
            </tbody>
        </table>
        <b-alert v-else show variant="warning">Table "Forums" is empty</b-alert>
        <b-modal
            size="md"
            @ok="addSection"
            title="Add new section"
            id="modal-add-group"
        >
            <b-alert
                variant="danger"
                dismissible
                fade
                :show="showDismissibleAlert"
                @dismissed="showDismissibleAlert=false"
            >
                All required fields are not filled
            </b-alert>
            <b-form
                ref="addSection"
                enctype="multipart/form-data"
            >
                <b-form-group
                    label="Title"
                    label-for="title"
                >
                    <b-input id="title" name="title" v-model="sectionForm.title" placeholder="Enter title"></b-input>
                </b-form-group>
                <b-form-group
                    label="Icon"
                    label-for="icon"
                >
                    <b-form-file id="file" accept=".jpg, .jpeg, .svg, .icon, .gif"  name="icon" v-model="sectionForm.file" placeholder="Select icon"/>
                </b-form-group>
            </b-form>
        </b-modal>
        <b-modal
            size="md"
            @ok="addForum"
            title="Add new forum"
            id="modal-add-forum"
        >
            <b-alert
                variant="danger"
                dismissible
                fade
                :show="showDismissibleAlert"
                @dismissed="showDismissibleAlert=false"
            >
                All required fields are not filled
            </b-alert>
            <b-form
                ref="addForum"
                enctype="multipart/form-data"
            >
                <b-form-group
                    label="Title"
                    label-for="title"
                >
                    <b-input id="title" name="title" v-model="form.title" placeholder="Enter title"></b-input>
                </b-form-group>
                <b-form-group
                    label="Section"
                    label-for="section"
                >
                    <select name="parent" id="section" class="form-control">
                        <option value="none" disabled selected>None</option>
                        <option v-for="sect in sections" :value="sect.id">{{sect.title}}</option>
                    </select>
                </b-form-group>
            </b-form>
        </b-modal>
        <b-modal
            size="md"
            ref="editFormModal"
            @ok="editSend"
            title="Edit"
            id="modal-edit"
        >

            <b-form
                ref="editForm"
                enctype="multipart/form-data"
            >
                <b-form-group
                    label="Title"
                    label-for="title"
                >
                    <b-input id="title" name="title" v-model="etitle" placeholder="Enter title"></b-input>
                </b-form-group>
                <input type="hidden" id="editId" name="id" :value="eid">
                <input type="hidden" id="editKey" :value="eKey">
                <input type="hidden" name="_method" value="PUT">
                <b-form-group
                    label="Parent"
                    label-for="parent"
                >
                  <select name="parent" class="form-control" id="parent">
                      <option :value="selectedParent" selected >{{parentName}}</option>
                      <option :value="s.id" v-for="s in sections" v-if="s.id != selectedParent">{{s.title}}</option>
                  </select>
                </b-form-group>
            </b-form>
        </b-modal>
        <b-modal
            size="md"
            ref="editSectionModal"
            @ok="editSectionSend"
            title="Edit section"
            id="modal-edit"
        >

            <b-form
                ref="editSectionForm"
                enctype="multipart/form-data"
            >
                <b-form-group
                    label="Title"
                    label-for="title"
                >
                    <b-input id="title" name="title" v-model="SEtitle" placeholder="Enter title"></b-input>
                </b-form-group>
                <input type="hidden" id="editSectionId" name="id" :value="SEId">
                <input type="hidden" id="editSectionKey" :value="SEKey">
                <input type="hidden" name="_method" value="PUT">
                <b-form-group
                    label="Icon"
                    label-for="icon"
                >
                    <b-form-file id="icon" name="icon"></b-form-file>
                </b-form-group>
            </b-form>
        </b-modal>
        <hr>
        <div>
            <div v-if="sections.length != 0">
                <div class="d-flex justify-content-start">
                    <b-button
                        :class="visible ? null : 'collapsed'"
                        :aria-expanded="visible ? 'true' : 'false'"
                        aria-controls="collapse-4"
                        @click="visible = !visible"
                        variant="primary"
                    >
                        <b-icon-chevron-double-down></b-icon-chevron-double-down>
                        <span class="ml-2">Show all sections</span>
                    </b-button>
                </div>
                <b-collapse id="collapse-4" v-model="visible" class="mt-2">
                    <table v-if="sections.length != 0 && rerender" class="table table-stripped">
                        <thead>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Icon</th>
                        <th title="If checked, it is visible on the site.">Show</th>
                        <th>Edit</th>
                        <th>Delete</th>
                        </thead>
                        <tbody>
                        <tr v-for="(item,key) in sections">
                            <td>{{item.id}}</td>
                            <td>{{item.title}}</td>
                            <td><img style="width: 25px" v-lazy="'/storage/app/public/forums/icons/'+item.icon" alt=""></td>
                            <td>
                                <b-form-checkbox class="section" v-bind:checked="item.showing ? true : false" :data-id="item.id" @change="sectionShowing(key)" />
                            </td>
                            <td><b-button variant="success" @click="editSectionsGet(key)"><b-icon-vector-pen/></b-button></td>
                            <td><b-button @click="deleteSectionItem(key)" variant="danger"><b-icon-x/></b-button></td>
                        </tr>
                        </tbody>
                    </table>
                </b-collapse>
            </div>
            <b-alert v-else show variant="warning">Table "Sections" is empty</b-alert>
        </div>
    </div>
    <div v-else>
        <spinner></spinner>
        <div style="width: 700px">
            <b-skeleton-table
                class="w-100"
                :rows="5"
                :columns="7"
                :table-props="{ bordered: true, striped: true }"
            ></b-skeleton-table>
        </div>
    </div>
</template>

<script>
    import spinner from "../components/Spinner";
    import axios from 'axios';
    export default {
        name: "Forum",
        components:{
            spinner
        },
        data(){
            return {
                loaded: false,
                forums: [],
                SEtitle:"",
                SEId:false,
                SEKey:false,
                showDismissibleAlert: false,
                etitle:"",
                visible: false,
                selectedParent:"",
                parentName:"",
                eKey: null,
                eid: null,
                sections: [],
                rerender: true,
                parent:{
                    title:"",
                    id:"",
                },
                sectionForm:{
                    title:null,
                    file:null
                },
                form:{
                    title:null,
                    file:null,
                }
            }
        },
        methods:{
            getParent(item){
                let ret = null;
                this.sections.forEach(i => {
                    if(item == i.id){
                        ret = i.title;
                        return false;
                    }
                })
                return ret;
            },
            editSectionsGet(key){
                let info = this.sections[key]
                this.SEtitle = info.title;
                this.SEId = info.id;
                this.SEKey = key;
                this.$refs['editSectionModal'].show();
            },
            editSend(){
                let config = { headers: { 'Content-Type': 'multipart/form-data' } }
                let formData = new FormData(this.$refs.editForm);
                let id = document.querySelector("#editId").value;
                axios.post('/forum-edit/'+id, formData, config)
                .then(
                    resp => {
                        if(resp.data.success){
                            this.forums[this.eKey] = resp.data.forum;
                            this.rerender = false;
                            this.$nextTick(()=> {this.rerender = true})
                        }
                        else{
                            alert("Server send error");
                        }
                    }
                )
            },
            editGet(key){
                let info = this.forums[key]
                this.etitle = info.title;
                this.eid = info.id;
                this.eKey = key;
                this.selectedParent = info.forum_section_id;
                this.parentName = this.getParent(info.forum_section_id);
                this.$refs['editFormModal'].show();
            },
            /*
            Send section data to server
             */
            editSectionSend(){
                let config = { headers: { 'Content-Type': 'multipart/form-data' } }
                let formData = new FormData(this.$refs.editSectionForm);
                let id = document.querySelector("#editSectionId").value;
                axios.post('/sections/'+id, formData, config)
                    .then(
                        resp => {
                            console.log(resp.data)
                            if(resp.data.success){
                                this.sections[this.SEKey] = resp.data.section;
                                this.rerender = false;
                                this.$nextTick(()=> {this.rerender = true})
                            }
                            else{
                                alert("Server send error");
                            }
                        }
                    )
            },
            addForum(e){
                if(this.form.title != null) {
                    let formData = new FormData(this.$refs.addForum);
                    let config = {headers: {'Content-Type': 'multipart/form-data'}}
                    axios.post("/forum-add", formData, config)
                        .then(resp => {
                            if(resp.data.success == true){
                                resp.data.forum.countTopic = 0;
                                resp.data.forum.countPosts = 0
                                resp.data.forum.showing = true;
                                this.forums.push(resp.data.forum);
                            }else{
                                alert("Server send error message");
                            }

                        })
                }else{
                    e.preventDefault();
                    this.showDismissibleAlert = true;
                }
            },
            addSection(e){
                if(this.sectionForm.title != null && this.sectionForm.file != null) {
                    let formData = new FormData(this.$refs.addSection);
                    let config = {headers: {'Content-Type': 'multipart/form-data'}}
                    axios.post("/sections", formData, config)
                        .then(resp => {
                            if(resp.data.success == true){
                                this.rerender = false;
                                this.sections.push(resp.data.section);
                                this.$nextTick(()=>{
                                    this.rerender = true;
                                })
                                resp.data.section.showing = true;
                            }else{
                                alert("Server send error message");
                            }

                        })
                }else{
                    e.preventDefault();
                    this.showDismissibleAlert = true;
                }
            },
            changeShowing(key){
                let info = this.forums[key];
                this.forums[key].showing = !this.forums[key].showing;

                let check =  this.forums[key].showing ;
                axios.post("/forum/showing/"+info.id,{
                    'showing': check
                }).then(
                    resp => {
                        if(resp.data.success == false){
                            alert("Server send error")
                        }
                    }
                )
            },
            sectionShowing(key){
                let info = this.sections[key];
                this.sections[key].showing = !this.sections[key].showing;
                let check =  this.sections[key].showing ;
                axios.post("/sections/showing/"+info.id,{
                    'showing': check
                }).then(
                    resp => {
                        if(resp.data.success == false){
                            alert("Server send error")
                        }
                    }
                )
            },
            deleteItem(key){
                let id = this.forums[key].id;
                axios.delete("/forum/"+id)
                .then( resp => {
                    if(resp.data.success){
                        this.forums.splice(key, 1);
                    }
                    else{
                        alert("Server send error");
                    }
                })
            },
            deleteSectionItem(key){
                let id = this.sections[key].id;
                axios.delete("/sections/"+id)
                    .then( resp => {
                        if(resp.data.success){
                            this.sections.splice(key, 1);
                        }
                        else{
                            alert("Server send error");
                        }
                    })
            }
        },
        mounted() {
            axios.get("/sections")
            .then(
                resp =>{
                    this.sections = resp.data;
                }
            )
            axios.get("/forum-get")
            .then(
                resp =>{
                    this.forums = resp.data;
                    this.loaded = true;
                }
            )
        }
    }
</script>

