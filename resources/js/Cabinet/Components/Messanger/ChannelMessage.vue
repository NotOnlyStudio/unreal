<template>
  <div :class="[channel.id == activeChannel ? 'active' : '' ,'message']">
    <img
      :src="user.photo ? '/storage/app/public/avatars/'+user.photo : '/img/user.png'"
      alt=""
    />
    <div class="message__content w-100">
      <p class="name d-flex">{{user.name}}<span v-if="JSON.parse(countNotification)[channel.id]['count_notification']" class="notifications__counters">
            {{ JSON.parse(countNotification)[channel.id]['count_notification'] ? JSON.parse(countNotification)[channel.id]['count_notification'] : ''}}
        </span></p>

      <div class="d-flex mb-2 w-100 justify-content-between w-100">
        <span class="rang">{{user.rang ? user.rang.name : "rookie" }} ({{user.rating}})</span>
        <span class="time" v-if="lastMessage != null">{{createdAt}}</span>
      </div>
      <p class="text" v-if="lastMessage != null">
        {{lastMessage.message_body | slice}}
      </p>
      <p class="text" v-else>
        No messages
      </p>
    </div>
  </div>
</template>


<script>
import moment from 'moment';
export default {
  name: "ChannelMessage",
  props:[
      'channel',"activeChannel", 'countNotification'
  ],
  computed:{
    user(){
      if(this.channel.user_one != null){
        return this.channel.user_one
      }else{
        return this.channel.user_two
      }
    },
    lastMessage(){
      if(this.channel.lastmessage.length != 0){
        return this.channel.lastmessage[0]
      }
      return null
    },
    createdAt(){
        return moment(this.lastMessage.created_at).from(new Date().toLocaleString('en-US'));
    },
  },
  filters:{
    slice(txt){
      if(txt != undefined && txt.length > 172){
        return txt.slice(0, 172) + "..."
      }
      return txt;
    }
  },
};
</script>
