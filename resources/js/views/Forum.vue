<template>
  <div class="d-flex flex-column">
    <div class="container d-flex flex-column">
      <p class="breadcrumbs">
        <a href="/" style="color: #b3b3b3; text-decoration: none">UnrealShop</a> >
        Forum
      </p>
    </div>

    <div v-if="loaded" class="content-right double-column">
      <div class="container">
        <p class="block-title pt-2">Forum</p>
        <table class="w-100 table-pr" style="margin-top: 53px">
          <thead>
            <th class="mr-auto d-block">Forum</th>
            <th>Topics</th>
            <th>Posts</th>
          </thead>

          <tablewrapper
            v-for="(forum, key) in forums"
            :key="key"
            :forum="forum"
          ></tablewrapper>
        </table>
      </div>
    </div>
    <div v-else>
      <div class="container">
        <b-skeleton-table
          class="w-100"
          :rows="5"
          :columns="4"
          :table-props="{ bordered: true, striped: true }"
        ></b-skeleton-table>
      </div>
    </div>
  </div>
</template>

<script>
import tablewrapper from "../components/TableWrapper";

export default {
    name: "Forum",
    props: [
        "serverData"
    ],
    data() {
        return {
            loaded: false,
            forums: [],
        };
    },
    components: {
        tablewrapper,
    },
    mounted() {
        this.forums = this.serverData
        this.loaded = true
    },
};
</script>

