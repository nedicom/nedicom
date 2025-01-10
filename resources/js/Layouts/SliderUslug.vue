<script setup>
import { reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Modal from "@/Components/Modal.vue";
import "vue3-carousel/dist/carousel.css";
import { defineComponent } from "vue";
import { Carousel, Navigation, Slide } from "vue3-carousel";

const props = defineProps({
  secondoffers: Object,
  city: String,
});

let ModalBtnText = "на консультацию";
</script>

<template>
  <div class="py-10 mt-20 bg-slate-100">
    <Carousel v-bind="settings" :breakpoints="breakpoints">
      <Slide v-for="card in props.secondoffers" :key="card">
        <div class="carousel__item w-full mx-1 bg-white rounded-lg">
          <!-- card -->
          <a :href="'/uslugi/' + props.city + '/' + card.url" class="">
            <img
              :src="'https://nedicom.ru/' + card.file_path"
              class="rounded-t-lg"
            />
            <h3
              class="h-12 mb-2 text-xs tracking-tight subpixel-antialiased text-center text-gray-900 line-clamp-3"
            >
              {{ card.usl_name }}
            </h3>
          </a>
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
      itemsToShow: 2.25,
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
        itemsToShow: 8.5,
        snapAlign: "start",
      },
    },
  }),
});
</script>
