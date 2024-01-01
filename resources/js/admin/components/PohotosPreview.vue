<template>
  <div v-if="photos != null" class="d-flex flex-wrap">
    <h3 v-if="title">{{ title }}</h3>
    <div class="w-100 d-flex flex-column" v-if="Array.isArray(photos)">
      <div class="photo" v-for="(photo, key) in photos" :key="key">
        <img class="w-100" :src="prefix + '/' + photo" alt="" />
        <div class="delete" v-if="deleteBool" @click="deletePhoto(photo)">
          <b-icon-x fill="red" />
        </div>
      </div>
    </div>
    <div v-else class="photo w-100 d-flex flex-column">
      <img class="w-100" :src="prefix + '/' + photos" alt="" />
      <div class="delete" v-if="deleteBool" @click="deletePhoto(photos)"><b-icon-x  fill="red" /></div>
    </div>
  </div>
</template>

<script>
export default {
  name: "PhotosPreview",
  props: ["photos", "title", "prefix", "type","deletable"],
  methods:{
    deletePhoto(photo){
        this.$emit("deletePhoto",{
            'photo': photo,
            'type': this.type
        })
    }
  },
  computed:{
    deleteBool(){
      if(this.deletable == null || this.deletable == true)
        return true;
      else  
        return false;
      
    }
  }
  ,mounted(){
    console.clear()
    console.log(this.prefix)
  }
};
</script>


<style scoped>
.photo {
  position: relative;
}
.photo .delete {
  position: absolute;
  right: 0;
  width: 30px;
  height: 30px;
  top: 0;
  background: white;
  cursor: pointer;
  border-radius: 50%;
  box-shadow: 0 0 5px rgba(0,0,0,.5);
}
.delete svg{
    width: 100%;
    height: 100%;
}
.delete path{
    fill: red;
}
</style>