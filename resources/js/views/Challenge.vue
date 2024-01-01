<template>
   <form v-if="loaded"  ref="challengeForm" @submit.prevent="saveChallenge">
      <preloader v-if="preloaderBool"/>
      <b-modal ref="modal" id="modal-1" hide-footer title="Alert">
         <p v-html="errorMsg"></p>
      </b-modal>
      <div class="container">
         <p class="breadcrumbs breadcrumbs-challenge">
            {{ challengeInfo.name }} -
            <span>
            Deadline:
            {{ challengeInfo.deadline.split("-").reverse().join(".") }}</span
               >
         </p>
      </div>
      <Tinybox v-model="index" :images="photos" />
      <div class="container challenge__examples border-standart p-4 mt-5 d-flex">
         <div class="d-flex flex-column">
            <p class="text-orange text-bold" style="font-size: 17px">Examples:</p>
            <carousel :items="1" :dots="true" :nav="false" :auto-width="true">
               <picture v-for="(img, key) in photos" :key="key" @click="index = key">
                  <!-- <source type="image/webp" :srcset="$imgRoute+'challenges/'+challengeInfo.alias+'/'+img | webpThumb" /> -->
                  <source type="image/png" :srcset="img | thumb" />
                  <source type="image/jpg" :srcset="img | thumb" />
                  <source type="image/jpeg" :srcset="img | thumb" />
                  <img :src="img | thumb" alt="" style="width: 220px" />
               </picture>
            </carousel>
         </div>
         <div class="d-flex flex-column px-5">
            <p class="text-bold text-orange" style="font-size: 17px">
               About challenge:
            </p>
            <p class="text-gray text-bold" style="font-size: 17px">
               {{ challengeInfo.about }}
            </p>
            <p class="text-bold text-orange" style="font-size: 17px">
               Description:
            </p>
            <p class="text-gray text-bold" style="font-size: 17px">
               {{ challengeInfo.description }}
            </p>
         </div>
      </div>
      <div class="d-flex mt-5 pt-5">
         <div class="container mt-4">
            <div class="d-flex flex-column">
               <p class="subtags text-orange">
                  Title:
                  <input
                     name="title"
                     @keypress="inpFilter"
                     v-model="cardTitle"
                     placeholder="Please enter name"
                     type="text"
                     />
               </p>
               <p class="subtags text-orange">
                  Tags:
                  <input
                     name="tags"
                     type="text"
                     v-model="tags"
                     placeholder="Please enter tags"
                     />
               </p>
               <div
                  class="d-flex mt-2 container justify-content-between"
                  style="padding-left: 0px"
                  >
                  <card
                     v-if="cardLoad"
                     url="#"
                     :image="cardImg"
                     :card-title="
                     cardTitle == ''
                     ? 'This will be the name of the card'
                     : cardTitle
                     "
                     :author="authorInfo.name"
                     :authorName="authorInfo.name"
                     ratingPlus="0"
                     ratingMinus="0"
                     />
                  <div class="rules ml-auto">
                     <p class="text-orange title">Requrements:</p>
                     <p class="text-gray">
                        {{ challengeInfo.requirments }}
                     </p>
                     <p class="text-orange title">Moderation:</p>
                     <p class="text-gray">
                        {{ challengeInfo.moderation }}
                     </p>
                     <div class="my-3">
                        <div role="group" class="form-group">
                           <label for="description" class="d-block text-orange"
                              >Description</label
                              >
                           <div>
                              <textarea
                                 id="description"
                                 v-model="description"
                                 name="description"
                                 placeholder="Enter description here"
                                 ></textarea>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="d-flex justify-content-end mt-4 container">
                  <button class="btn btn-red sm" :disabled="isButtonDisabled">Send</button>
               </div>
               <p class="block-title bold cr mt-big mb-0">
                  {{
                  cardTitle == "" ? "This will be the name of the card" : cardTitle
                  }}
               </p>
               <div class="d-flex justify-content-end">
                  <div class="d-flex plus">
                     <span>0</span>
                     <div class="plus__icon">
                        <svg
                           xmlns="http://www.w3.org/2000/svg"
                           width="6"
                           height="6"
                           viewBox="0 0 6 6"
                           >
                           <g
                              id="Group_14"
                              data-name="Group 14"
                              transform="translate(-1048.5 -2226.5)"
                              >
                              <line
                                 id="Line_1"
                                 data-name="Line 1"
                                 x2="6"
                                 transform="translate(1048.5 2229.5)"
                                 fill="none"
                                 stroke="#ff8562"
                                 stroke-width="1.5"
                                 ></line>
                              <line
                                 id="Line_2"
                                 data-name="Line 2"
                                 y2="6"
                                 transform="translate(1051.5 2226.5)"
                                 fill="none"
                                 stroke="#ff8562"
                                 stroke-width="1.5"
                                 ></line>
                           </g>
                        </svg>
                     </div>
                  </div>
                  <div class="d-flex minus">
                     <span>0</span>
                     <div class="minus__icon">
                        <svg
                           xmlns="http://www.w3.org/2000/svg"
                           width="6"
                           height="1.5"
                           viewBox="0 0 6 1.5"
                           >
                           <line
                              id="Line_2"
                              data-name="Line 2"
                              x2="6"
                              transform="translate(0 0.75)"
                              fill="none"
                              stroke="#ff8562"
                              stroke-width="1.5"
                              ></line>
                        </svg>
                     </div>
                  </div>
               </div>
               <div
                  class="
                  images-controllers
                  position-relative
                  mt-3
                  d-flex
                  flex-column
                  position-relative
                  "
               >
                   <input type="file" class="d-none" id="tmp_images"/>
                   <author
                       :name="user.name"
                       :id="user.id"
                       :photo="user.photo"
                       :rank="1"
                   />

                   <div v-for="(itm,key) in videosSrc" :key="'video-'+key">
                       <b-modal :id="'modal-video-'+key" title="Upload video" @ok="uploadVideo(key)">
                           <b-form-group label="Insert video url" label-for="video">
                               <b-form-input v-model="videosSrc[key]" placeholder="Insert url" :id="'video-'+key"/>
                           </b-form-group>
                       </b-modal>
                       <div class="video-control position-relative mt-3">
                           <b-icon-camera-video v-b-modal.modal="'modal-video-'+key" v-if="!videoBoolean[key]" title="Upload video"/>
                           <iframe :src="videosSrc[key]" v-if="videoBoolean[key]" frameborder="0"
                                   class="w-100 position-absolute h-100"></iframe>

                           <div class="position-absolute delete-bg" v-if="videoBoolean[key]" style="z-index:3">
                               <button class="delete" @click="deleteVideo(key)">
                                   <img src="/img/icons/delete.svg" alt=""/>
                               </button>
                           </div>
                       </div>
                   </div>
                   <button
                       class="btn btn-red btn-sm"
                       style="
                  width: fit-content;
                  padding-left: 10px;
                  padding-right: 10px;
                  "
                       @click.prevent="moreVideo"
                   >
                       Add more video
                   </button>

                   <div
                       class="image image-control"
                       v-for="(item, key) in images"
                       draggable
                       :key="key"
                       @drop="dropEvent"
                       @dragstart="startDrag($event, key)"
                       @dragend="dragEnd()"
                       @dragover="dragOver($event)"
                       @dragover.prevent
                  :data-key="key"
                  :style="'order:' + (key + 1)"
                  >
                  <button class="add" @click="uploadCardImg">+</button>
                  <img
                     data-type="first"
                     src="#"
                     class="d-none background-photo"
                     alt=""
                     v-if="item.id == 1"
                     />
                  <img
                     data-type="second"
                     src="#"
                     class="d-none background-photo"
                     alt=""
                     v-else-if="item.id == 2"
                     />
                  <img src="#" class="d-none background-photo" alt="" v-else />
                  <input
                     accept=".gif, .jpeg, .png, .jpg"
                     name="img[]"
                     type="file"
                     @change="uploadPhoto"
                     class="d-none file"
                     multiple
                     />
                  <div class="background"></div>
                  <button class="delete" @click="clearFile">
                  <img src="/img/icons/delete.svg" alt="" />
                  </button>
               </div>
            </div>
            <div class="d-flex justify-content-between my-3">
               <button
                  class="btn btn-red btn-sm"
                  style="width: fit-content;padding-left: 10px;padding-right: 10px;"
                  @click.prevent="more"
                  >
               Add more photo
               </button>
               <button class="btn btn-red sm" :disabled="isButtonDisabled">Send</button>
            </div>
         </div>
      </div>
      </div>
      <button v-b-modal.modal-1 class="d-none modal" id="modal-btn">
      open modal
      </button>
   </form>
