<template>
    <div class="d-flex flex-column" v-if="rendering">
        <b-modal ref="findUsersModal" hide-footer title="Find user">
            <b-form size="sm" class="d-flex justify-content-center w-100" ref="findUserModal">
                <b-form-input style="width: auto" placeholder="Name" id="userName"></b-form-input>
                <b-button @click.prevent="findUsers">Find users</b-button>
            </b-form>
            <b-form ref="selectUser" class="my-3 d-flex flex-column align0items-center" v-if="findedUser">
                <input type="hidden" v-model="activeProduct" id="activeProduct">
                <select name="user" style="width: auto" id="selectedUser" class="form-control">
                    <option value="none" selected disabled>Select user</option>
                    <option v-for="user in users" :value="user.id">{{ user.name }}</option>
                </select>
                <b-button class="my-2" @click.prevent="changeUser" variant="success">Change</b-button>
            </b-form>
        </b-modal>
        <b-modal size="xl" ref="editModal" @ok="editSend" @close="editCancel" @cancel="editCancel" @hide="editCancel"
                 title="Edit form">
            <b-form ref="editForm">
                <b-form-group
                    label="Title"
                    label-for="title"
                >
                    <b-form-input placeholder="Title" v-model="editForm.title" id="title" name="title"></b-form-input>
                </b-form-group>

                <b-form-group
                    label="Category"
                    label-for="category"
                >
                    <b-form-select v-model="editForm.category_id" :options="optionsCats" id="category"
                                   name="category"></b-form-select>
                </b-form-group>

                <b-form-group
                    label="Tags"
                    label-for="tags"
                >
                    <b-form-input placeholder="Tags" v-model="editForm.tags" id="tags" name="tags"></b-form-input>
                </b-form-group>

                <b-form-group
                    label="Description"
                    label-for="description"
                >
                    <b-form-textarea placeholder="Description" v-model="editForm.description" id="description"
                                     name="description"></b-form-textarea>
                </b-form-group>
                <input type="hidden" name="key" :value="editForm.key">
                <b-form-group
                    label="Version"
                    label-for="version"
                >
                    <b-form-input placeholder="Version" id="version" v-model="editForm.version"
                                  name="version"></b-form-input>
                </b-form-group>
                <b-form-group
                    label="RTX"
                    label-for="rtx"
                >
                    <input type="checkbox" placeholder="RTX" id="rtx" v-model="editForm.rtx" value="rtx"
                           :checked="editForm.rtx" name="rtx"/>
                </b-form-group>
                <b-form-group
                    label="Free"
                    label-for="free"
                >
                    <input type="checkbox" placeholder="Free" id="free" v-model="editForm.is_free" value="free"
                           :checked="editForm.is_free" name="free"/>
                </b-form-group>
                <b-form-group
                    label="Bake "
                    label-for="bake"
                >
                    <input type="checkbox" placeholder="Bake " id="bake" v-model="editForm.bake" value="bake"
                           :checked="editForm.bake" name="bake"/>
                </b-form-group>
                <b-form-group
                    label="Model"
                    label-for="model"
                >
                    <input type="file" id="model" ref="modelFile" webkitdirectory mozdirectory @change="fileSizeCheck"
                           name="model[]">
                </b-form-group>
                <input type="hidden" name="deletedPhotos" v-model="editForm.deletedPhotos">
                <input type="hidden" name="photosOrders" v-model="editForm.photosOrders" v-if="inpRerender">
                <div class="d-flex flex-column w-100 align-items-center">
                    <div
                        class="my-2 w-100 uploaded_photos"
                        v-for="(i,key) in editForm.photos"
                        :key="key"
                        v-if="photosRerender"
                        @drop="DropEvent($event,key)"
                        @dragstart='StartDrag($event, key)'
                        @dragend="DragEnd($event,key)"
                        @dragover="DragOver($event)"
                        @dragover.prevent
                        :style="`order: ${key+1}`"
                        :id="`static__photo-${key}`"
                    >
                        <div class="photo__wrapper uploaded my-2" v-if="editForm.photos != ''">
                            <span class="d-none">+</span>
                            <img :src="'/storage/app/public/models/user-'+editForm.user_id+'/'+i" alt="">
                        </div>
                        <div class="w-100 d-flex justify-content-end">
                            <b-button variant="danger" @click="deletePhoto" :data-key="key" class="ml-auto my-2">Delete
                                photo
                            </b-button>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="user_id" :value="editForm.user_id">
                <input type="hidden" name="id" :value="editForm.id">
                <hr>
                <p class="my-3">New photos:</p>
                <div class="d-flex flex-column w-100">
                    <div
                        @drop="StaticDropEvent(key)"
                        @dragstart='StaticStartDrag($event, key)'
                        @dragend="StaticDragEnd($event,key)"
                        @dragover="StaticDragOver($event)"
                        @dragover.prevent
                        class=" my-2 staticPhoto w-100"
                        :data-key="key"
                        v-if="editForm.addons.length != 0"
                        v-for="(i,key) in editForm.addons"
                        :key="key"
                        :style="`order:${key+1}`"
                    >
                        <div
                            class="photo__wrapper"
                        >
                            <span @click="uploadCardImg">+</span>
                            <img :src="'/storage/app/public/models/user-'+editForm.user_id+'/'+i" class="d-none">
                            <input type="file" name="photos[]" multiple @change="uploadPhoto" class="d-none">
                            <span style="display: none">-</span>
                        </div>
                        <div class="w-100 d-flex justify-content-end">
                            <b-button variant="danger" @click="deleteAddon" :data-key="key" class="ml-auto my-2">Delete
                                photo
                            </b-button>
                        </div>

                    </div>
                </div>
                <b-button @click="morePhoto">Add more photo</b-button>
                <br>
                <hr>
                <b-form-group
                    label="Meta description"
                    label-for="metaDescription"
                >
                    <b-form-textarea placeholder="Description" v-model="editForm.metaDescription" id="metaDescription"
                                     name="metaDescription"></b-form-textarea>
                </b-form-group>
                <b-form-group
                    label="Meta keywords"
                    label-for="metaKeywords"
                >
                    <b-form-textarea placeholder="Keywords" v-model="editForm.metaKeywords" id="metaKeywords"
                                     name="metaKeywords"></b-form-textarea>
                </b-form-group>
            </b-form>

        </b-modal>

        <b-form method="get" class="my-3 d-flex">
            <div class="d-flex justify-content-start">
                <b-form-input name="fuser" :value="this.$route.query.fuser ? this.$route.query.fuser : '' "
                              style="width: auto" type="text" placeholder="Enter User"/>
                &nbsp

                <b-form-select v-model="filterModerate" :options="optionsModerate"></b-form-select>
                &nbsp
                <b-form-select v-model="filterFree" :options="optionsFree"></b-form-select>
                &nbsp
                <b-form-input name="title" :value="this.$route.query.title ? this.$route.query.title : '' "
                              style="width: auto" type="text" placeholder="Enter Title/Tags"/>
                &nbsp

                <input type="hidden" name="filter_moderate" :value="filterModerate">
                <input type="hidden" name="filter_free" :value="filterFree">
                <b-button type="submit" class="mx-2">Search</b-button>
            </div>

        </b-form>
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
            <th>ID</th>
            <th>URL</th>
            <th>User</th>
            <th>Title</th>
            <th>Tags</th>
            <!--       <th>Price</th>-->
            <th>Version</th>
            <th>Get file</th>
            <th>Moderate</th>
            <th>VR</th>
            <th>Free</th>
            <th>Change user</th>
            <th>Edit</th>
            <th>Delete</th>
            </thead>
            <tbody>
            <tr v-for="(model, key) in models">
                <td>{{ model.id }}</td>
                <td>
                    <b-button target="_blank" varian="primary" :href="'/models/'+model.alias">
                        <b-icon-link></b-icon-link>
                    </b-button>
                </td>
                <td><a class="" target="_blank" :href="'/admin/user/'+model.users.id">{{ model.users.name }}</a></td>
                <td>{{ model.title | toLower }}</td>
                <td>{{ model.tags }}</td>
                <!-- <td>{{models[key] && models[key].price ? "NO" : model[key].price}}</td> -->
                <td>{{ JSON.parse(model.props).version }}</td>
                <td>
                    <b-button variant="primary" :href="'/downloads/model/id-'+model.id">
                        <b-icon-upload/>
                    </b-button>
                </td>
                <td>
                    <b-button @click="moderate(model.id, key)" variant="success">
                        <b-icon-check-square-fill v-if="model.moderation == 1"/>
                        <b-icon-check-square v-else/>
                    </b-button>
                </td>
                <td>
                    <b-button
                        variant="warning"
                        @click="change_vr(key,model.id)"
                        :class="[model.is_vr ? '' :  'disabled-free','free']"
                    >
                        VR
                    </b-button>
                </td>
                <td>
                    <b-button
                        variant="warning"
                        @click="change_price_mode(key,model.id)"
                        :class="[model.is_free ? '' :  'disabled-free','free']"
                    >
                        Free
                    </b-button>
                </td>
                <td>
                    <b-button @click="usersForm(model.id, key)" variant="secondary">
                        <b-icon-person-circle/>
                    </b-button>
                </td>
                <td>
                    <b-button @click="editModel(key)" variant="info">
                        <b-icon-vector-pen/>
                    </b-button>
                </td>
                <td>
                    <div>
                        <b-button v-b-modal="'my-modal'+key" variant="danger">
                            <b-icon-x-square></b-icon-x-square>
                        </b-button>

                        <b-modal :id="'my-modal'+key" title="Are you sure?" hide-footer>
                            <p class="my-4">Do you really want delete this model forever?</p>
                            <b-button class="mt-2" block @click="remove(model.id,key);hideModal('my-modal'+key)">
                                Delete
                            </b-button>
                        </b-modal>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        {{ init }}
        <pagination :limit="4" :data="laravelData" @pagination-change-page="getResults">
            <span slot="prev-nav">&lt; Previous</span>
            <span slot="next-nav">Next &gt;</span>
        </pagination>
        <div class="mt-4">
            <hr>
            <b-button v-b-toggle.collapse-1 variant="primary">
                <b-icon-chevron-double-down/>
                <span class="ml-2">Uploads rules</span></b-button>
            <b-collapse id="collapse-1" class="mt-2">

                <div class="py-2">
                    <editor
                        :init="init"
                        name="content"
                        id="content"
                        api-key="ho2xahb38lo8560uw0zpjbbxg62ko9plmy3vt47sex0p2vgj"
                    />

                    <div class="my-3 d-flex justify-content-end">
                        <b-button variant="success" @click="saveRules('/products/save-rules')">
                            <b-icon-download/>
                            <span class="ml-2 m-3">Save rules</span></b-button>
                    </div>
                </div>
            </b-collapse>
        </div>
        <input type="file" id="tmp_images" class="d-none">
    </div>
    <spinner v-else></spinner>
