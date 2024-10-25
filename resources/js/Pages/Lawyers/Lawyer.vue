<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Mainbanner from "@/Layouts/Mainbanner.vue";
import Specialization from "@/Layouts/Specialization.vue";
import Slider from "@/Layouts/Slider.vue";
import Articles from "@/Layouts/Articles.vue";
import About from "@/Layouts/About.vue";
import Address from "@/Layouts/Address.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import LawyerRating from "@/Components/LawyerRating.vue";
import PopupDialogue from "@/Layouts/PopupDialogue/PopupDialogue.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { ref } from "vue";

/*defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
});*/

let sliderheader = ref("Практика юриста");
let robots = false;

let set = defineProps({
    statusonimage: String,
    mainbannerimg: String,
    lawyer: "$string",
    specialization: String,
    articles: "$Array",
    practice: Array,
    flash: Object,
    auth: Object,
});

(set.lawyer.lawyer == 1) ? robots = false : robots = true;

let mainbannerpc = 'url("https://nedicom.ru/' + set.lawyer.file_path + '")';
let mainbannerimgmobile = 'url("https://nedicom.ru/' + set.lawyer.avatar_path + '")';

let title = ref(set.lawyer.name);
</script>

<template>
    <FlashMessage :message="flash.message" />

    <Head>
        <title>{{ set.lawyer.name }}</title>
        <meta name="description" :content="'Юрист ' + set.lawyer.name" />
        <meta v-if="robots" name="robots" content="noindex">
        <meta v-else name="robots" content="all">
    </Head>
    <div class="min-h-screen">
        <MainHeader :auth="auth" />

        <Header :modalPageTitle="'юрист - ' + set.lawyer.name" />

        <Mainbanner :statusonimage="statusonimage" :nameonimage="set.lawyer.name" :mainbannerpc="mainbannerpc"
            :mainbannerimgmobile="mainbannerimgmobile" />

        <About :about="set.lawyer.about" />

        <!--
        <Specialization :specializationOne="specializationOne" :specializationTwo="specializationTwo"
            :specializationThree="specializationThree" :specialization="specialization" />
-->
        <LawyerRating :lawyer="set.lawyer" />

        <Articles :articles="articles" />

        <Slider :sliderheader="sliderheader" :sldimg="practice" :practice="set.practice" />

        <MainFooter />

        <PopupDialogue />
    </div>
</template>
