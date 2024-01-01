<template>
  <div
    v-if="!loaded"
    class="d-flex flex-column messanger-right position-relative"
  >
    <preloader />
  </div>
  <div v-else class="d-flex flex-column messanger-right position-relative">
    <b-alert
      v-if="!allMessanges || allMessanges.length == 0"
      variant="info"
      show
    >
      Messanges not found
    </b-alert>
    <div class="content" v-else>
      <div v-if="reloaded">
        <a href="/load-more" v-if="countMessages > allMessanges.length" @click.prevent="loadMoreMessanges">Load more</a>
        <message
          v-for="(message, key) in allMessanges"
          :key="key"
          :guest="guest"
          :message="message"
          :current-user="currentUser"
        />
        <sound v-if="sound"/>
      </div>
    </div>
    <send-form @newMessage="sendMessage" />
  </div>
</template>


<script>
const message = () => import("./MessageItem");
const delimeter = () => import("./MessageDelimeter");
import MessageForm from "./MessageForm";
import axios from "axios";
import preloader from "./ChatPreloader";
const sound = () => import("./MessageSound")
export default {
  name: "MessangerChat",
  props: ["messanges", "currentChannel","guest","currentUser","countMessages"],
  components: {
    message,
    delimeter,
    preloader,
    sound,
    "send-form": MessageForm,
  },
  data() {
    return {
      allMessanges: this.messanges,
      loaded: true,
      reloaded: true,
      messangesCount: 50,
      sound: false,
    };
  },
  methods: {
    sendMessage(data) {
      let message = data.message;
      axios.defaults.headers = {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      }
      axios.defaults.withCredentials = true;
      axios
        .post("/new-message", {
          room_id:this.currentChannel,
          messange_body: message,
          to_user:this.guest,
        })
        .then((resp) => {
          if (resp.status === 200) {
            this.allMessanges == undefined ? [] : this.allMessanges;
            this.reloaded = false;
            this.allMessanges.push(resp.data.messange);
            this.newmessage(message);
            this.$nextTick(() => {
              this.reloaded = true;
            });
          } else {
            console.warn("Error not sended");
          }
        });
    },
    newmessage(message){
      this.$emit("newmessage",{
        "message_body":message,
      })
    },
    loadMoreMessanges(){
      let count_mes = this.allMessanges.length
      axios.post("/get-messages?channel="+this.currentChannel+"&messanges-count="+count_mes)
      .then(
        resp => {
          thsi.count_mes.push(resp.data.messanges)
        }
      )
    }
  },
  mounted() {
    window.Echo.private('room.'+this.currentChannel)
      .listen('.PrivateMessage', (data) => {
        this.allMessanges == undefined ? [] : this.allMessanges;
        this.sound = true
        this.allMessanges.push(data.message)
        this.newmessage(data.message.message_body);
        axios.post(`/chat/read/${this.currentChannel}`).then(
          resp => {
            console.clear()
            console.log(resp)
          }
        )
        setTimeout(
          () => {
            this.sound = false
          }, 1000
        )
      });
  },
};
</script>