</template>
<script>
import axios from "axios";
import author from "../components/Author";
// const preloaderWrapper = () => import("../components/preloader");
import card from "../components/SimpleCards/SimpleCard";
import {filters} from "../Mixins/filters";
import carousel from "vue-owl-carousel";
import Tinybox from "vue-tinybox";
import preloader from '../components/preloader.vue';
import {upload_video} from "../Mixins/iframe_video";

export default {
    name: "Challenge",
    props: ["challengeInfo", "authorInfo"],
    mixins: [filters, upload_video],
    components: {
        author,
        card,
        carousel,
        preloader,
        "tiny-box": Tinybox,
    },
    data() {
        return {
            isButtonDisabled: false,
            loaded: true,
            user: $cookies.get("user"),
            tags: "",
            cardTitle: "",
         errorMsg: "",
         sending: false,
         index: null,
         preloaderBool: false,
         description: "",
         cardImg: "",
         cardLoad: true,
         images: [
           {
             src: "",
             id: 1,
           },
           {
             src: "",
             id: 2,
           },
           {
             src: "",
           },
         ],
         options2: {
           slidesToScroll: 1,
           slidesToShow: 1,
           centerPadding: "0",
           centerMode: true,
           arrows: false,
           infinite: true,
           focusOnSelect: "true",
         },
       };
     },
     computed: {
       photos() {
         let arr = JSON.parse(this.challengeInfo.photos);
         let res = arr.map((item) => {
           return (
             this.$imgRoute + "challenges/" + this.challengeInfo.alias + "/" + item
           );
         });
         return res;
       },
     },
     methods: {
       more(e) {
         if (this.images.length < 9) {
           this.images.push({ src: "" });
         }
       },
         moreVideo(e) {
         if (this.videosSrc.length < 3) {
           this.videosSrc.push('');
           this.videoBoolean.push(false);
         }
       },
       saveChallenge(e) {
         if (!this.sending) {
           let items = document.querySelectorAll(".background-photo")
           let photos = []
           items.forEach(item => {
              let src = item.getAttribute("src")
              if(src != "" && src != "#" && src != "null")
                photos.push(item)
           })
           if (
             this.tags != "" &&
             this.cardTitle != "" &&
             this.description != "" &&
             photos.length > 0
           ) {
               this.isButtonDisabled= true;
               this.preloaderBool = true;
               this.sending = true;
               e.preventDefault();
               let formdata = new FormData(this.$refs.challengeForm);
               formdata.append("challenge_id", this.challengeInfo.id);
               formdata.append("user", this.user.id);
               console.log('formdata', formdata);

               // let videoSrc = this.videoBoolean ? this.videoSrc : null;
               //let videoSrc = null;
               //formdata.append("video", this.videoSrc)

               Object.keys(this.videosSrc).forEach(e => {
                   formdata.append(`video[]`, this.videosSrc[e])
               })

               let config = {headers: {"Content-Type": "multipart/form-data"}};
               axios.post("/gallery", formdata, config).then((resp) => {
                   if (resp.status == 200) {
                       if (resp.data.status == true) {
                           this.preloaderBool = false;
                           document.location.href = "/cabinet/gallery/";
                       }
                   }
               });
            /*setTimeout(
              () => {
                  this.preloaderBool = false;
              },1500
            )*/
           } else {
             alert("Please, fill all inputs");
           }
         } else {
           alert("Wait 5 seconds");
         }
         setTimeout(() => {
           this.sending = false;
         }, 5000);
       },
       clearFile(e) {
         e.target.parentElement.parentElement.classList.remove("image-delete");
         e.target.parentElement.parentElement.children[1].classList.add("d-none");
         e.target.parentElement.parentElement.children[1].classList.remove(
           "showingBg"
         );
         e.target.parentElement.parentElement.children[1].setAttribute(
           "src",
           null
         );
         e.target.parentElement.parentElement.children[2].value = null;
         e.target.parentElement.parentElement.children[0].classList.remove(
           "d-none"
         );
       },

       uploadCardImg(e) {
         e.preventDefault();
         e.target.parentElement.children[2].click();
       },

       uploadPhoto(e) {
             let image = e.target.files;
             if (image.length == 1) {
                 image = image[0];
                 const reader = new FileReader();
                 reader.readAsDataURL(image);
                 let parent = e.target.parentElement;
                 let img = parent.children[1];
                 reader.onload = (e) => {
                     let imgSrc = e.target.result;
                     img.classList.remove("d-none");
                     img.classList.add("showingBg");
                     parent.children[0].classList.add("d-none");
                     img.setAttribute("src", imgSrc);

                 };
                 e.target.parentElement.classList.add("image-delete");
             } else {
                 e.preventDefault();
                 function clearedFileInputs() {
                     let arr = [];
                     Array.prototype.forEach.call(
                         document.querySelectorAll(`input[name="img[]"]`),
                         (item) => {
                             if (item.files.length == 0) arr.push(item);
                         }
                     );
                     return arr;
                 }
                 function uplImages() {
                     console.warn(images);

                     Array.prototype.forEach.call(images, (file, key) => {
                         let type = file.type;
                         if (
                             type == "image/png" ||
                             type == "image/jpg" ||
                             type == "image/gif" ||
                             type == "image/png" ||
                             type == "image/jpeg"
                         ) {
                             let dt = new DataTransfer();
                             dt.items.add(file);
                             let inp = clearedInputs[key]; //---
                             inp.files = dt.files;
                             let reader = new FileReader();
                             reader.readAsDataURL(file);
                             let parent = inp.parentElement;
                             let img = parent.children[1];
                             reader.onload = (e) => {
                                 let imgSrc = e.target.result;
                                 img.classList.remove("d-none");
                                 img.classList.add("showingBg");
                                 parent.children[0].classList.add("d-none");
                                 img.setAttribute("src", imgSrc);

                             };
                             parent.classList.add("image-delete");
                         }
                     });
                 }
                 let dt = new DataTransfer();
                 Array.prototype.forEach.call(image, (file) => {
                     dt.items.add(file);
                 });
                 image = dt.files;
                 document.querySelector("#tmp_images").files = image;

                 let images = document.querySelector("#tmp_images").files;
                 e.target.value = null;
                 let clearedInputs = clearedFileInputs();
                 let img_lg = images.length;
                 if (clearedInputs.length < img_lg) {
                     let numb = img_lg - clearedInputs.length;
                     for (let i = 0; i < numb; i++) {
                         this.more();
                     }
                     setTimeout(() => {
                         clearedInputs = clearedFileInputs();
                         uplImages();
                     }, 500);
                 }
                 if (clearedInputs.length >= img_lg) {
                     uplImages();
                 }
                 // document.querySelector("#tmp_images").value = "";
             }
             this.changeMainPhoto();
         },
       dropEvent(e) {
         if (this.isDragging) {
           let target = e.target.classList.contains("image-control");
           let newOrder, dragKey;
           if (target) {
             newOrder = e.target.style.order;
             dragKey = e.target.getAttribute("data-key");
           } else {
             newOrder = e.target.parentElement.style.order;
             dragKey = e.target.parentElement.getAttribute("data-key");
           }
           document.querySelector(
             `.image-control[data-key="${this.dragKey}"]`
           ).style.order = newOrder;
           document.querySelector(
             `.image-control[data-key="${dragKey}"]`
           ).style.order = this.order;
           this.dragLeave();
         } else {
           e.preventDefault();
           const image = e.dataTransfer.files;
           let inp =
             e.target.tagName == "BUTTON"
               ? e.target.parentElement.children[2]
               : e.target.children[2];
           if (image.length < 2) {
             let type = image[0].type;
             if (
               type == "image/png" ||
               type == "image/jpg" ||
               type == "image/gif" ||
               type == "image/png" ||
               type == "image/jpeg"
             ) {
               inp.files = image;
               const reader = new FileReader();
               reader.readAsDataURL(image[0]);
               let parent = inp.parentElement;
               let img = parent.children[1];
               reader.onload = (e) => {
                 let imgSrc = e.target.result;
                 img.classList.remove("d-none");
                 img.classList.add("showingBg");
                 parent.children[0].classList.add("d-none");
                 img.setAttribute("src", imgSrc);
               };
               inp.parentElement.classList.add("image-delete");
             }
           } else {
             function clearedFileInputs() {
               let arr = [];
               document.querySelectorAll(`input[name="img[]"]`).forEach((item) => {
                 if (item.files.length == 0) arr.push(item);
               });
               return arr;
             }
             function uplImages() {
               Array.prototype.forEach.call(image, (file, key) => {
                 let type = file.type;
                 if (
                   type == "image/png" ||
                   type == "image/jpg" ||
                   type == "image/gif" ||
                   type == "image/png" ||
                   type == "image/jpeg"
                 ) {
                   let dt = new DataTransfer();
                   dt.items.add(file);
                   let inp = clearedInputs[key]; //---
                   inp.files = dt.files;
                   let reader = new FileReader();
                   reader.readAsDataURL(file);
                   let parent = inp.parentElement;
                   let img = parent.children[1];
                   reader.onload = (e) => {
                     let imgSrc = e.target.result;
                     img.classList.remove("d-none");
                     img.classList.add("showingBg");
                     parent.children[0].classList.add("d-none");
                     img.setAttribute("src", imgSrc);
                   };
                   parent.classList.add("image-delete");
                 }
               });
             }
             let clearedInputs = clearedFileInputs();
             let img_lg = image.length;
             if (clearedInputs.length < img_lg) {
               let numb = img_lg - clearedInputs.length;
               for (let i = 0; i < numb; i++) {
                 this.more();
               }
               setTimeout(() => {
                 clearedInputs = clearedFileInputs();
                 uplImages();
               }, 500);
             }
             if (clearedInputs.length >= img_lg) {
               uplImages();
             }
           }
           this.changeMainPhoto();
         }
       },
       changeMainPhoto() {
         setTimeout(() => {
           let first_img = document
             .querySelector(`.background-photo:first-of-type`)
             .getAttribute("src");
           first_img = first_img == "#" ? [] : first_img;
           this.cardImg = first_img;
         }, 1000);
       },

         dragStart(e) {
         let type = e.target.tagName;
         if (type == "BUTTON") {
           e.target.parentElement.classList.add("dragEffect");
         } else {
           e.target.classList.add("dragEffect");
         }
       },
       dragOver(e) {
         e.target.classList.add("dragOver");
       },
       startDrag(e) {
         this.order = e.target.style.order;
         this.isDragging = true;
         e.target.classList.add("dragged");
         this.dragKey = e.target.getAttribute("data-key");
       },
       dragEnd() {
         Array.prototype.forEach.call(
           document.querySelectorAll(".image-control"),
           (item) => {
             item.classList.remove("dragged");
           }
         );
         Array.prototype.forEach.call(
           document.querySelectorAll(".dragOver"),
           (item) => {
             item.classList.remove("dragOver");
           }
         );
         this.isDragging = false;
         this.order = null;
       },
     },
     mounted() {
       this.$store.dispatch("setChallengeStyles", `top:14px;`);
     },
   };
</script>
<style scoped>
   .subtags input {
   border: none;
   margin-left: 10px;
   border-bottom: 1px dotted #b3b3b3;
   outline: none !important;
   }
   .form-group label.text-orange {
   color: #ff8767 !important;
   }
   #description,
   #description::placeholder {
   box-shadow: none;
   outline: none;
   font-family: Candara;
   font-weight: bold;
   color: #b3b3b3;
   border: none;
   }
   .subtags input::placeholder {
   opacity: 0.5;
   }
   .upload-model__inner label {
   font-size: 107px;
   color: #b3b3b3;
   font-family: Candara;
   -webkit-transition: 0.3s linear;
   transition: 0.3s linear;
   cursor: pointer;
   }
       .delete-bg{
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      background: rgba(255,255,255,.5);
    }
    .delete-bg button{
      background: none;
      border: none;
      opacity: 0;
      transition: .3s linear;
    }
    .delete-bg:hover button{
      opacity: 1;
    }
    .delete-bg button:hover{
      opacity: 0.9;
    }
</style>
