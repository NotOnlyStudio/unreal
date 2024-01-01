<template>
  <div class="chart-data d-flex flex-column">
        <p class="title">{{titleName}}</p>
        <hr>
        <bar-chart
            :styles="{height: '320px'}"
            v-if="rendering && chartData.length != 0"
            :chart-data="this.chartInfo"
            :options="options"
        />
    </div>
</template>


<script>
import BarChart from "../../../charts/barChart";
import {DataFilter} from "../../../charts/mixins/dataFilter";
export default {
    name:"ModelsChart",
    mixins:[DataFilter],
    props:["chartData"],
    components:{
        BarChart,
    },
    methods: {
      updateInfo(){
        this.sortData(this.chartData);
      },
      updateTime()
      {
        this.rendering = true;
      }
    },
    mounted(){
      this.updateInfo();
      setTimeout(this.updateTime, 100);
    },
  watch: {
    chartData: function() {
      this.rendering = false;
      this.updateInfo();
      setTimeout(this.updateTime, 100);
    }
  }
}
</script>

<style >
    .chart-data{
        width: 400px;
        height: 350px;
    }
        .title{
        font-family: Arial, Helvetica, sans-serif;
        font-size: 20px;
        margin-bottom: 10px;
        color: #d4d2bd
    }
    .title::first-letter{
        text-transform: uppercase;
    }
    .title+hr{
        width: 100%;
        background: #d4d2bd;
        height: 2px;
        opacity: 1;
    }
</style>
