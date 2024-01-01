<template>
  <div class="d-flex inner__content profile-content messanger__wrapper big">
    <div class="d-flex messanger w-100">
      <channels-list
          @changeChannel="selectChanel"
          :channels = "channelsData"
          :count-notification="countNotification"
          v-if="channelsData.length != 0"
      />
      <b-alert v-else show variant="success">
        No correspondence found
      </b-alert>
      <a href="/load-more" @click.prevent="moreChannels" v-if="countChannels > JSON.parse(channelsData).length">
        Load more
      </a>
      <chat
          v-if="currentChannel && chatLoaded"
          :current-channel="currentChannel"
          :messanges="messanges"
          :guest="currentGuest"
          :count-messages="messangesCount"
          :current-user="currentUser"
          @newmessage="newMessageAdd"
      />
    </div>
  </div>
</template>


<script>
import MessangerChannels from "./MessangerChannels";
import moment from 'moment';
const chat  = () => import("./MessangerChat");
export default {
  name:"MessangerWrapper",
  props:[
    'channels',
    'countChannels',
    'countNotification'
  ],
  data(){
    return{
      currentChannel:null,
      messanges:[],
      chatLoaded: false,
      currentGuest: null,
      usedChats:20,
      messangesCount: 0,
      countNotification: null,
      currentUser: null,
      channelMessanges:0,
      channelsData: this.channels,
    }
  },
  methods:{
    moreChannels(){
      window.axios.get("/load-more-chats?used-channels="+this.usedChats)
          .then(
              resp => {
                this.usedChats += 20
                let channels = JSON.parse(this.channelsData);
                let new_channels = channels.concat(resp.users);
                this.channelsData = JSON.stringify(new_channels)
              }
          )
    },
    selectChanel(data){
      this.channelMessanges = 30
      this.currentChannel = data.channel.id // chanelId - это id второго пользователя.
      // this.currentGuest = data.channel
      let users = data.users.split(",")
      axios.get("/get-messages?channel="+data.channel+"&users="+ data.users)
          .then(
              resp => {
                this.countMessanges = resp.countMessanges
                this.currentUser = resp.data.current_user
                this.currentChannel = resp.data.channel_id;
                let count = resp.data.view_counts
                let guest = null;
                this.currentGuest = resp.data.guest
                let unread_count = resp.data.view_counts

                function selectCounts(selector){
                  let select = document.querySelector(selector);
                  if(select){
                    let prev_count = select.innerText
                    prev_count -= count
                    if(prev_count > 0){
                      select.innerText = prev_count
                    }else{
                      select.style.display = "none"
                    }
                  }

                }
                selectCounts(".profile-nav li:nth-child(2) span")
                selectCounts("header nav .notifications__counter")

                this.dataChanger("messanges",resp.data.messanges)
              }
          )
    },
    newMessageAdd(data){
      let key = null;
      console.log(data)
      let newActive = null;
      let channels = JSON.parse(this.channelsData);
      for(let i =0;i<channels.length; i++){
        console.log(this.currentChannel + " = "+ channels[i].id)
        if(channels[i].id == this.currentChannel) {
          key = i;
          newActive = channels[i]
          i = channels.length
        }
      }
      channels.splice(key,1)
      newActive.lastmessage[0].message_body = data.message_body
      console.log('-1-');
      console.log('-2-',moment().format());
      newActive.lastmessage[0].created_at = moment().format()
      channels.unshift(newActive)
      this.channelsData = JSON.stringify(channels)
    },
    dataChanger(nameOfVariable, dataVariable){
      this.chatLoaded = false
      this.$data[nameOfVariable] = dataVariable;
      this.$nextTick(() => {
        this.chatLoaded = true
      })
    }
  },
  components:{
    "channels-list":MessangerChannels,
    chat,
  },

}
</script>
