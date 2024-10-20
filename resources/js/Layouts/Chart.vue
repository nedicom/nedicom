<script setup>
/*
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
        locales: [{
            "name": "ru",
            "options": {
                "months": [
                    "Январь",
                    "Февраль",
                    "Март",
                    "Апрель",
                    "Май",
                    "Июнь",
                    "Июль",
                    "Август",
                    "Сентябрь",
                    "Октябрь",
                    "Ноябрь",
                    "Декабрь"
                ],
                "shortMonths": [
                    "Янв",
                    "Фев",
                    "Мар",
                    "Апр",
                    "Май",
                    "Июн",
                    "Июл",
                    "Авг",
                    "Сен",
                    "Окт",
                    "Ноя",
                    "Дек"
                ],
                "days": [
                    "Воскресенье",
                    "Понедельник",
                    "Вторник",
                    "Среда",
                    "Четверг",
                    "Пятница",
                    "Суббота"
                ],
                "shortDays": [
                    "Вс",
                    "Пн",
                    "Вт",
                    "Ср",
                    "Чт",
                    "Пт",
                    "Сб"
                ],
                "toolbar": {
                    "exportToSVG": "Сохранить SVG",
                    "exportToPNG": "Сохранить PNG",
                    "exportToCSV": "Сохранить CSV",
                    "menu": "Меню",
                    "selection": "Выбор",
                    "selectionZoom": "Выбор с увеличением",
                    "zoomIn": "Увеличить",
                    "zoomOut": "Уменьшить",
                    "pan": "Перемещение",
                    "reset": "Сбросить увеличение"
                }
            }
        }],
        defaultLocale: 'ru',
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
    const observer = new IntersectionObserver(function (entries, observer) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                obs.value = true;
                observer.disconnect()
            }
        })
    }, options)
    observer.observe(sections)
})
    */
</script>

<template>
    <div id="apexchart">
        <VueApexCharts v-if="obs" width="500" type="pie" :options="chartOptions" :series="series"></VueApexCharts>
    </div>
</template>