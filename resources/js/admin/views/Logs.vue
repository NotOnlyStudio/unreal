<template>
  <div class="overflow-auto">
    <b-pagination
        v-model="currentPage"
        :total-rows="rows"
        :per-page="perPage"
        aria-controls="my-table"
    ></b-pagination>

    <p class="mt-3">Current Page: {{ currentPage }}</p>

    <b-table
        id="my-table"
        :items="laravelData"
        :per-page="perPage"
        :current-page="currentPage"
        small
    >
    </b-table>
  </div>
</template>


<script>
import spinner from "../components/Spinner";
import editor from "@tinymce/tinymce-vue";
import axios from "axios";
import pagination from "laravel-vue-pagination";

export default {
  name: "Logs",
  data() {
    return {
      secrKey: 0,
      perPage: 15,
      currentPage: 1,
      rows: 0,
      loaded: false,
      laravelData: {},
      logs: [],
    };
  },
  components: {
    spinner,
    editor,
    pagination,
  },
  methods: {
    getData() {
      this.loaded = true;
      axios.get("/get-logs").then((resp) => {
        this.laravelData = resp.data;
        this.rows = this.laravelData.length
        this.laravelData.forEach( el=> el.url = 'https://unreal.shop/models/'+el.url)
      });
    },
  },
  mounted() {
    this.getData();
    document.addEventListener('focusin', function (e) {
      let closest = e.target.closest(".tox-tinymce-aux, .tox-dialog, .moxman-window, .tam-assetmanager-root");
      if (closest !== null && closest !== undefined) {
        e.stopImmediatePropagation();
      }
    });
  },
};
</script>
