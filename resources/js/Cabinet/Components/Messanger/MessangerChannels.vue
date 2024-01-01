<template>
  <div class="d-flex flex-column messanger-left">
    <div v-if="channelsData && channelsData.length != 0" >
      <channel
          :active-channel="activeChannel"
          @click.native="changeChannel(key)"
          v-for="(channel, key) in channelsData"
          :key="key"
          :count-notification="countNotification"
          :channel="channel"
      />
    </div>
    <div v-else>
      <b-alert
          show
          variant="info"
      >
        Chats not found
      </b-alert>
    </div>
  </div>
</template>

<script>
const channel = () => import("./ChannelMessage");
export default {
  name:"MessangerChannels",
  props:[
    'channels', 'countNotification',
  ],
  computed:{
    channelsData(){
      return JSON.parse(this.channels);
    }
  },
  data(){
    return{
      activeChannel: null,
    }
  },
  components:{
    channel
  },
  mounted () {
    const urlParams = new URLSearchParams(window.location.search);
    const userId = urlParams.get('id')
    this.openByUserId(userId);
  },
  methods:{
    openByUserId(userId) {
      const item = this.channelsData.find(item=>item.user_1==userId);
      const key = this.channelsData.indexOf(item)
      this.changeChannel(key);
    },
    changeChannel(key){
      let channel = this.channelsData[key];
      this.activeChannel = channel.id
      this.$emit("changeChannel",{
        "channel": channel.id,
        "users":`${channel.user_1},${channel.user_2}`
      })
    }
  },

}
</script>