</template>

<script>
import axios from "axios"
import spinner from "../components/Spinner";
import pagination from 'laravel-vue-pagination';
import editor from '@tinymce/tinymce-vue'
import {rules} from "../mixins/rules-mixin"
import {uploadPhoto} from "../mixins/upload-photo"

const select = () => import("../../components/Select/Select")

export default {
    name: "Products",
    mixins: [rules, uploadPhoto],
    data() {
        return {
            optionsCats: [],
            categoriesList: [],
            filterModerate: this.$route.query.filter_moderate ? this.$route.query.filter_moderate : null,
            filterFree: this.$route.query.filter_free ? this.$route.query.filter_free : null,
            optionsModerate: [
                {value: null, text: 'Please select Moderate'},
                {value: '1', text: 'Moderate'},
                {value: '2', text: 'No moderate'},
            ],
            optionsFree: [
                {value: null, text: 'Please select Free'},
                {value: '1', text: 'Free'},
                {value: '2', text: 'Pro'},
            ],
            rendering: false,
            models: [],
            init: {

                height: 500,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount',
                    'link image code preview imagetools table lists textcolor hr wordcount'
                ],
                init_instance_callback: (item) => {
                    item.setContent(this.rules);
                },
                toolbar: 'undo redo | styleselect | forecolor | bold italic underline | alignleft aligncenter alignright alignjustify | outdent indent | link'

            },
            modelsRules: "",
            activeProduct: null,
            findUser: false,
            users: [],
            activeKey: 0,
            fileSystem: [],
            modelName: "",
            findedUser: false,
            editForm: {
                "category_id": "",
                'title': "",
                'deletedPhotos': [],
                "id": "",
                photosOrders: [],
                "rtx": false,
                "bake": false,
                "user_id": "",
                "tags": "",
                "is_free": false,
                "metaDescription": "",
                "metaKeywords": "",
                'photos': "",
                'description': "",
                "version": "",
                'addons': [],
            },
            editFormModel: {
                "category_id": "",
                'title': "",
                'deletedPhotos': [],
                "id": "",
                "rtx": false,
                "bake": false,
                "user_id": "",
                "metaDescription": "",
                "metaKeywords": "",
                photosOrders: [],
                "tags": "",
                "is_free": false,
                'photos': "",
                'description': "",
                "version": "",
                'addons': [],
            },
            laravelData: null,
        }
    },
    filters: {
        toLower(value) {
            return value.toLowerCase();
        }
    },
    methods: {
        categoriesEvent(data) {

            let categories = data.categories
            console.log(categories)
            /*let subcategories = this.allCategories.filter(
                item => {
                    let par = item.parent
                    return categories.indexOf(item.parent) != -1
                }
            )*/

            this.subcategoriesBool = false
            this.subcategories = subcategories
            if (categories.length == 0)
                this.selectedSubcategories = []
            this.$nextTick().then(
                () => {
                    this.subcategoriesBool = true
                }
            )
        },
        fileSizeCheck(e) {
            let files = this.$refs.modelFile.files;
            let totalSize = 0;
            let error = true;
            let directory = files[0].webkitRelativePath.split("/")[0]
            let filesystemStructure = [];
            if (files.length != 0) {
                for (let item of files) {
                    totalSize += item.size
                    let file_dir = item.webkitRelativePath.split("/")
                    file_dir = file_dir.filter(el => {
                        if (el != directory && el != item.name)
                            return el
                    })
                    filesystemStructure.push({
                        "fileName": item.name,
                        "directoriesStructure": file_dir.length == 0 ? null : file_dir.join("/")
                    })
                }

                console.clear()
                console.info(filesystemStructure)

                if (totalSize != 0 && totalSize <= 300002000) {
                    error = false
                }
            } else {
                alert("The folder is empty")
            }
            if (totalSize == 0 && files.length != 0) {
                alert("The folder weighs nothing")
            }
            if (totalSize >= 300002000) {
                alert("File size exceeded.")
            }
            if (error) {
                e.target.value = null
            } else {
                this.modelName = directory;
                this.fileSystem = filesystemStructure
            }
        },
        moderate(id, key) {
            this.models[key].moderation = !this.models[key].moderation
            axios.post("/products/moderate/" + id)
                .then(resp => {

                })
        },
        morePhoto() {
            this.editForm.addons.push("ITEM")
        },
        changeUser() {
            let user = document.querySelector("#selectedUser").value
            let product = this.activeProduct;
            axios.post("/product/change-user",
                {
                    'user': user,
                    'product': product,
                })
                .then(
                    resp => {
                        console.clear()
                        console.log(resp)
                        this.rendering = false
                        this.users.forEach(item => {
                            if (item.id == user)
                                user = item
                        })
                        this.models[this.activeKey].users = user
                        this.$nextTick(() => {
                            this.rendering = true
                        })

                    }
                )
        },
        usersForm(id, key) {
            this.activeKey = key;
            this.findUser = true;
            this.activeProduct = id
            this.$refs['findUsersModal'].show()
        },
        findUsers() {
            axios.get("/users/?name=" + document.querySelector("#userName").value)
                .then(
                    resp => {
                        if (resp.status == 200 && resp.data.length != 0) {
                            this.findedUser = false;
                            this.users = resp.data;
                            this.findedUser = true;
                        } else {
                            alert("Users not found")
                        }
                    }
                )
        },
        editSend() {
            let form = new FormData(this.$refs.editForm);
            let config = {headers: {'Content-Type': 'multipart/form-data'}}
            form.append("folderName", this.modelName)
            form.append("fileSystemStructure", JSON.stringify(this.fileSystem))
            form.append("photosPositions", JSON.stringify(this.editForm.photosOrders))
            let meta = {
                "keywords": this.editForm.metaKeywords,
                "description": this.editForm.metaDescription,
            }
            form.append("meta", JSON.stringify(meta))
            // form.set("product.category_id", this.editForm.categoryId)
            this.rendering = false
            console.log(form.get('product'))
            axios.post("/admin/products/edit", form, config)
                .then(
                    resp => {
                        if (resp.status == 200) {
                            let model = resp.data.product;
                            let user = this.models[this.activeKey].users
                            this.editForm = this.editFormModel
                            this.models[this.activeKey] = model
                            this.models[this.activeKey].is_free = model.is_free
                            this.models[this.activeKey].users = user
                            this.$nextTick(() => {
                                this.rendering = true
                            })
                        } else {
                            alert("Error")
                            console.log(resp)
                        }
                    }
                )
        },
        uploadCardImg(e) {
            e.preventDefault();
            console.log(e.target.parentElement.children[2])
            e.target.parentElement.children[2].click()
        },
        editModel(key) {
            this.activeKey = key;
            let info = this.models[key];
            let metaData = info.meta != null ? JSON.parse(info.meta) : {
                keywords: "",
                description: ""
            };
            let props = JSON.parse(info.props);
            this.editForm.category_id = info.category_id
            this.editForm.title = info.title
            this.editForm.tags = info.tags
            this.editForm.key = key;
            this.editForm.metaKeywords = metaData.keywords
            this.editForm.metaDescription = metaData.description
            this.editForm.rtx = props.rtx == true ? 1 : 0;
            this.editForm.bake = props.bake
            this.editForm.user_id = info.user_id
            this.editForm.is_free = info.is_free
            this.editForm.photos = JSON.parse(info.photos)
            this.editForm.photosOrders = this.editForm.photos
            this.editForm.id = info.id
            this.editForm.description = props.description
            this.editForm.version = props.version
            this.$refs['editModal'].show();
            console.log(this.activeKey)
            console.log()
        },

        editCancel() {
            this.editForm = this.editFormModel;
            document.querySelector("#tmp_images").value = null
        },
        deleteAddon(e) {
            let key = e.target.getAttribute("data-key")
            this.editForm.addons.slice(key, 1)
            e.target.parentElement.parentElement.remove();

        },
        deletePhoto(e) {
            let key = e.target.getAttribute("data-key")
            let photoName = this.editForm.photos[key]
            this.editForm.photos.slice(key, 1)
            this.editForm.deletedPhotos.push(photoName);
            e.target.parentElement.parentElement.remove();

        },
        editData(e) {
            e.preventDefault();
        },
        hideModal(id) {
            this.$root.$emit('bv::hide::modal', id)
        },
        getFile(id) {
            axios.post('/get-admin-link', {id: id, responseType: 'blob',})
                .then(
                    resp => {
                        console.log(resp)
                        const downloadUrl = window.URL.createObjectURL(new Blob([resp.data]))
                        const link = document.createElement('a');

                        link.href = downloadUrl;

                        link.setAttribute('download', 'file.zip'); //any other extension

                        document.body.appendChild(link);

                        link.click();

                        link.remove();
                    }
                )
        },
        getToast(title, text) {
            this.$bvToast.toast(text, {
                title: title,
                variant: "danger",
                solid: true,
                toaster: "b-toaster-bottom-right"
            })
        },
        getResults(page = 1) {
            let params = '';

            let fTitle = this.$route.query.title;
            params += fTitle ? '&title=' + fTitle : '';

            let fUser = this.$route.query.fuser;
            params += fUser ? '&fuser=' + fUser : '';


            let fModerate = this.$route.query.filter_moderate;
            params += fModerate ? '&filter_moderate=' + fModerate : '';

            let fFree = this.$route.query.filter_free;
            params += fFree ? '&filter_free=' + fFree : '';

            axios.get('/products-admin/?page=' + page + params)
                .then(response => {
                    console.log(response)
                    this.models = response.data.data;
                    this.laravelData = response.data;
                });
        },
        remove(id, key) {
            this.models.splice(key, 1);
            axios.delete("/products/" + id)
                .then(
                    resp => {
                        // if(resp.success == true){
                        //     this.models.splice(key,1);
                        // }
                        console.log(resp)
                    }
                )
        },
        change_price_mode(key, id) {
            if (this.models[key].is_vr) {
                alert('cannot be switched when vr is enabled');
                return false;
            }
            axios.post("/product/change-mode", {
                "id": id,
                "is_free": !this.models[key].is_free
            })
                .then(
                    resp => {
                        this.models[key].is_free = !this.models[key].is_free
                    }
                )
        },
        change_vr(key, id) {
            console.log(this.models[key])
            if (this.models[key].is_free) {
                alert('cannot be switched when free is enabled');
                return false;
            }
            axios.post("/product/change-vr", {
                "id": id,
                "is_vr": !this.models[key].is_vr
            })
                .then(
                    resp => {
                        this.models[key].is_vr = !this.models[key].is_vr
                    }
                )
        },
    },
    components: {
        spinner,
        pagination,
        editor
    },
    mounted() {
        axios.get("/categories-get")
            .then(resp => {
                let cats = resp.data

                // this.allCategories = resp.data
                // let cats = [];
                cats.forEach(
                    (item, index) => {
                        if (item.parent != null) {
                            this.optionsCats.push({
                                "text": item.name,
                                "value": item.id
                            })
                        }
                    }
                )
                console.log(this.optionsCats)
                // this.categoriesList = cats;
                // this.loaded = true

            })

        this.getResults();
        axios.get("/api/products/get-rules")
            .then(
                resp => {
                    this.rules = resp.data.rules ? JSON.parse(resp.data.rules) : []
                }
            )
        this.rendering = true
    }
}
</script>

<style>
.photo__wrapper {
    width: 100%;
    border: 1px solid gray;
    border-radius: 5px;
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    user-select: none;
}

.photo__wrapper span:nth-child(2) {
    transition: .3s linear;
    opacity: 0;
    top: 42%;
    left: 42%;
    position: absolute;
}

.photo__wrapper.uploaded span:nth-child(2) {
    opacity: 1;
    display: block !important;
}

.photo__wrapper img {
    max-width: 100%;
}

.photo__wrapper span {
    font-size: 35px;
    color: gray;
    font-family: Arial;
    transition: .3s linear;
    cursor: pointer;
}

.photo__wrapper span:hover {
    opacity: .75;
}

.free {
    font-size: 14px !important;
}

.disabled-free {
    opacity: .5;
}

.custom-select option {
    color: #000;
}
</style>
