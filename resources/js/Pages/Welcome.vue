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
</script>

<template>
  <FlashMessage :message="flash.message" />

  <Head>
    <title>Услуги юриста: бесплатно, онлайн, телефон, задать вопрос, консультация</title>
    <meta name="description" content="Ресурс позволяет найти юриста по своей проблеме, получить консультацию юриста, задать вопрос юристу онлайн, поулчить телефон юриста. Оказываем услуги по \
    городу Москва и Республике Крым" />
    <meta property="og:title" content="Услуги юриста: бесплатно, онлайн, телефон, задать вопрос, консультация" />
    <meta property="og:description" content="Ресурс позволяет найти юриста по своей проблеме, получить консультацию юриста, задать вопрос юристу онлайн, поулчить телефон юриста. Оказываем услуги по \
    городу Москва и Республике Крым" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://nedicom.ru" />
    <meta property="og:image" :content="mainbannerimgmobile" />
    <meta property="og:site_name" content="nedicom.ru" />
    <meta property="og:locale" content="ru_RU" />
  </Head>

  <div class="min-h-screen" itemscope itemtype="https://schema.org/LegalService">
    <MainHeader :auth="auth" />

    <Header :modalPageTitle="'Модальное окно главная'" />

    <SecondBanner :statusonimage="'Юридическая компания МИНА'" :nameonimage="null" :phnform="true"
      :secondbannerimgmobile="secondbannerimgmobile" :secondbannerpc="secondbannerpc" />

    <SliderUslug :uslugislider="uslugislider" />

    <Mainbanner :mainbannerimgmobile="mainbannerimgmobile" :mainbannerpc="mainbannerpc" />

    <Slider :practice="practice" :sliderheader="'Практика юристов'" />

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