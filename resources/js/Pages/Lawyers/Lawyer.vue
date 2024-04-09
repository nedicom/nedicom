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

let set = defineProps({
    statusonimage: String,
    mainbannerimg: String,
    lawyer: "$string",
    specializationOne: Object,
    specializationTwo: Object,
    specializationThree: Object,
    specialization: String,
    articles: "$Array",
    practice: Array,
    flash: Object,
});

let mainbannerpc =  'url("https://nedicom.ru/'+set.lawyer.file_path+'")';
let mainbannerimgmobile  = 'url("https://nedicom.ru/'+set.lawyer.avatar_path+'")';

let title = ref(set.lawyer.name);
</script>

<template>
    <FlashMessage :message="flash.message" />

    <Head title="Адвокат Симферополь" />

    <div class="min-h-screen">
        <MainHeader />

        <Header :ttl="title" />

        <Mainbanner
            :statusonimage="statusonimage"
            :nameonimage="set.lawyer.name"
            :mainbannerpc="mainbannerpc"
            :mainbannerimgmobile="mainbannerimgmobile"
        />

        <Address />

        <About :about="set.lawyer.about" />

        <Specialization
            :specializationOne="specializationOne"
            :specializationTwo="specializationTwo"
            :specializationThree="specializationThree"
            :specialization="specialization"
        />

        <LawyerRating :lawyer="set.lawyer"
        />

        <Articles :articles="articles" />

        <Slider :sliderheader="sliderheader" :sldimg="practice" :practice="set.practice" />

        <MainFooter />

        <PopupDialogue />
    </div>
</template>
