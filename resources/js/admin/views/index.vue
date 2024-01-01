<template>
  <div class="charts-container">
    <div id="form-buttons">
      <button type="button" class="btn btn-success" @click="changeTime('day')">day</button>
      <button type="button" class="btn btn-success" @click="changeTime('week')">week</button>
      <button type="button" class="btn btn-success" @click="changeTime('month')">month</button>
      <button type="button" class="btn btn-success" @click="changeTime('year')">year</button>
    </div>

    <div class="row">
      <div class="col-lg-6 col-12">
        <models-chart
            v-if="modelsData.length != 0"
            :chart-data="modelsData"
            title-name="models"
        />
        <no-data v-else/>
      </div>
      <div class="col-lg-6 col-12">
        <models-chart
            v-if="uploaded.length != 0"
            :chart-data="uploaded"
            title-name="Uploaded models"
        />
        <no-data v-else/>
      </div>
    </div>
    <div class="row my-lg-3 my-0">
      <div class="col-lg-6 col-12">
        <models-chart
            v-if="refill.length != 0"
            :chart-data="refill"
            title-name="Account refill (purchased credits)"
        />
        <no-data v-else/>
      </div>
      <div class="col-lg-6 mt-5 col-12">
        <models-chart
            v-if="usersData.length != 0"
            :chart-data="usersData"
            title-name="users"
        />
        <no-data v-else/>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import ModelsChart from "../components/charts/ModelsChart"
import ChartNoData from "../components/charts/chartNoData"

export default {
  name: "index",
  data() {
    return {
      loaded: false,
      modelsData: [],
      galleryData: [],
      blogData: [],
      usersData:[],
      uploaded:[],
      refill:[],
      time: 'day',
    };
  },
  props: ["chartData"],
  components: {
    "models-chart":ModelsChart,
    "no-data":ChartNoData,
  },
  methods: {
    getInfo() {
      axios.get("/statistic/get",{params: { time: this.time}}
      ).then((resp) => {
        this.modelsData = resp.data.products;
        this.usersData = resp.data.users;
        this.uploaded = resp.data.uploaded;
        this.refill = resp.data.refill;
        this.loaded = true;
        console.log(this.modelsData)
      });
    },
    changeTime(name) {
      this.time = name;
      this.getInfo();
    }

  },
  mounted() {
    this.getInfo();
  },
};
</script>

<style scoped>
.col-12{
  display: flex;
  align-items: center;
  justify-content: center;
}
.charts-container{
  padding-bottom: 100px;
  max-width: 1000px;
  padding-top: 50px;
}
</style>
