<template>
    <form  ref="model"  v-if="loaded" enctype="multipart/form-data">
        <div class="container">
            <p class="breadcrumbs">
                <a href="/" style="color: rgb(179, 179, 179); text-decoration: none;">UnrealShop</a>
                >
                <a href="/store" style="color: rgb(179, 179, 179); text-decoration: none;">Models</a>
                > Upload</p>
        </div>
        <div class="d-flex content-right double-column">
            <div class="container mt-4">
                <div class="d-flex  flex-column">
                    <p class="subtags text-orange">
                        Title:
                        <input name="title" v-model="cardTitle" placeholder="Please enter name" v-bind:class="{ 'red-bottom': titleTrue }" type="text" @keypress="inpFilter">
                    </p>
                    <p class="subtags text-orange">
                        Tags:
                        <input name="tags" type="text" v-model="tags" placeholder="Please enter tags" v-bind:class="{ 'red-bottom': tagTrue }" @keypress="inpFilterTags">
                        <!--            <input name="tags" type="text" v-model="tags" placeholder="Please enter tags" v-bind:class="{ 'red-bottom': tagTrue }">-->
                    </p>
                    <div class="d-flex mt-4">
                        <galcard

                            v-if="cardLoaded"
                            :image="cardImg"
                            :card-title="cardTitle == '' ? 'This will be the name of the card' : cardTitle "
                            :author="userInfo.name"
                            :authorName="userInfo.name"
                            ratingPlus="0"
                            ratingMinus="0"
                        />

                        <div
                            v-if="rules.length != 0 && rules != null"
                            class="rules block-fc"
                            v-html="rules"
                        />
                    </div>
                    <div class="d-flex w-100 mt-5">
                        <div class="d-flex flex-column upload-model">
                            <div @click="uplModel" class=" upload-model__inner shadow-standart  d-flex justify-content-center align-items-center"  v-bind:class="{ 'red-box': modelTrue }">
                                <span v-if="!model">+</span>
                                <span v-else :title="modelName"><b-icon-file-earmark-zip-fill /></span>
                                <input type="file"  ref="modelFile" webkitdirectory mozdirectory  name="modelFile[]" @change="fileSizeCheck" class="d-none" id="uploadModel"  >
                            </div>
                            <div class="d-flex justify-content-end mt-4" @click.prevent="uplModel"><button class="btn btn-red sm">Upload model</button></div>
                        </div>
                        <div>
                            <div class="border-standart models-details block-fc p-3" style="min-height: 330px">
                                <div class="d-flex">
                                    <div class="d-flex flex-column">
                                        <p class="text-orange">Version Unreal Engine</p>
                                        <input type="text" name="version" v-model="version" v-bind:class="{ 'red-bottom': versionTrue }" class="border-none uploading" placeholder="Enter UE version here">
                                    </div>
                                    <div class="d-flex flex-column">
                                        <div class="d-flex text-gray"><label for="">RTX</label><input type="checkbox" class="form-control mx-2" name="rtx" value="rtx" id=""></div>
                                        <div class="d-flex text-gray mt-2"><label for="">Bake </label><input type="checkbox" class="form-control mx-2" value="bake" name="bake" id=""></div>
                                        <div class="d-flex text-gray mt-2"><label for="">VR </label><input type="checkbox" class="form-control mx-2" value="vr" name="vr" id=""></div>
                                        <div class="d-flex text-gray mt-2"><label for="">Lumen </label><input type="checkbox" class="form-control mx-2" value="lumen" name="lumen" id=""></div>
                                    </div>
                                </div>
                                <br>
                                <b-form-group
                                    label="Description"
                                    label-class="text-orange"
                                    label-for="description"
                                >
                                <textarea id="description" v-model="description" name="description" v-bind:class="{ 'red-bottom': descriptionTrue }" placeholder="Enter description here">

                                </textarea>
                                </b-form-group>
                                <p class="text-orange">Category</p>
                                <div class="d-flex w-75 justify-content-enaround mt-3">
                                    <custom-select
                                        v-bind:class="{ 'red-box': categoryTrue }"
                                        :select-data="allCategories"
                                        select-name="categories"
                                        @on-select="categoriesEvent"
                                        select-placeholder="Categories"
                                    />
                                    <!-- <custom-select
                                        :select-data="subcategories"
                                        select-name="subcategories"
                                        v-if="subcategoriesBool"
                                        class-list="mx-3"
                                        :previos-selected="selectedSubcategories"
                                        select-placeholder="Subcategories"
                                        @on-select="selectSubcategories"
                                    /> -->
                                    <!-- <multi-select
                                       select-options="categories"
                                       emptyTabText="Category"
                                       v-model="categories"
                                       :options="options"
                                       :selectOptions="categoriesList"
                                       labelName="Search"
                                       :btnLabel="catsLabel"
                                       @selectionChanged="subcatsEvent"
                                   />
                                   <multi-select
                                       select-options="subcategories"
                                       emptyTabText="Subcategory"
                                       v-model="subcategories"
                                       :options="options"
                                       v-if="subcategoriesBool"
                                       id="subcategories"
                                       :selectOptions="subCategoriesList"
                                       labelName="Search"
                                       :btnLabel="btnLabel"
                                   /> -->

                                </div>
                            </div>
                            <div class="d-lg-flex d-md-flex d-none mt-4 justify-content-end">
                                <button class="btn btn-red sm" @click.prevent="addModel">Send</button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex d-lg-none d-md-none d-block justify-content-end mt-4 send-model-wrapper">
                        <button class="btn btn-red sm" @click.prevent="addModel">Send</button>
                    </div>
                    <p class="block-title bold cr mt-big mb-0 tr-none">{{cardTitle == '' ? 'This will be the title' : cardTitle }}</p>
                    <input type="hidden" name="api_token" :value="this.$store.getters.userToken">
                    <div class="d-flex justify-content-end"><div class="d-flex align-items-end"><div class="d-flex plus"><span>00</span> <div class="plus__icon"><span>+</span></div></div> <div class="d-flex minus"><span>00</span> <div class="minus__icon"><span>-</span></div></div></div></div>
                    <div class="images-controllers position-relative mt-3 d-flex flex-column position-relative">
                        <author
                            :rank="userInfo.rang.name"
                            :photo="userInfo.photo"
                            :name="userInfo.name"
                        />
                        <div
                            v-bind:class="{ 'red-box': imgTrue }"
                            class="image image-control"
                            v-for="(item,key) in images"
                            draggable
                            :key="key"
                            @drop="dropEvent"
                            @dragstart='startDrag($event, key)'
                            @dragend="dragEnd()"

                            @dragover="dragOver($event)"
                            @dragover.prevent
                            :data-key="key"
                            :style="'order:'+(key+1)"
                        >
                            <button class="add"  @click.prevent="uploadCardImg">+</button>
                            <img data-type="first" src="#" class="d-none background-photo" alt="" v-if="item.id == 1">
                            <img data-type="second" src="#" class="d-none background-photo" alt="" v-else-if="item.id == 2">
                            <img src="#" class="d-none background-photo" alt="" v-else>
                            <input accept=".gif, .jpeg, .png, .jpg" name="photo[]" type="file" @change="uploadPhoto" class="d-none file" multiple>
                            <div class="background"></div>
                            <button class="delete" @click.prevent="clearFile"><img src="/img/icons/delete.svg" alt=""></button>
                        </div>

                        <div class="d-flex justify-content-between w-100 my-3" style="order: 1000">
                            <button class="btn btn-red btn-sm" style="width: fit-content; padding-left: 10px; padding-right: 10px;" @click.prevent="more">Add more photo</button>
                            <button class="btn btn-red btn-sm" @click.prevent="addModel">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="progress__bar-wrapper" v-if="preloader">
            <div class="progress__bar" >
                <p class="text-center">Model is uploading on server</p>
                <b-progress :value="progress" variant="dark" :max="100" :animated="animate" class="mt-3"></b-progress>
            </div>
        </div>
        <input type="file" multiple id="tmp_images" class="d-none">
        <b-modal ref="errorModal" title="Warning" hide-footer>
            <p> Fill in all the fields</p>
        </b-modal>


    </form>
