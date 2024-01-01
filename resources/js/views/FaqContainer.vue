<template>
<div>
    <video-iframe
        v-scroll-lock="video"
        v-if="video && videoIframe"
        :video-iframe="videoIframe"
        :video-title="videoTitle"
        @close-video="closeVideo"
        @keydown.esc="closeVideo"
    />
    <div class="faq-container">
        <b-card no-body>
            <b-tabs pills card vertical>
                <b-tab v-for="(item,key) in serverData" :key="key" :title="item.title" :active="key==0 ? true : false">
                    <b-card-text>
                        <div :set="content = JSON.parse(item.content)">
                            <div  v-for="(elem, k) in content" :key="k">
                                <p class="text-bold d-flex fsz17 text-orange">
                                    {{elem.title}}
                                    <a href="#" v-if="elem.video != null" @click.prevent="showVideo(k, key)" class="getInstruction">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15"><g id="Group_17" data-name="Group 17" transform="translate(-766 -687)"><g id="Ellipse_1" data-name="Ellipse 1" transform="translate(766 687)" fill="#fff" stroke="#ff8562" stroke-width="1"><circle cx="7.5" cy="7.5" r="7.5" stroke="none"></circle> <circle cx="7.5" cy="7.5" r="7" fill="none"></circle></g> <g id="Polygon_1" data-name="Polygon 1" transform="translate(777 691) rotate(90)" fill="#ff8562"><path d="M 6.129478454589844 5.5 L 0.8705216646194458 5.5 L 3.5 0.9923228621482849 L 6.129478454589844 5.5 Z" stroke="none"></path> <path d="M 3.5 1.984635829925537 L 1.741037368774414 5 L 5.258962631225586 5 L 3.5 1.984635829925537 M 3.5 0 L 7 6 L 0 6 L 3.5 0 Z" stroke="none" fill="#ff8562"></path></g></g></svg>
                                      instruction
                                    </a>
                                </p>
                                <p class="text-bold fsz17 text-gray" v-html="elem.description ? elem.description.replace(/\n|\r/g, '<br />') : ''"></p>
                            </div>
                        </div>
                    </b-card-text>
                </b-tab>
            </b-tabs>
        </b-card>
    </div>
</div>
    
</template>

<script>
const videoIframe = () => import("../components/VideoComponent")
export default {
    name:"FaqContainer",
    props:["serverData"],
    components:{
        "video-iframe":videoIframe
    },
    data(){
        return{
            video:false,
            videoIframe:null,
            videoTitle:"",
        }
    },
    methods:{
        closeVideo(){
            this.video = false
            this.videoIframe = null
        },
        showVideo(k,key){
            let content = JSON.parse(this.serverData[key].content)
            content = content[k]
            if(content.video != null){
                this.videoIframe = content.video
                this.video = true
                this.videoTitle = content.title
            }
        }
    },
    mounted(){
        document.querySelectorAll(".nav-item")[0].click
    }
}
</script>