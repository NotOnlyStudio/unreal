<template>
    <div class="analytics border-standart p-3">
        <bar-chart
            :styles="{height: '320px'}"
            :chart-data="chartInfo"
            :options="options"
            v-if="loaded"
        />
    </div>
</template>

<script>
import VueApexCharts from 'vue-apexcharts'
import moment from 'moment';
import BarChart from "../../charts/barChart";
import {PricesChart} from "../../charts/mixins/pricesChart";


export default {
    name: "ChartItem",
    mixins: [PricesChart],
    props: ["purchasesCount"],
    components: {
        "apexchart": VueApexCharts,
        BarChart,
    },
    computed: {
        dates() {
            let dates = []
            for (let i = 0; i <= 31; i++) {
                dates.push(moment().subtract(i, 'd').format('d'))
            }
            return dates
        }
    },
    data: function () {
        return {
            loaded: false,
            options: {
                chart: {
                    id: 'vuechart-example'
                },
                xaxis: {
                    categories: this.dates
                }
            },
            series: [{
                name: 'Purchases count',
                data: this.purchasesCount,
            }]
        }
    },
    mounted() {
        this.options.categories = this.dates
        this.sortData(this.purchasesCount);
        this.loaded = true
    }
}
</script>
