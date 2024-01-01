<template>
  <div v-if="message.message_from == currentUser.id" class="main message">
    <img
      :src="currentUser.photo ? '/storage/app/public/avatars/'+currentUser.photo : '/img/user.png'"
      :alt="currentUser.name ? currentUser.name : 'alt'"
    />
    <div class="messange_body">
      <p>
          {{message.message_body}}
      </p>
    </div>
  </div>
  <div v-else class="guest message">
    <img
      :src="guest.photo != null ? '/storage/app/public/avatars/'+guest.photo : '/img/user.png'"
      :alt="guest.name ? guest.name : 'alt'"
    />
    <div class="messange_body">
      <p>
          {{message.message_body}}
      </p>
    </div>
  </div>
</template>

<script>
export default {
  name: "MessageItem",
  props:[
    'message',
    "guest",
    "currentUser"
  ],
  data(){
    return{

    }
  },
  computed:{
      // currentUser(){
      //   let user = $cookies.get("user")
      //   return user.id;
      // }
  },
  mounted(){
      var objDiv = document.querySelector(".content");
      objDiv.scrollTop = objDiv.scrollHeight;

  }
};
</script>