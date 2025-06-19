<script setup>
import "vue3-carousel/dist/carousel.css";
import { defineComponent } from "vue";
import { Carousel, Navigation, Slide } from "vue3-carousel";

const set = defineProps({
  sliderq: Array,
});

</script>

<template>
  <h2 v-if="!set.sliderq[0].status" class="text-4xl font-semibold text-grey text-center py-10" id="question-article">
    {{ set.sliderq[0].status }}
    Какие вопросы нам задают
  </h2>
  <h2 v-else class="text-4xl font-semibold text-grey text-center py-10">
    Мы нашли похожие вопросы
  </h2>

  <div class="py-10 bg-gray-200">
    <Carousel v-bind="settings" :breakpoints="breakpoints">
      <Slide v-for="card in set.sliderq" :key="card">
        <div class="carousel__item w-full mx-3">
          <!-- card -->
          <div class="w-full mx-1 rounded-lg border-1 border-gray-600 flex flex-col bg-white py-5">
            <div class="h-24 mx-3 grid grid-cols-1 content-center">
              <h3 class="text-gray-900 subpixel-antialiased text-center text-lg line-clamp-2 font-bold px-1 hover:underline">
                <a :href="'https://nedicom.ru/questions/' + card.url">{{ card.title }}</a>
              </h3>
            </div>

            <div class="h-24 grid grid-cols-1 content-center">
              <p class="text-gray-700/75 line-clamp-3 px-5">
                {{ card.body }}
              </p>
            </div>

            <p v-if="card.quantity_ans_count" class="text-gray-500 h-12 flex  justify-center items-center" >
              <a :href="'https://nedicom.ru/questions/' + card.url"
                class="my-3 text-sm text-gray-700/75 hover:underline">ответили</a>
              <span
                class="inline-flex items-center ml-2 rounded-full bg-blue-500 px-2 py-1 text-xs font-bold text-white w-5 h-5">{{
                  card.quantity_ans_count }}</span>
                  
            </p>
            <p v-else class="text-gray-500 h-12 grid grid-cols-1 content-center" >
              <a :href="'https://nedicom.ru/questions/' + card.url"
                class="my-3 text-sm text-gray-700/75 hover:underline">подробнее</a>
            </p>
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