</template>

<script>
const galcard = () => import("../components/SimpleCards/SimpleCard")

const author = ()  => import("../components/Author")
const preloader = () => import("../components/preloader");
import vueMultiSelect from 'vue-multi-select';
import 'vue-multi-select/dist/lib/vue-multi-select.css';
const select = () => import("../components/Select/Select.vue")
import {filters} from "../Mixins/filters.js"

export default {
    name: "uploadModel",
    mixins:[filters],
    components:{
        galcard,
        author,
        preloader,
        "multi-select":vueMultiSelect,
        "custom-select":select,
    },
    props:["userInfo"],
    data(){
        return{
            allCategories: [],
            categoriesList:[],
            //----
            cardTitle:"",
            selectedSubcategories:[],
            animate: true,
            tags:"",
            rules:[],
            selectedCategory: "none",
            subcategories:[],
            subcategoriesBool: true,
            categories:[],
            preloader: false,
            imgSrc:"",
            cardLoaded:true,
            btnLabel: values => `Subcategories (${values.length})`,
            catsLabel: values => `Categories (${values.length})`,
            options:{
                "multi": true,
                "labelValue":"asd",
                "btnLabel":"32"
            },
            loaded: false,
            fileSystem: null,
            isDragging: false,
            order: null,
            images:[
                {
                    src:"",
                    id:1,
                },
                {
                    src: "",
                    id: 2,
                },
                {
                    src:""
                },
            ],
            cardImg:[],
            modelName:"",
            model:false,
            description:"",
            version:"",
            dragKey: null,
            progress: 0,
            categoryTrue: 0,
            titleTrue: 0,
            modelTrue: 0,
            tagTrue: 0,
            imgTrue: 0,
            versionTrue: 0,
            descriptionTrue: 0,
            photoTrue: 0
        }
    },
    methods:{
        dragOver(e){
            e.target.classList.add("dragOver")
        },
        selectSubcategories(data){
            this.selectedSubcategories = data.categories
        },
        categoriesEvent(data){
            let categories = data.categories
            let subcategories = this.allCategories.filter(
                item => {
                    let par = item.parent
                    return categories.indexOf(item.parent) != -1
                }
            )

            this.subcategoriesBool = false
            this.subcategories = subcategories
            if(categories.length == 0)
                this.selectedSubcategories = []
            this.$nextTick().then(
                () => {
                    this.subcategoriesBool = true
                }
            )
        },
        changeMainPhoto(){
            setTimeout(()=>{
                let first_item = $('.image-control[style*="order: 1"]');
                console.log(($('.first_item')));
                first_item.prependTo($('.images-controllers'))
                let first_img = $('.image-control[style*="order: 1"]')[0].children[1].getAttribute("src")
                first_img = first_img == "#" ? [] : first_img;
                this.cardImg = first_img
            },1000)
        },
        dropEvent(e){
            if(this.isDragging){
                let target = e.target.classList.contains("image-control")
                let newOrder,dragKey;
                if(target){
                    newOrder = e.target.style.order
                    dragKey = e.target.getAttribute("data-key")
                }
                else{
                    newOrder = e.target.parentElement.style.order
                    dragKey = e.target.parentElement.getAttribute("data-key")
                }
                console.log(dragKey);
                document.querySelector(`.image-control[data-key="${this.dragKey}"]`).style.order = newOrder
                document.querySelector(`.image-control[data-key="${dragKey}"]`).style.order = this.order
                this.changeMainPhoto();
                if(newOrder == 1)
                {
                    let qwer = ($('.image-control'))
                    for (var key in qwer) {
                        if($(qwer[key]).attr('style') == "order: 1;")
                        {
                            this.cardImg = ($(qwer[key]).children('img').attr('src'));
                            break;
                        }
                    }

                }
            }else{
                e.preventDefault()
                const image = e.dataTransfer.files;
                let inp = e.target.tagName == "BUTTON" ? e.target.parentElement.children[2] : e.target.children[2]
                if(image.length < 2){
                    let type = image[0].type;
                    if(type == "image/png" || type == "image/jpg" || type == "image/gif" || type == "image/png" || type == "image/jpeg"){
                        inp.files = image
                        const reader = new FileReader();
                        reader.readAsDataURL(image[0]);
                        let parent = inp.parentElement;
                        let img = parent.children[1];
                        reader.onload = e =>{
                            let imgSrc = e.target.result;
                            img.classList.remove("d-none")
                            img.classList.add("showingBg")
                            parent.children[0].classList.add("d-none")
                            img.setAttribute("src",imgSrc)
                            if(img.getAttribute("data-type") && img.getAttribute("data-type") == "first"){
                                this.cardImg =imgSrc
                                this.cardLoaded = false;
                                this.$nextTick(()=>{this.cardLoaded = true})
                            }
                        };
                        inp.parentElement.classList.add("image-delete")
                    }
                }
                else{
                    function clearedFileInputs(){
                        let arr = []
                        Array.prototype.forEach.call(document.querySelectorAll(`input[name="photo[]"]`),
                            item => {
                                if(item.files.length == 0)
                                    arr.push(item)
                            }
                        )
                        return arr
                    }
                    function uplImages(){
                        Array.prototype.forEach.call(image,
                            (file,key) => {
                                let type = file.type;
                                if(type == "image/png" || type == "image/jpg" || type == "image/gif" || type == "image/png" || type == "image/jpeg"){
                                    let dt = new DataTransfer();
                                    dt.items.add(file);
                                    let inp = clearedInputs[key]; //---
                                    inp.files = dt.files
                                    let reader = new FileReader();
                                    reader.readAsDataURL(file);
                                    let parent = inp.parentElement;
                                    let img = parent.children[1];
                                    reader.onload = e =>{
                                        let imgSrc = e.target.result;
                                        img.classList.remove("d-none")
                                        img.classList.add("showingBg")
                                        parent.children[0].classList.add("d-none")
                                        img.setAttribute("src",imgSrc)
                                        if(img.getAttribute("data-type") && img.getAttribute("data-type") == "first"){
                                            this.cardImg =imgSrc
                                            this.cardLoaded = false;
                                            this.$nextTick(()=>{this.cardLoaded = true})
                                        }
                                    };
                                    parent.classList.add("image-delete")
                                }
                            }
                        )
                    }
                    let clearedInputs = clearedFileInputs();
                    let img_lg = image.length;
                    if(clearedInputs.length < img_lg){
                        let numb = img_lg - clearedInputs.length;
                        for(let i = 0; i< numb; i++){
                            this.more();
                        }
                        setTimeout(()=>{
                            clearedInputs = clearedFileInputs();
                            uplImages()
                        },500)
                    }
                    if(clearedInputs.length >= img_lg){
                        uplImages()
                    }
                }
                this.changeMainPhoto()
            }
        },
        firstEmpty(){
            let arr = [];
            document.querySelectorAll(`input[name="photo[]"]`).forEach(
                item => {
                    if(item.files.length == 0)
                        arr.push(item)
                }
            )
            return arr[0]
        },
        addModel(e){
            e.preventDefault();

            this.categoryTrue = 0;
            this.descriptionTrue = 0;
            this.versionTrue = 0;
            this.titleTrue = 0;
            this.tagTrue = 0;
            this.modelTrue = 0;
            this.imgTrue = 0;

            let categories = document.querySelector(`input[name="categories"]`).value
            let subcategories = document.querySelector(`input[name="subcategories"]`).value
            let formData = new FormData(this.$refs.model)

            if(subcategories != "" && categories != ""  && this.description!= "" && this.version != "" && this.cardTitle != "" && this.modelName != "" && this.tags != "" && this.cardImg != null && this.cardImg.length != 0){
                this.preloader = true;
                let formData = new FormData(this.$refs.model)
                formData.append("folderName",this.modelName)
                formData.append("fileSystemStructure",JSON.stringify(this.fileSystem))
                let config = {
                    headers: { 'Content-Type': 'multipart/form-data' },
                    onUploadProgress: e => {
                        this.progress = Math.min(Math.round(e.loaded * 100 / e.total), 99);
                    }
                }
                window.axios.post('/products', formData, config).then( resp => {
                        this.preloader = false;
                        if(resp.status == 200){
                            window.location.href = '/cabinet/models';
                        }
                    }
                )
            }else{
                if (categories == '')
                {
                    this.categoryTrue = 1;
                }

                if (this.description == '')
                {
                    this.descriptionTrue = 1;
                }

                if (this.version == '')
                {
                    this.versionTrue = 1;
                }

                if (this.cardTitle == '')
                {
                    this.titleTrue = 1;
                }

                if (this.tags == '')
                {
                    this.tagTrue = 1;
                }

                if (this.modelName == '')
                {
                    this.modelTrue = 1;
                }

                if (this.cardImg.length == 0)
                {
                    this.imgTrue = 1;
                }

                this.$refs["errorModal"].show();
            }
        },
        clearFile(e){
            e.target.parentElement.parentElement.classList.remove("image-delete");
            e.target.parentElement.parentElement.children[1].classList.add("d-none");
            e.target.parentElement.parentElement.children[1].classList.remove("showingBg");
            e.target.parentElement.parentElement.children[1].setAttribute("src",null);
            e.target.parentElement.parentElement.children[2].value = null;
            e.target.parentElement.parentElement.children[0].classList.remove("d-none")
            this.dragLeave();
        },
        more(){
            if(this.images.length < 9)
                this.images.push({src:""})
        },
        fileSizeCheck(e){
            let files = this.$refs.modelFile.files;
            let totalSize = 0;
            let error = true;
            let directory = files[0].webkitRelativePath.split("/")[0]
            let filesystemStructure = [];
            if(files.length != 0){
                Array.prototype.forEach.call(files, item => {
                        totalSize += item.size
                        let file_dir = item.webkitRelativePath.split("/")
                        file_dir = file_dir.filter(el => {
                            if(el != directory && el != item.name)
                                return el
                        })
                        filesystemStructure.push({
                            "fileName":item.name,
                            "directoriesStructure":file_dir.length == 0 ? null : file_dir.join("/")
                        })
                    }
                )
                console.log(totalSize)

                if(totalSize < 300002000 && totalSize !=0){
                    error = false
                }
            }else{
                alert("The folder is empty")
            }
            if(totalSize == 0 && files.length != 0){
                alert("The folder weighs nothing")
            }
            if(totalSize >= 300002000){
                alert("File size exceeded.")
            }
            if(error){
                this.models = false
                e.target.value = null
            }else{
                this.model = true
                this.modelName = directory;
                this.fileSystem = filesystemStructure
            }
        },
        uploadCardImg(e){
            e.preventDefault();
            e.target.parentElement.children[2].click()
        },
        uplModel(){
            document.querySelector("#uploadModel").click();
        },
        dragStart(e){
            console.log(e)
        },
        dragLeave(){
            document.querySelectorAll(".image")
                .forEach(
                    item => {
                        item.classList.remove("dragEffect")
                    }
                )
        },
        uploadPhoto(e){
            let image = e.target.files;
            if(image.length == 1){
                image = image[0]
                const reader = new FileReader();
                reader.readAsDataURL(image);
                let parent = e.target.parentElement;
                let img = parent.children[1];
                reader.onload = e =>{
                    let imgSrc = e.target.result;
                    img.classList.remove("d-none")
                    img.classList.add("showingBg")
                    parent.children[0].classList.add("d-none")
                    img.setAttribute("src",imgSrc)
                    if(img.getAttribute("data-type") && img.getAttribute("data-type") == "first"){
                        this.cardImg =imgSrc
                        this.cardLoaded = false;
                        this.$nextTick(()=>{this.cardLoaded = true})
                    }
                };
                e.target.parentElement.classList.add("image-delete")
            }else{
                e.preventDefault()
                function clearedFileInputs(){
                    let arr = []
                    Array.prototype.forEach.call(document.querySelectorAll(`input[name="photo[]"]`),
                        item => {
                            if(item.files.length == 0)
                                arr.push(item)
                        }
                    )
                    return arr
                }
                function uplImages(){
                    console.warn(images)
                    Array.prototype.forEach.call(images,(file,key) => {
                            let type = file.type;
                            if(type == "image/png" || type == "image/jpg" || type == "image/gif" || type == "image/png" || type == "image/jpeg"){
                                let dt = new DataTransfer();
                                dt.items.add(file);
                                let inp = clearedInputs[key]; //---
                                inp.files = dt.files
                                let reader = new FileReader();
                                reader.readAsDataURL(file);
                                let parent = inp.parentElement;
                                let img = parent.children[1];
                                reader.onload = e =>{
                                    let imgSrc = e.target.result;
                                    img.classList.remove("d-none")
                                    img.classList.add("showingBg")
                                    parent.children[0].classList.add("d-none")
                                    img.setAttribute("src",imgSrc)
                                    this.cardImg = document.querySelectorAll(".image")[0].children[1].getAttribute("src")
                                    if(img.getAttribute("data-type") && img.getAttribute("data-type") == "first"){
                                        this.cardImg =imgSrc
                                        this.cardLoaded = false;
                                        this.$nextTick(()=>{this.cardLoaded = true})
                                    }
                                };
                                parent.classList.add("image-delete")
                            }
                        }
                    )
                }
                let dt = new DataTransfer();
                Array.prototype.forEach.call(image, file=>{
                        dt.items.add(file);
                    }
                )
                image = dt.files;
                document.querySelector("#tmp_images").files = image

                let images = document.querySelector("#tmp_images").files;
                e.target.value = null
                console.warn(image)
                let clearedInputs = clearedFileInputs();
                let img_lg = images.length;
                console.warn(images)
                if(clearedInputs.length < img_lg){
                    let numb = img_lg - clearedInputs.length;
                    for(let i = 0; i< numb; i++){
                        this.more();
                    }
                    setTimeout(()=>{
                        clearedInputs = clearedFileInputs();
                        uplImages()
                    },500)
                }
                if(clearedInputs.length >= img_lg){
                    uplImages()
                }
                // document.querySelector("#tmp_images").value = "";
            }
            this.changeMainPhoto()

        },
        startDrag(e){
            this.order = e.target.style.order
            this.dataKey = e.target.getAttribute('data-key')
            this.isDragging = true
            e.target.classList.add("dragged")
            this.dragKey = e.target.getAttribute("data-key")
        },
        dragEnd(){
            Array.prototype.forEach.call(
                document.querySelectorAll(".image-control"),
                item => {
                    item.classList.remove("dragged")
                }
            )
            Array.prototype.forEach.call(
                document.querySelectorAll(".dragOver"),
                item => {
                    item.classList.remove("dragOver")
                }
            )
            this.isDragging = false
            this.order = null
        }

    },
    mounted() {
        window.axios.get("/categories-get")
            .then(resp =>{
                let categories = resp.data
                this.allCategories = resp.data
                let cats = [];
                categories.forEach(
                    (item,index) => {
                        if(item.parent == null){
                            cats.push({
                                "id":item.id,
                                "name":item.name,
                                "value":item.id
                            })
                        }

                    }
                )
                this.categoriesList = cats;
                this.loaded = true

            })
        window.axios.get("/api/products/get-rules")
            .then(
                resp => {
                    this.rules = resp.data.rules ? JSON.parse(resp.data.rules) : null
                }
            )
    },

}
</script>


