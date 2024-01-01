<template>
  <a class="search" @click.prevent="getSerch">
    <svg
      xmlns="http://www.w3.org/2000/svg"
      width="37.621"
      height="38.621"
      viewBox="0 0 37.621 38.621"
    >
      <g id="Group_15" data-name="Group 15" transform="translate(-1817 -860)">
        <line
          id="Line_1"
          data-name="Line 1"
          x1="10"
          y1="10"
          transform="translate(1842.5 886.5)"
          fill="none"
          stroke="#000"
          stroke-linecap="round"
          stroke-width="3"
        />
        <g
          id="Ellipse_1"
          data-name="Ellipse 1"
          transform="translate(1817 860)"
          fill="none"
          stroke="#000"
          stroke-width="3"
        >
          <circle cx="15.5" cy="15.5" r="15.5" stroke="none" />
          <circle cx="15.5" cy="15.5" r="14" fill="none" />
        </g>
      </g>
    </svg>
  </a>
</template>


<script>

export default({
    name:"SearchBtn",
    methods:{
        getSerch(){
            this.$emit(
                "getSerch",{
                    "error":false
                }
            )
        }
    }
})
</script>
