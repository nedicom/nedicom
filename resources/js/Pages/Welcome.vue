<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Mainbanner from "@/Layouts/Mainbanner.vue";
import SecondBanner from "@/Layouts/SecondBanner.vue";
import Testimonials from "@/Layouts/Testimonials.vue";
import Slider from "@/Layouts/Slider.vue";
import Youtube from "@/Layouts/Youtube.vue";
import SliderUslug from "@/Layouts/SliderUslug.vue";
import RatingReady from "@/Components/RatingReady.vue";
import Address from "@/Layouts/Address.vue";
//import VK from "@/Layouts/Vk.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import PopupDialogue from "@/Layouts/PopupDialogue/PopupDialogue.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { ref } from "vue";

defineProps({
  flash: Object,
  uslugislider: Array,
  practice: Array,
  reviews: Object,
  reviewscount: Number,
  rating: Number,
  users: Object,
  auth: Object,
});

let secondbannerimgmobile =
  "https://nedicom.ru/storage/images/landing/main/secondm.webp";
let secondbannerpc =
  "https://nedicom.ru/storage/images/landing/main/second.webp";

let mainbannerimgmobile =
  'url("https://nedicom.ru/storage/images/landing/main/firstm.webp")';
let mainbannerpc =
  'url("https://nedicom.ru/storage/images/landing/main/1280on600.webp")';

let statusonimage = ref("Юридическая компания");
let nameonimage = ref("Мина");

let title = "Юридическая компания 'Мина'";
let description =
  "8 978 8838 978 юридические услуги по г. Москва и по Республике Крым";
</script>

<template>
  <FlashMessage :message="flash.message" />

  <Head>
    <title>{{ title }}</title>
    <meta name="description" :content="description" />
  </Head>

  <div class="min-h-screen" itemscope itemtype="https://schema.org/LegalService">
    <MainHeader :auth="auth" />

    <Header :modalPageTitle="'Модальное окно главная'"/>

    <Mainbanner :mainbannerimgmobile="mainbannerimgmobile" :mainbannerpc="mainbannerpc" />

    <SliderUslug :uslugislider="uslugislider" />

    <SecondBanner :statusonimage="'© 2024 nedicom™'" :nameonimage="null" :phnform="true"
      :secondbannerimgmobile="secondbannerimgmobile" :secondbannerpc="secondbannerpc" />

    <Slider :practice="practice" :sliderheader="'Каждая проблема для нас особенная'" />

    <Testimonials />

    <!--reviews carousel-->

    <!--<Youtube />-->

    <!--<Address />-->

    <!--<VK />-->

    <MainFooter />

    <PopupDialogue />
  </div>
</template>

<script>
import { defineComponent } from "vue";
import { Carousel, Navigation, Slide } from "vue3-carousel";

import "vue3-carousel/dist/carousel.css";

export default defineComponent({
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
        itemsToShow: 3.5,
        snapAlign: "start",
      },
    },
  }),
});
</script>