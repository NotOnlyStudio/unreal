export let DataFilter = {
    props:["titleName"],
    data(){
        return{
            rendering: false,
            chartInfo:{
                labels:[],
                datasets:[
                    {
                        label:"New "+this.titleName,
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
            this.chartInfo.labels.splice(0,this.chartInfo.labels.length);
            this.chartInfo.datasets[0].data.splice(0,this.chartInfo.datasets[0].data.length);
            data.forEach(item => {
                this.chartInfo.labels.push(item.day.split("-").reverse().join("."))
                this.chartInfo.datasets[0].data.push(item.count)
            });
        }
    },
}
