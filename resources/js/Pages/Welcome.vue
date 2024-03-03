<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Mainbanner from "@/Layouts/Mainbanner.vue";
import SecondBanner from "@/Layouts/SecondBanner.vue";
import Testimonials from "@/Layouts/Testimonials.vue";
import Slider from "@/Layouts/Slider.vue";
import SliderUslug from "@/Layouts/SliderUslug.vue";
import Address from "@/Layouts/Address.vue";
//import VK from "@/Layouts/Vk.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import PopupDialogue from "@/Layouts/PopupDialogue/PopupDialogue.vue";
import Tg from "@/Layouts/TG/TeleGram.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { ref } from "vue";

defineProps({
  flash: Object,
  uslugislider: Array,
  practice: Array,
});

let screen = window.innerWidth;

let secondbannerimg = new Image();
secondbannerimg.fetchPriority = "high";

let mainbannerimg = ref(
  "https://nedicom.ru/storage/images/landing/main/1280on600.webp"
);
secondbannerimg.src = "storage/images/landing/main/second.webp";

  if(screen < 1024){
      mainbannerimg = ref("https://nedicom.ru/storage/images/landing/main/firstm.webp");
      secondbannerimg.src = "storage/images/landing/main/secondm.webp";
    }
  
let sldimg = ref([
  { id: 1, practice_file_path: "/storage/images/landing/main/slider/1.webp" },
  { id: 2, practice_file_path: "/storage/images/landing/main/slider/2.webp" },
  { id: 3, practice_file_path: "/storage/images/landing/main/slider/3.webp" },
  { id: 4, practice_file_path: "/storage/images/landing/main/slider/4.webp" },
]);

let title = ref("Главная");
let statusonimage = ref("Адвокатский кабинет");
let nameonimage = ref("Мина Марк Анатольевич");
</script>

<template>
  <FlashMessage :message="flash.message" />

  <Head title="Адвокатский кабинет Мина Марк Анатольевич" />

  <div class="min-h-screen">
    <MainHeader />

    <Header :ttl="title" />

    <Mainbanner :mainbannerimg="mainbannerimg" />

    <SliderUslug :uslugislider="uslugislider" />

    <SecondBanner
      :statusonimage="statusonimage"
      :nameonimage="nameonimage"
      :secondbannerimg="secondbannerimg.src"
    />

    <Slider
      :sldimg="sldimg"
      :practice="practice"
      :sliderheader="'Каждая проблема для нас особенная'"
    />

    <Testimonials />

    <!--<Address />-->

    <!--<VK />-->

    <MainFooter />

    <Tg />

    <PopupDialogue />
  </div>
</template>
