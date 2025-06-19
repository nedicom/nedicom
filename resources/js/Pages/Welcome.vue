<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Mainbanner from "@/Layouts/Mainbanner.vue";
import FrontSecondBanner from "@/Layouts/FrontSecondBanner.vue";
import PracticeArticles from "@/Layouts/PracticeArticles.vue";
import FrontBanner from "@/Layouts/FrontBanner.vue";
import Pile from "@/Layouts/Pile.vue";
import FrontQuestion from "@/Layouts/FrontQuestion.vue";
import Testimonials from "@/Layouts/Testimonials.vue";
import Slider from "@/Layouts/Slider.vue";
import Youtube from "@/Layouts/Youtube.vue";
import SliderUslug from "@/Layouts/SliderUslug.vue";
import InfinityScroll from "@/Layouts/InfinityScroll.vue";
import RatingReady from "@/Components/RatingReady.vue";
import Address from "@/Layouts/Address.vue";
//import VK from "@/Layouts/Vk.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import PopupDialogue from "@/Layouts/PopupDialogue/PopupDialogue.vue";
import Bundle from "@/Components/Bundle.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import { Head } from "@inertiajs/inertia-vue3";
import Chat from "@/Layouts/Chat.vue";

import { ref } from "vue";

const showAnimation = ref(true);

defineProps({
  city: Object,
  flash: Object,
  mainoffers: Object,
  secondoffers: Object,
  practice: Array,
  reviews: Object,
  reviewscount: Number,
  rating: Number,
  users: Object,
  auth: Object,
  bundles: Object,
  user: Object,
  usluga: Object,
});

let secondbannerimgmobile =
  "https://nedicom.ru/storage/images/landing/main/nmainmob.webp";
let secondbannerpc =
  "https://nedicom.ru/storage/images/landing/main/nmainpc.webp";

let mainbannerimgmobile =
  'url("https://nedicom.ru/storage/images/landing/main/nmainmob.webp")';
let mainbannerpc = 'url("/storage/images/services/mainpcjune3.png")';
</script>

<template>
  <FlashMessage :message="flash.message" />

  <Head>
    <title>
      Услуги юриста: от консультаций до судебных прогнозов
    </title>
    <meta
      name="description"
      content="Услуги проверенных юристов. Консультации, вопросы, онлайн."
    />
    <meta
      property="og:title"
      content="Услуги юриста: бесплатно, онлайн, телефон, задать вопрос, консультация"
    />
    <meta
      property="og:description"
      content="Услуги проверенных юристов. Консультации, вопросы, онлайн."
    />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://nedicom.ru" />
    <meta property="og:image" :content="secondbannerpc" />
    <meta property="og:site_name" content="nedicom.ru" />
    <meta property="og:locale" content="ru_RU" />
    <link rel="canonical" href="https://nedicom.ru" />
  </Head>

  <div
    class="min-h-screen"
    itemscope
    itemtype="https://schema.org/LegalService"
  >
    <MainHeader :auth="auth" :city="city" />
    <!--
    <Header :modalPageTitle="'Модальное окно главная'" />
    -->
    

    <Mainbanner :mainbannerpc="mainbannerpc" />

    <FrontSecondBanner :city="city" />

    

    <!--
    <FrontBanner
      :statusonimage="'Юридическая компания МИНА'"
      :phnform="true"
      :secondbannerimgmobile="secondbannerimgmobile"
      :secondbannerpc="secondbannerpc"
    />
    -->

    <!--
    <SliderUslug :secondoffers="secondoffers" :city="city.url" />-->

    <InfinityScroll :bundles="bundles" :auth="auth" />

    <Chat :user="user" :usluga="usluga" />

    <FrontQuestion
      :mainoffers="mainoffers"
      :secondoffers="secondoffers"
      :city="city"
    />

    <Pile
      :uslugislider="mainoffers"
      :secondoffers="secondoffers"
      :city="city.url"
    />

    <!--<Mainbanner :mainbannerimgmobile="mainbannerimgmobile" :mainbannerpc="mainbannerpc" />-->

    <!--<Slider :practice="practice" :sliderheader="'Практика юристов'" />-->

    <!--<Testimonials />-->

    <!--reviews carousel-->

    <!--<Youtube />-->

    <!--<Address />-->

    <!--<VK />-->

    <MainFooter />

    <!--<PopupDialogue />-->
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