<template>
    <div v-if="loaded" class="tabs row no-gutters">
        <div class="col-auto">
            <ul class="nav nav-pills flex-column card-header h-100 border-bottom-0 rounded-0">
                <li 
                    draggable="true"
                    role="presentation"
                    class="nav-item"
                    @dragenter.prevent="dragEnter"
                    @dragover.prevent
                    @dragstart ="dragStart($event,key)"
                    @drop.prevent="drop($event,key)"
                    @drag="dragEvent"
                    @dragend="dragEnd"
                    @dragleave="dragLeave"
                    v-for="(title,key) in itemInfo"
                    :key="key"
                    :data-key="key"
                    @click="changeTab($event,key)"
                >
                    <a class="nav-link" :data-key="key" v-text="title.title"></a>
                </li>
            </ul>
        </div>
        <div class="tab-content col">
            <div class="tab-pane card-body" v-for="(accordion,key) in itemInfo" :key="key"  :data-key="key">
                  <b-form :set="contentItem = JSON.parse(accordion.content)" :ref="'form-'+key" :data-key="key" :id="'form-'+key" class="d-flex flex-column component-form">
                    <b-form-group
                        label="Title"
                        :label-for="'title-'+key"
                    >
                        <b-form-input :id="'title-'+key" name="faqtitles[]" :data-key="key" placeholder="Title" :value="accordion.title"/>
                    </b-form-group>
                    <div v-if="contentItem.length != 0">
                        <div
                            v-for="(item, k) in contentItem"
                            :key="k"
                            class="jumbotron position-relative d-flex flex-column"
                        >
                            <div class="bg-danger deleteBtn" @click="deleteJumbotron(key,k)">
                                <b-icon-x variant="light"/>
                            </div>
                            <b-form-group
                                label="Title"
                                :label-for="'title-'+k"
                            >
                                <b-form-input
                                    :id="'title-'+k"
                                    placeholder="Title"
                                    name="titles[]"
                                    :value="item.title"
                                />
                            </b-form-group>
                            <b-form-group
                                label="Iframe video"
                                :label-for="'video-'+k"
                            >
                                <b-form-textarea
                                    :id="'video-'+k"
                                    placeholder="Video"
                                    name="videos[]"
                                    :value="item.video"
                                />
                            </b-form-group>
                            <b-form-group
                                label="Description"
                                :label-for="'description-'+k"
                            >
                                <b-form-textarea
                                    :id="'description-'+k"
                                    placeholder="Description"
                                    name="descriptions[]"
                                    :value="item.description"
                                />
                            </b-form-group>
                
                        </div>
                    </div>
                </b-form>
                <div class="d-flex justify-content-start my-3">
                    <b-button variant="primary" @click="newInfo(key)"><b-icon-plus/><span class="ml-2">Add component</span></b-button>
                    <b-button variant="danger ml-3" @click="deleteComponent(key)"><b-icon-x/><span class="ml-2">Remove component</span></b-button>
                    
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name:"FaqTabs",
    props:[
        "content"
    ],
    data(){
        return{
            itemInfo:this.content,
            loaded: true,
            activeDrag: null,
            startClick:0,
        }
    },
    methods:{
         newInfo(key){
            let content = JSON.parse(this.itemInfo[key].content)
            content.push({
                "title":"",
                "video":"",
                "description":"",
            })
            this.itemInfo[key].content = JSON.stringify(content)
        },
        dragEnter(e){
            e.target.classList.add("dragEnter")
        },
        dragStart(e,key){
            e.target.classList.add("dragStart")
            this.activeDrag = key
        },
        dragEvent(e){
            e.target.classList.add("isDragged")
        },
        dragEnd(e){
            document.querySelectorAll(".nav-item").forEach(
                item => {item.classList.remove("isDragged");
                item.classList.remove("dragStart")}
            )
            document.querySelectorAll(".nav-link").forEach(
                item => {item.classList.remove("dragEnter")}
            )
            this.activeDrag = null
            console.log("is dragged")
        },
        deleteJumbotron(key,k){
            let content = this.itemInfo[key].content
            content = JSON.parse(content)
            content.splice(k, 1);
            this.itemInfo[key].content = JSON.stringify(content)
        },
        deleteComponent(key){
            this.itemInfo.splice(key,1)
        },
        drop(e,key){
            this.loaded = false
            let prev = this.itemInfo[this.activeDrag]
            let newItem = this.itemInfo[key]
            this.itemInfo[this.activeDrag] = newItem
            this.itemInfo[key] = prev
            this.$nextTick(
                ()=>{
                    this.startClick = key
                    this.loaded = true
                   
                }
            )
            setTimeout(()=>{
                this.startFAQ()
            },250)
            document.querySelectorAll(".nav-item").forEach(
                item => {item.classList.remove("isDragged");
                item.classList.remove("dragStart")}
            )
        },
        dragLeave(e){
            e.target.classList.remove("dragEnter")
        },
        changeTab(e,key){
            document.querySelectorAll(".tab-pane").forEach(
                item => {
                    item.classList.remove("active")
                }
            )
            document.querySelectorAll(".nav-link").forEach(
                item => {
                    item.classList.remove("active")
                }
            )
            setTimeout(
                () => {
                    document.querySelector(`.tab-pane[data-key="${key}"]`).classList.add("active")
                    document.querySelector(`.nav-link[data-key="${key}"]`).classList.add("active").classList.add("active")
                },
                250
            )
        },
        startFAQ(){
            document.querySelectorAll(`.nav-item[data-key="${this.startClick}"]`)[0].click()
        }
    },
    mounted(){
        this.startFAQ();
    }
}
</script>


<style scoped>
    .deleteBtn{
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 5;
        border-radius: 10px;
        width: 20px;
        cursor: pointer;
        height: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: .3s linear;
    }
    .deleteBtn:hover{
        opacity: .75;
    }
</style>