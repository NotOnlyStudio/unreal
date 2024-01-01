<template>
  <form enctype="multipart/form-data" ref="galleryForm" class="container mt-4">
    <div class="container">
      <p class="breadcrumbs">
        <a href="/" style="color: rgb(179, 179, 179); text-decoration: none"
          >UnrealShop</a
        >
        >
        <a
          href="/gallery"
          style="color: rgb(179, 179, 179); text-decoration: none"
          >Gallery</a
        >
        > Upload
      </p>
    </div>
    <div class="d-flex flex-column">
      <p class="subtags text-orange">
        Title:
        <input
          name="title"
          v-model="cardTitle"
          placeholder="Please enter name"
          type="text"
          @keypress="inpFilter"
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
      <input type="file" multiple id="tmp_images" class="d-none">
      <div class="d-flex mt-4">
        <card
          v-if="cardLoaded"
          url="#"
          :image="cardImg != '' ? cardImg : '/assets/1render_small.jpg'"
          :card-title="
            cardTitle == '' ? 'This will be the name of the card' : cardTitle
          "
          :author="$cookies.get('user').name"
          :authorName="$cookies.get('user').name"
          ratingPlus="0"
          ratingMinus="0"
        />
        <div class="d-flex flex-column rules block-fc">
          <div
            v-if="rules.length != 0 && rules != null"
            class=""
            v-html="rules"
          />
          <div class="my-3">
            <div role="group" class="form-group">
              <label for="description" class="d-block text-orange" >Description</label>
              <div>
                <textarea id="description" v-model="description" name="description" placeholder="Enter description here"></textarea></div></div>
          </div>
        </div>
      </div>
      <div class="d-flex justify-content-end mt-4">
        <button class="btn btn-red sm" @click.prevent="uploadToGallery">
          Send
        </button>
      </div>
      <p class="block-title bold cr mt-big mb-0 tr-none">
        {{ cardTitle == "" ? "Your title will be here" : cardTitle }}
      </p>
      <div class="d-flex justify-content-end">
        <div class="d-flex align-items-end">
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
                  />
                  <line
                    id="Line_2"
                    data-name="Line 2"
                    y2="6"
                    transform="translate(1051.5 2226.5)"
                    fill="none"
                    stroke="#ff8562"
                    stroke-width="1.5"
                  />
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
                />
              </svg>
            </div>
          </div>
        </div>
      </div>
        <div v-for="(itm,key) in videosSrc" :key="'video-'+key">
            <b-modal :id="'modal-video-'+key" title="Upload video" @ok="uploadVideo(key)">
                <b-form-group label="Insert video url" label-for="video">
                    <b-form-input v-model="videosSrc[key]" placeholder="Insert url" :id="'video-'+key"/>
                </b-form-group>
            </b-modal>
            <div class="video-control position-relative mt-3">
                <b-icon-camera-video v-b-modal.modal="'modal-video-'+key" v-if="!videoBoolean[key]"
                                     title="Upload video"/>
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
                  margin-top: 10px;
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
            @dragstart='startDrag($event, key)'
            @dragend="dragEnd()"
            @dragover="dragOver($event)"
            @dragover.prevent
            :data-key="key"
            :style="'order:'+(key+1)"
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
      <input name="user" type="hidden" id="user" v-bind:value="user_id" />
    </div>
      <div class="d-flex justify-content-between my-3">
          <button
              class="btn btn-red btn-sm"
              style="width: fit-content; padding-left: 10px; padding-right: 10px"
              @click.prevent="more"
          >
              Add more photo
          </button>

          <button class="btn btn-red sm" @click.prevent="uploadToGallery">
              Send
          </button>
      </div>
      </div>

      <b-modal ref="my-modal" id="modalPopover" title="Alert" ok-only>
          <p>Not all data is filled.</p>
      </b-modal>
    <div class="progress__bar-wrapper" v-if="preloader">
      <div class="progress__bar" >
        <p class="text-center">Art is uploading on server</p>
        <b-progress :value="progress" variant="dark" :max="100" :animated="animate" class="mt-3"></b-progress>
      </div>
    </div>
  </form>
</template>

<script>
const card = () => import("../components/SimpleCards/SimpleCard");
const preloader = () => import("../components/preloader");
const author = () => import("../components/Author");

