<script setup>
import VueApexCharts from "vue3-apexcharts";
import { ref, onMounted } from "vue";


let set = defineProps({
    prices: Array,
})

let series = [];
let test = [];

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

let obs = ref(false);

onMounted(() => {
    const sections = document.querySelector('#apexchart')
    const options = {
        threshold: 0.5
    }
    const observer = new IntersectionObserver(function(entries, observer) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                obs.value = true;
                observer.disconnect()
            }
        })
    }, options)
    observer.observe(sections)
})
</script>

<template>
    <div id="apexchart">
        <VueApexCharts v-if="obs" width="500" type="pie" :options="chartOptions" :series="series"></VueApexCharts>
    </div>
</template>

<script>


</script>
