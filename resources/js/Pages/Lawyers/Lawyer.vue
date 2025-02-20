<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import PromoHeader from "@/Layouts/PromoHeader.vue";
import Mainbanner from "@/Layouts/Mainbanner.vue";
import Slider from "@/Layouts/Slider.vue";
import ReviewCarousel from "@/Layouts/ReviewCarousel.vue";
import Articles from "@/Layouts/Articles.vue";
import LawyerAnswers from "@/Layouts/LawyerAnswers.vue";
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
    articles: Object,
    anwswers: Object,
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

    <MainHeader :auth="auth" />

    <PromoHeader />
    <div class="min-h-screen">
        <div class="grid grid-cols-1 md:grid-cols-4 py-5">
            <div class="w-full flex justify-between md:flex-col md:text-xl md:mt-12 px-5 sticky top-0">
                <div class="w-full flex justify-between md:flex-col md:text-xl md:mt-12 px-5 sticky top-0"><a
                        class="flex justify-end md:min-w-full md:mx-5 md:p-3 my-1 mr-1 text-gray-600 focus:outline-none transition hover:translate-x-1 duration-100"
                        href="#about">
                        <div class=""> Про юриста </div>
                    </a>
                    <a class="flex justify-end md:min-w-full md:mx-5 md:p-3 my-1 mr-1 text-gray-600 focus:outline-none transition hover:translate-x-1 duration-100"
                        href="#otzivi">
                        <div class=""> Отзывы </div>
                    </a><a
                        class="flex justify-end md:min-w-full md:mx-5 md:p-3 my-1 mr-1 text-gray-600 focus:outline-none transition hover:translate-x-1 duration-100"
                        href="#posts">
                        <div class=""> Посты </div>
                    </a><a
                        class="flex justify-end md:min-w-full md:mx-5 md:p-3 my-1 mr-1 text-gray-600 focus:outline-none transition hover:translate-x-1 duration-100"
                        href="#practice">
                        <div class=""> Практика </div>
                    </a><a
                        class="flex justify-end md:min-w-full md:mx-5 md:p-3 my-1 mr-1 text-gray-600 focus:outline-none transition hover:translate-x-1 duration-100"
                        href="#answers">
                        <div class=""> Ответы </div>
                    </a>                    
                </div>
            </div>

            <div class="md:col-span-3 md:mt-12 px-3 md:px-10 w-full">
                <About :lawyer="set.lawyer" />

                <ReviewCarousel :reviews="set.lawyer.reviews" :rating="Number(set.lawyer.reviews_sum_rating)"
                    :reviewscount="set.lawyer.reviews_count" :lwrid="set.lawyer.id" :auth="set.auth"
                    :errors="set.errors" :mainuslugaid="null" :uslugaid="null" />

                <!--
        <Specialization :specializationOne="specializationOne" :specializationTwo="specializationTwo"
            :specializationThree="specializationThree" :specialization="specialization" />
-->
                <LawyerRating :lawyer="set.lawyer" />

                <Articles :articles="set.articles" />

                <Slider :sliderheader="'Практика юриста'" :practice="set.practice" />

                <LawyerAnswers :anwswers="set.anwswers" />
                
            </div>
        </div>
        <MainFooter />
    </div>
</template>
