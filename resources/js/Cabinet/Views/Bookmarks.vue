<template>
  <div>
    <div class="d-flex profile-menu-top w-100 justify-content-between mt-2">
      <form class="search__form user-search search-sm" method="GET">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="23.206"
          height="23.206"
          viewBox="0 0 23.206 23.206"
        >
          <g
            id="Group_16"
            data-name="Group 16"
            transform="translate(-41589.794 -6523)"
          >
            <g
              id="Ellipse_2"
              data-name="Ellipse 2"
              transform="translate(41595 6523)"
              fill="none"
              stroke="#707070"
              stroke-width="1"
            >
              <circle cx="9" cy="9" r="9" stroke="none"></circle>
              <circle cx="9" cy="9" r="8.5" fill="none"></circle>
            </g>
            <line
              id="Line_2"
              data-name="Line 2"
              y1="7"
              x2="8"
              transform="translate(41590.5 6538.5)"
              fill="none"
              stroke="#707070"
              stroke-linecap="round"
              stroke-width="1"
            ></line>
          </g>
        </svg>
        <input type="text" placeholder="Search" name="searchBy" :value="this.$route.query.searchBy"/>
      </form>
      <navigation/>
    </div>
    <router-view />
  </div>
</template>

<script>
import navigation from "../Components/BookmarkNav"
export default {
  name: "Bookmarks",
  computed: {
      path(){
         return window.location.pathname;
      }
  },
  components:{
    navigation
  }
};
</script>

