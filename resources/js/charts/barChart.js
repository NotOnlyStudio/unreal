import { Line , mixins } from 'vue-chartjs'
const { reactiveProp } = mixins

export default {
  extends: Line,
  mixins: [reactiveProp],
  props: {
    chartData: {
      type: Object,
      default: null
    },
    options: {
        responsive: true,
        legend: {
            display: false
        },
        title: {
            display: false,
            text: 'Chart.js bar Chart'
        },
        animation: {
            animateScale: true
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    callback: function (value) { if (Number.isInteger(value)) { return value; } },
                    stepSize: 1
                }
            }]
        }
    }
  },
  mounted () {
      this.updatex();
  },
    methods: {
      updatex()
      {
          this.renderChart(this.chartData, {responsive: true, maintainAspectRatio: false})
      }
    }

}
