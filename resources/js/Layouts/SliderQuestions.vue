<script setup>
import "vue3-carousel/dist/carousel.css";
import { defineComponent } from "vue";
import { Carousel, Navigation, Slide } from "vue3-carousel";

const set = defineProps({
  sliderq: Array,
});

</script>

<template>
  <h2 v-if="!set.sliderq[0].status" class="text-4xl font-semibold text-grey text-center py-10">
    {{ set.sliderq[0].status }}
    Последние 20 вопросов юристам 
  </h2>
  <h2 v-else class="text-4xl font-semibold text-grey text-center py-10">
    Мы нашли похожие вопросы
  </h2>

  <div class="py-10 bg-gray-200">
    <Carousel v-bind="settings" :breakpoints="breakpoints">
      <Slide v-for="card in set.sliderq" :key="card">
        <div class="carousel__item w-full mx-3">
          <!-- card -->
          <div
            class="w-full mx-1 rounded-lg border-1 border-gray-600 flex flex-col bg-white"
          >
            <div class="h-24 mt-3 mx-3 grid grid-cols-1 content-center">
              <h3
                class="text-gray-900 subpixel-antialiased text-center text-lg line-clamp-2 font-bold px-1"
              >
                {{ card.title }}
              </h3>
            </div>

            <div class="h-24 mb-3 grid grid-cols-1 content-center">
              <p class="text-gray-700/75 line-clamp-3 px-5">
                {{ card.body }}
              </p>
            </div>

            <p class="text-gray-500">
              ответили:
              <span
                v-if="card.quantity_ans_count"
                class="inline-flex items-center ml-2 rounded-full bg-blue-500 px-2 py-1 text-xs font-bold text-white"
                >{{ card.quantity_ans_count }}</span
              >
              <span
                v-else
                class="inline-flex items-center ml-2 rounded-full bg-gray-500 px-2 py-1 text-xs font-bold text-white"
                >0</span
              >
            </p>

            <div class="h-24 grid grid-cols-1 content-center">
              <a
                :href="'https://nedicom.ru/questions/' + card.url"
                class="my-3 text-sm text-gray-700/75"
                >подробнее</a
              >
            </div>
          </div>

          <!-- card -->
        </div>
      </Slide>

      <template #addons>
        <Navigation />
      </template>
    </Carousel>
  </div>
</template>

<script>
import { defineComponent } from "vue";
import { Carousel, Navigation, Slide } from "vue3-carousel";

import "vue3-carousel/dist/carousel.css";

export default defineComponent({
  uslugislider: "",
  name: "Breakpoints",
  components: {
    Carousel,
    Slide,
    Navigation,
  },
  data: () => ({
    // carousel settings
    settings: {
      itemsToShow: 1.25,
      snapAlign: "center",
    },
    // breakpoints are mobile first
    // any settings not specified will fallback to the carousel settings
    breakpoints: {
      // 700px and up
      700: {
        itemsToShow: 3.5,
        snapAlign: "center",
      },
      // 1024 and up
      1024: {
        itemsToShow: 4.5,
        snapAlign: "start",
      },
    },
  }),
});
</script>
