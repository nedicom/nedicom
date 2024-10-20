<script setup>
import VueApexCharts from "vue3-apexcharts";


let set = defineProps({
    prices: Array,
})

let series = [];
let test = [];

//result = set.prices.map((item) => ({ price: item.price }));
set.prices.map((item) => series.push(Number(item.price)));
set.prices.map((item) => test.push(item.name));

const chartOptions = {
    chart: {
        width: 380,
        type: 'pie',
    },
    labels: test,
    responsive: [{
        breakpoint: 480,
        options: {
            chart: {
                width: 200
            },
            legend: {
                position: 'bottom'
            }
        }
    }]
};
</script>

<template>
    <VueApexCharts width="500" type="pie" :options="chartOptions" :series="series"></VueApexCharts>
</template>

<script>


</script>
