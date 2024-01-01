<template>
  <div>
    <table class="table table-stripped">
      <thead class="thead-dark">
        <th>Name</th>
        <th>Link</th>
        <th>Edit</th>
      </thead>
      <tbody>
        <tr>
          <td>Terms of use</td>
          <td>
            <b-button variant="primary"><b-icon-link /></b-button>
          </td>
          <td>
            <b-button @click.prevent.stop="getForm('terms-of-use')" href="/admin/polytics/terms-of-use" variant="info"
              ><b-icon-vector-pen
            /></b-button>
          </td>

        </tr>
        <tr>
          <td>Privacy policy</td>
          <td>
            <b-button variant="primary"><b-icon-link /></b-button>
          </td>
          <td>
            <b-button @click.prevent.stop="getForm('privacy-policy')" href="/admin/polytics/privacy-policy" variant="info"
              ><b-icon-vector-pen
            /></b-button>
          </td>

        </tr>
        <tr>
          <td>Cookie policy</td>
          <td>
            <b-button variant="primary"><b-icon-link /></b-button>
          </td>
          <td>
            <b-button @click.prevent.stop="getForm('cookie-policy')" href="/admin/polytics/cookie-policy" variant="info"
              ><b-icon-vector-pen
            /></b-button>
          </td>

        </tr>
        <tr>
          <td>Content policy</td>
          <td>
            <b-button variant="primary"><b-icon-link /></b-button>
          </td>
          <td>
            <b-button @click.prevent.stop="getForm('content-policy')" href="/admin/polytics/content-policy" variant="info"
              ><b-icon-vector-pen
            /></b-button>
          </td>
        </tr>
      </tbody>
    </table>
    <b-modal ref="modal" ok-title="Upload" title="Upload file" @close="activeType = 0" @hide="activeType = 0" @ok="sendFile">
        <form ref="polytic" enctype="multipart/form-data" action="">
          <b-input hidden name="type" :value="activeType"/>
          <b-file name="polytics_file" placeholder="Choose file" accept="application/pdf" />
        </form>
    </b-modal>
  </div>
</template>

<script>
import axios from "axios"

export default {
  name: "Polytics",
  data() {
    return {
      activePolytic: "",
      activeType:"",
    };
  },
  methods:{
    getForm(type){
      this.activeType = type
      this.$refs['modal'].show();
    },
    sendFile(){
      let formData = new FormData(this.$refs.polytic)
      let config = { 
        headers: { 'Content-Type': 'multipart/form-data' },
      }
      axios.post("/set-policy",formData,config)
      .then(
        resp => {
          if(resp.status == 200){
            this.makeToast("File was updated")
          }
        }
      )
    },
   
 
    makeToast(message,append = false) {
            this.$bvToast.toast(message, {
            title: 'UnrealShop notification',
            autoHideDelay: 5000,
            appendToast: append,
            variant: "success"
        })
    },
  }
};
</script>