<script setup>
import { reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Modal from "@/Components/Modal.vue";
import "vue3-carousel/dist/carousel.css";
import { defineComponent } from "vue";
import { Carousel, Navigation, Slide } from "vue3-carousel";

const props = defineProps({
  uslugislider: Array,
});

let ModalBtnText = "на консультацию";
</script>

<template>
  <h1 class="text-4xl font-semibold text-grey text-center py-10" itemprop="name">
    Юридическая компания "Мина"
  </h1>
  <div class="py-10 bg-gray-200">
    <Carousel v-bind="settings" :breakpoints="breakpoints">
      <Slide v-for="card in props.uslugislider" :key="card">
        <div class="carousel__item w-full mx-3">
          <!-- card -->
          <div
            class="w-full mx-1 rounded-lg border-1 border-gray-600 flex flex-col bg-white"
          >
            <div class="h-24 mx-3 grid grid-cols-1 content-center">
              <h5
                class="text-gray-900 subpixel-antialiased text-center text-lg line-clamp-2 font-bold px-1"
              >
                {{ card.usl_name }}
              </h5>
            </div>

            <div class="group flex items-center justify-center h-24">
              <div class="rounded-full">
                <img
                    v-if="card.firstlawyer"
                    :src="'https://nedicom.ru/' + card.firstlawyer.avatar_path"
                    width="120"
                    class="rounded-full border-2 border-white dark:border-gray-800"
                />
              
              </div>
            </div>

            <div class="h-24 grid grid-cols-1 content-center">
              <p class="text-gray-700/75 line-clamp-3 px-5">
                {{ card.usl_desc }}
              </p>
            </div>

            <div class="h-24 grid grid-cols-1 content-center">
              <Modal :ModalBtnText="ModalBtnText" />
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
