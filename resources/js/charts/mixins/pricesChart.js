export let PricesChart = {
    props:["Purchases for price"],
    data(){
        return{
            chartInfo:{
                labels:[],
                datasets:[
                    {
                        label:"Purchases for price",
                        data:[],
                        backgroundColor: '#d4d2bd',
                        fill: false,
                        borderColor: '#ff7a70',
                    }
                ], step:10
            },
            options:{
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
        }
    },
    methods:{
        sortData(data){
            console.clear();
            data.forEach(item => {
                this.chartInfo.labels.push(item.day.split("-").reverse().join("."))
                this.chartInfo.datasets[0].data.push(item.price)                
            });
            console.log(this.chartInfo)
            this.render = true
        }
    },
}