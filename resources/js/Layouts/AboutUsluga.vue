<script setup>
import Chart from '@/Components/Chart.vue';
import { ref } from 'vue';

const props = defineProps({
  usluga: Object,
  lawyer: Object,
  main_usluga: Object,
  reviewscount: Number,
  rating: Number,
  statistics: Object,
  auth: Object,
});

const showButton = ref(props.auth && props.auth.lawyer == 1);

function toggleButton() {
  showButton.value = !showButton.value;
}
</script>

<template>
  <!-- secondary banner - about -->
  <section class="bg-white border-b-4 border-indigo-500" id="about">
    <h1 itemprop="name" class="p-2 lg:p-6 lg:py-12 text-4xl tracking-tight font-bold text-gray-900 text-center">
      {{ props.usluga.usl_name }}
    </h1>
    <div class="gap-4 items-center justify-items-center px-4 mx-auto max-w-screen-xl grid lg:px-6">
      <div class="mt-4 md:mt-0">
        <div class="mt-2 font-bold text-gray-700 text-3xl text-center lg:text-3xl" itemprop="brand">
          {{  props.lawyer.name }}
        </div>
        <div class="font-medium text-gray-900 text-xl lg:text-2xl text-center" itemprop="category">
          {{  props.main_usluga.usl_name }}.
          <span v-if=" props.usluga.cities">{{  props.usluga.cities.title }}</span>
        </div>
      </div>
      <div class="flex flex-col items-center justify-center h-full">
        <img itemprop="image" class="w-full md:w-1/2 text-center rounded-3xl"
          :src="'https://nedicom.ru/' +  props.usluga.file_path" :alt=" props.usluga.usl_name" />

        <div class="flex justify-between w-full md:w-1/2">
          <div v-if="reviewscount" class="w-full flex items-center justify-left mt-1">
            <svg class="w-4 h-4 text-yellow-300 me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
              fill="currentColor" viewBox="0 0 22 20">
              <path
                d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
            </svg>
            <p class="text-sm font-bold text-gray-900">{{  props.rating }}</p>

            <a href="#reviews" class="ml-1 text-sm font-medium text-gray-900 underline hover:no-underline">отзывы: {{
               props.reviewscount }}</a>
          </div>
          <div class="flex items-center justify-end gap-1">
            <button @click="toggleButton" type="button" data-tooltip-target="tooltip-quick-look"
              class="flex items-center gap-1 rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
              <span class="sr-only">перейти</span>
              <span class="text-xs">{{  props.usluga.counter }}</span>
              <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-width="2"
                  d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
              </svg>
            </button>
            <div id="tooltip-quick-look" role="tooltip" class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300 dark:bg-gray-700
            max-w-[calc(100vw-2rem)] w-auto min-w-[10rem]" data-popper-placement="bottom">
              Подробная статистика
              <div class="tooltip-arrow" data-popper-arrow=""></div>
            </div>
          </div>
        </div>
      </div>

      <Chart v-if="showButton" :statistics="props.statistics" />

      <p itemprop="description"
        class="mb-6 font-normal text-gray-900 text-base lg:text-xl leading-normal text-justify italic">
        {{  props.usluga.usl_desc }}
      </p>
    </div>
  </section>
  <!--secondary banner - about -->
</template>