export let upload_video = {
    data(){
        return{
            videosSrc: [''],
            videoBoolean: [false]
        }
    },
    methods:{
        deleteVideo(index){
            Vue.set(this.videoBoolean, index, false)
            this.videosSrc[index] = ''
        },
        uploadVideo(index) {
            let videoType = "";
            let url = this.videosSrc[index]

            if (url.includes("http") && url.indexOf("youtube.com") != -1 || url.indexOf("vimeo.com") != -1 || url.indexOf("youtu.be") != -1) {
                url = url.indexOf("youtu.be") != -1 ? url.replace("youtu.be", "youtube.com/embed/") : url
                if (url.indexOf("youtube.com")) {
                    url = url.replace("https://www.youtube.com/watch?v=", "https://www.youtube.com/embed/")
                    let ind = url.indexOf("&");
                    if (ind != -1) {
                        url = url.slice(0, ind)
                    }
                }

                if (url.indexOf("vimeo.com"))
                    url = url.replace("https://vimeo.com/", "https://player.vimeo.com/video/") + "?title=0&byline=0&portrait=0"

                this.videosSrc[index] = url
                // Vue.set(this.videosSrc,index, url)
                Vue.set(this.videoBoolean, index, true)
            }else{
                alert("Incorrect link")
                this.videosSrc[index] = null
            }
        },
    }
}
