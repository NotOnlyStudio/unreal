export const uploadPhoto = {
    data(){
        return{
            startDrag: null,
            startOrder: null,
            endOrder: null,
            endDrag: null,
            photosRerender: true,
            inpRerender: true,
        }
    },
    methods:{
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
                         console.log(this.cardImg)
                     }
                 };
                 e.target.parentElement.classList.add("image-delete")
            }else{
                 e.preventDefault()
                 function clearedFileInputs(){
                     let arr = []
                     Array.prototype.forEach.call(document.querySelectorAll(`input[name="photos[]"]`),
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
                         this.morePhoto();
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
        },
        StaticDropEvent(key){
            this.endDrag = key
            console.log(this.startDrag + " + " + this.endDrag)
            this.endOrder = document.querySelector(`.staticPhoto[data-key="${key}"]`).style.order
            document.querySelector(`.staticPhoto[data-key="${this.startDrag}"]`).style.order = this.endOrder
            document.querySelector(`.staticPhoto[data-key="${this.endDrag}"]`).style.order = this.startOrder
        },
        StaticStartDrag(e,key){
            this.startDrag = key
            this.startOrder = document.querySelector(`.staticPhoto[data-key="${key}"]`).style.order
        },
        StaticDragEnd(e,key){
            console.log(e.target)
        },
        StaticDragOver(){

        },
        DragOver(){

        },
        DragEnd(){

        },
        StartDrag(e,key){
            this.startDrag = key
            this.startOrder = e.target.style.order
        },
        DropEvent(e,key){
            this.inpRerender = false
            this.endDrag = key
            this.endOrder = e.target.style.order 
            let photos = this.editForm.photos;
            [photos[this.startDrag],photos[this.endDrag]] = [photos[this.endDrag],photos[this.startDrag]]
            this.editForm.photosOrder = photos
            setTimeout(
                () => {
                    console.log(`${this.startOrder} | ${this.endOrder}`)
                    document.querySelector(`#static__photo-${this.startDrag}`).style.order = this.endOrder
                    document.querySelector(`#static__photo-${this.endOrder}`).style.order = this.startOrder
                },
                300
            )
            this.$nextTick(
                () => {
                    this.inpRerender = true
                }
            )

        }
    }
}