<style scoped>
.subtags input{
    border: none;
    margin-left: 10px;
    border-bottom: 1px dotted #b3b3b3;
    outline: none!important;
}
.image-control.dragged{
    border: 2px solid rgba(0,0,0, .3);
}
.form-group label.text-orange{
    color: #ff8767!important;
}
.dragOver{
    opacity: .75;
}
.image-control{
    transition: .3s linear;
}
#description,#description::placeholder{
    box-shadow: none;
    outline: none;
    font-family: Candara;
    font-weight: bold;
    color: #B3B3B3;
    border: none;
    resize: vertical;
    width: 50%;
}
.subtags input::placeholder{
    opacity: .5;
}
.upload-model__inner label {
    font-size: 107px;
    color: #b3b3b3;
    font-family: Candara;
    -webkit-transition: .3s linear;
    transition: .3s linear;
    cursor: pointer;
}
.progress__bar{
    position: static;
    bottom: 15px;
    left: 0;
    width: 100%;
}
.progress{
    margin: 0 auto;
    max-width: 720px;
}
.progress__bar p {
    font-family: 'GhothamPro';
    font-size: 24px;
    color: black;
}
.progress__bar-wrapper{
    width: 100%;
    height: 100vh;
    position: fixed;
    z-index: 50000000;
    top: 0;
    left: 0;
    background:rgba(255, 255, 255, 0.75);
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