import {upload_video} from "../Mixins/iframe_video";
import {filters} from "../Mixins/filters";

export default {
    name: "uploadModel",
    mixins: [filters, upload_video],
    components: {
        card,
        author,
        preloader,
    },

    data() {
        return {
            cardTitle: "",
            cardSrc: "",
            cardImg: "#",
            dragKey: null,
            isDragging: false,
            order: null,
            description: "",
            rules: [],
            animate: true,
            progress: 0,
            tags: "",
            preloader: false,
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
      cardLoaded: true,
      csrf: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
      user_id: this.$store.getters.userId,
    };
  },

  methods: {
    changeMainPhoto(){
        console.clear()
        console.log("Changed")
        setTimeout(()=>{
            let first_img = document.querySelector(`.background-photo:first-of-type`).getAttribute("src")
            first_img = first_img == "#" ? [] : first_img;
            this.cardImg = first_img
        },1000)
    },
    uploadToGallery() {
      let items = document.querySelectorAll(".background-photo")
      let photos = []
      items.forEach(item => {
          let src = item.getAttribute("src")
          if (src != "" && src != "#" && src != "null")
              photos.push(item)
      })
      if (
        this.cardTitle != "" &&
        this.tags != "" &&
        this.$store.getters.userToken !== false &&
        this.description != "" &&
        photos.length > 0
      ) {
          this.preloader = true;
          let images = [];
          Array.prototype.forEach.call(
              document.querySelectorAll(".uplImg"),
              function (item) {
                  images.push(item.value);
              }
          );
          document.querySelector("#user").value = this.user_id;
          var formData = new FormData(this.$refs.galleryForm);
          Object.keys(this.videosSrc).forEach(e => {
              formData.append(`video[]`, this.videosSrc[e])
          })
          let config = {
              headers: {"Content-Type": "multipart/form-data"},
              onUploadProgress: (progressEvent) => {

                this.progress = Math.min(Math.round(e.loaded * 100 / e.total), 99);

              },
          };
          window.axios.post("/gallery", formData, config).then((resp) => {
              this.preloader = false;
              if (resp.status == 200) {
                  document.location.href = "/cabinet/gallery"
              }
          });
      } else {
        this.$refs["my-modal"].show();
      }
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
                  if (
                    img.getAttribute("data-type") &&
                    img.getAttribute("data-type") == "first"
                  ) {
                    this.cardImg = imgSrc;
                    this.cardLoaded = false;
                    this.$nextTick(() => {
                      this.cardLoaded = true;
                    });
                  }
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
        this.changeMainPhoto();
    },
      uploadCardImg(e) {
          e.preventDefault();
          e.target.parentElement.children[2].click();
      },
      more() {
          if (this.images.length < 9) this.images.push({src: ""});
      },
      moreVideo(e) {
          if (this.videosSrc.length < 3) {
              this.videosSrc.push('');
              this.videoBoolean.push(false);
          }
      },
      clearFile(e) {
          e.preventDefault();
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
          this.dragLeave();
          this.changeMainPhoto();
      },
      check() {
          console.log(this.cardImg);
    },

    dragStart(e) {
      let type = e.target.tagName;
      if (type == "BUTTON") {
        e.target.parentElement.classList.add("dragEffect");
      } else {
        e.target.classList.add("dragEffect");
      }
    },
    dragLeave() {
      Array.prototype.forEach.call(
        document.querySelectorAll(".image"),
        (item) => {
          item.classList.remove("dragEffect");
        }
      );
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
    window.axios.get("/api/gallery/get-rules").then((resp) => {
      this.rules = resp.data.rules ? JSON.parse(resp.data.rules) : [];
    });
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
.subtags input::placeholder {
  opacity: 0.5;
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
.image-control.dragged {
  border: 2px solid rgba(0, 0, 0, 0.3);
}
.dragOver {
  opacity: 0.75;
}
.image-control {
  transition: 0.3s linear;
}
.upload-model__inner label {
  font-size: 107px;
  color: #b3b3b3;
  font-family: Candara;
  -webkit-transition: 0.3s linear;
  transition: 0.3s linear;
  cursor: pointer;
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

