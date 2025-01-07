<script setup>
import { reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Modal from "@/Components/Modal.vue";
import "vue3-carousel/dist/carousel.css";
import { defineComponent } from "vue";
import { Carousel, Navigation, Slide } from "vue3-carousel";

const props = defineProps({
  secondoffers: Object,
});

let ModalBtnText = "на консультацию";
</script>

<template>
  <div class="py-10 mt-20 bg-slate-100">
    <Carousel v-bind="settings" :breakpoints="breakpoints">
      <Slide v-for="card in props.secondoffers" :key="card">
        <div class="carousel__item w-full mx-3">
          <!-- card -->
          <div
            class="w-full mx-1 rounded-lg border-1 border-gray-600 flex flex-col bg-white"
          >
            <div class="h-24 mx-3 grid grid-cols-1 content-center">
              <h3
                class="text-sm text-gray-900 subpixel-antialiased text-center line-clamp-2 px-1"
              >
                {{ card.usl_name }}
              </h3>
            </div>

            <div class="h-24 grid grid-cols-1 content-center">
              <p class="text-sm text-gray-700/75 line-clamp-3 px-5">
                {{ card.usl_desc }}
              </p>
            </div>

            <!--
            <div class="h-24 grid grid-cols-1 content-center">
              <Modal :ModalBtnText="ModalBtnText" />
            </div>
            -->
          </div>

          <!-- card -->
        </div>
      </Slide>
    </Carousel>
  </div>
</template>

<script>
import { defineComponent } from "vue";
import { Carousel, Slide } from "vue3-carousel";

import "vue3-carousel/dist/carousel.css";

export default defineComponent({
  uslugislider: "",
  name: "Breakpoints",
  components: {
    Carousel,
    Slide,
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
        itemsToShow: 5.5,
        snapAlign: "center",
      },
      // 1024 and up
      1024: {
        itemsToShow: 10.5,
        snapAlign: "start",
      },
    },
  }),
});
</script>
