<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Mainbanner from "@/Layouts/Mainbanner.vue";
import Slider from "@/Layouts/Slider.vue";
import ReviewCarousel from "@/Layouts/ReviewCarousel.vue";
import Articles from "@/Layouts/Articles.vue";
import About from "@/Layouts/About.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import LawyerRating from "@/Components/LawyerRating.vue";
import { Head } from "@inertiajs/inertia-vue3";

let robots = false;

let set = defineProps({
    statusonimage: String,
    mainbannerimg: String,
    lawyer: Object,
    specialization: String,
    articles: "$Array",
    practice: Array,
    flash: Object,
    auth: Object,
    errors: Object,
});

(set.lawyer.lawyer == 1) ? robots = false : robots = true;

let description = (set.lawyer.about) ? set.lawyer.about : set.lawyer.name

let mainbannerpc = 'url("https://nedicom.ru/' + set.lawyer.file_path + '")';
let mainbannerimgmobile = 'url("https://nedicom.ru/' + set.lawyer.avatar_path + '")';

</script>

<template>
    <FlashMessage :message="flash.message" />

    <Head>
        <title>Юрист - {{ set.lawyer.name }}</title>
        <meta name="description" :content="description" />
        <meta v-if="robots" name="robots" content="noindex">
        <meta v-else name="robots" content="all">
    </Head>
    <div class="min-h-screen">
        <MainHeader :auth="auth" />

        <Header :modalPageTitle="'юрист - ' + set.lawyer.name" />

        <Mainbanner :statusonimage="statusonimage" :nameonimage="set.lawyer.name" :mainbannerpc="mainbannerpc"
            :mainbannerimgmobile="mainbannerimgmobile" />

        <About :about="description" :name="set.lawyer.name" />

        <ReviewCarousel :reviews="set.lawyer.reviews" :rating="Number(set.lawyer.reviews_sum_rating)"
            :reviewscount="set.lawyer.reviews_count" :lwrid="set.lawyer.id" :auth="set.auth" :errors="set.errors"
            :mainuslugaid="null" :uslugaid="null" />

        <!--
        <Specialization :specializationOne="specializationOne" :specializationTwo="specializationTwo"
            :specializationThree="specializationThree" :specialization="specialization" />
-->
        <LawyerRating :lawyer="set.lawyer" />

        <Articles :articles="set.articles" />

        <Slider :sliderheader="set.sliderheader" :practice="set.practice" />

        <MainFooter />
    </div>
</template>
