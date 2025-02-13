<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import SecondBanner from "@/Layouts/SecondBanner.vue";
import Body from "@/Layouts/Body.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import RatingReady from "@/Components/RatingReady.vue";
import Prices from "@/Components/Prices.vue";
import VideoBlock from "@/Components/VideoBlock.vue";
import BreadcrumbsUslugi from "@/Components/BreadcrumbsUslugi.vue";
import Totop from "@/Components/Totop.vue";
import Address from "@/Layouts/Address.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import PracticeGallery from "@/Layouts/PracticeGallery.vue";
import ReviewCarousel from "@/Layouts/ReviewCarousel.vue";
import { Head } from "@inertiajs/inertia-vue3";


let vars = defineProps({
  usluga: "Object",
  lawyers: "Object",
  lawyer: Object,
  practice: Object,
  flash: "Object",
  reviews: "Object",
  reviewscount: Number,
  rating: Number,
  main_usluga: Object,
  second_usluga: Object,
  city: Object,
  auth: Object,
  url: String,
  errors: Object,
  cityheader: Object,
});

let pcimg = vars.lawyer.file_path;
if (vars.usluga.file_path) {
  pcimg = vars.usluga.file_path;
}
let secondbannerpc = "https://nedicom.ru/" + pcimg;
let secondbannerimgmobile = "https://nedicom.ru/" + vars.usluga.mob_file_path;

let metaimage = "https://nedicom.ru/" + vars.usluga.file_path;
</script>

<style>
.article h3 {
  font-size: 2rem;
}

.article ul,
ol {
  padding: 0 1rem;
  margin-left: 1rem;
  list-style-type: square;
}

details summary::-webkit-details-marker {
  display: none;
}

</style>

<template>
  <FlashMessage :message="flash.message" />

  <Head>
    <title>{{ vars.usluga.usl_name }} ({{vars.city.title}}) </title>
    <meta head-key="description" name="description" :content="vars.usluga.usl_name + ' - ' + vars.usluga.usl_desc" />
    <meta property="og:title" :content="vars.usluga.usl_name" />
    <meta property="og:description" :content="vars.usluga.usl_desc" />
    <meta property="og:type" content="website" />
    <meta property="og:url"
      :content="'https://nedicom.ru/uslugi/' + vars.url" />
    <meta property="og:image" :content="metaimage" />
    <meta property="og:site_name" content="nedicom.ru" />
    <meta property="og:locale" content="ru_RU" />
    <link rel="canonical" :href="'https://nedicom.ru/uslugi/' + vars.url" />
  </Head>

  <MainHeader :auth="vars.auth" :city="vars.cityheader" :hideBtn="false"/>

  <Header :avatarPath="vars.lawyer.avatar_path" :lawyer="vars.usluga.usl_name" :phone="usluga.phone" :address="usluga.address" :modalPageTitle="vars.usluga.usl_name" 
     />

  <Body>
    <div itemscope itemtype="https://schema.org/Product">
      <SecondBanner :statusonimage="usluga.usl_name" :phnform="false" :secondbannerpc="secondbannerpc"
        :secondbannerimgmobile="secondbannerimgmobile" :metaimage="metaimage" />
      <!-- edit btn -->
      <div v-if="auth" class="flex justify-center pt-2 max-w-5xl mx-auto sm:px-6 lg:px-8 sm:rounded-lg">
        <a v-if="vars.auth.isadmin == 1 || vars.auth.id == vars.usluga.user_id" :href="route('uslugi.edit', usluga.id)"
          class="text-white bg-green-500 hover:bg-green-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Редактировать
          объявление</a>
      </div>
      <!-- edit btn -->

      <!-- header 2 -->
      <div class="my-20 flex justify-center">
        <div class="flex flex-col md:flex-row md:w-3/4 px-5">
          <!-- short desc -->
          <div class="flex items-center md:w-2/3">
            <div class="flex-col">
              <div itemprop="description"
                class="mb-10 md:mb-5 md:my-0 font-bold text-gray-800 text-2xl text-center md:text-justify">
                {{ usluga.usl_desc }}
              </div>
              <div class="mt-2 font-bold text-gray-600 text-xl text-center md:text-right">
                @ {{ lawyer.name }}
              </div>
              <div class="font-medium text-gray-900 text-base text-center md:text-right">
                {{ main_usluga.usl_name }}.
                <span v-if="usluga.cities">{{ usluga.cities.title }}</span>
              </div>
            </div>
          </div>
          <!-- short desc -->

          <!-- short image -->
          <div class="flex mt-5 md:w-1/2 justify-center">
            <img :src="'https://nedicom.ru/' + lawyer.avatar_path" class="w-3/4 rounded-full"
              :alt="vars.usluga.usl_name" />
          </div>
          <!-- short image -->
        </div>
      </div>
      <!-- header 2 -->

      <!--video block-->
      <VideoBlock :video="vars.usluga.video" />
      <!--video block-->

      <PracticeGallery v-if="practice != 0" :sliderheader="'Доверяйте делам'" :keyword="vars.main_usluga.usl_name"
        :practice="vars.practice" />

      <!-- seo description -->
      <div class="my-20">
        <div itemprop="disambiguatingDescription" class="mx-auto max-w-2xl px-6 space-y-6 text-gray-900 text-justify"
          v-html="usluga.longdescription"></div>
      </div>
      <!-- seo description -->

      <!-- popular question -->
      <div id="questions" v-if="vars.usluga.popular_question">
        <div v-if="vars.usluga.popular_question[0].answer" class="my-12 pb-12" itemscope
          itemtype="https://schema.org/FAQPage">
          <h3 class="text-4xl mx-12 my-1 font-semibold text-grey text-center">
            Частые вопросы
          </h3>
          <ul class="md:w-4/6 w-11/12 mx-auto mt-10 md:mt-20 divide-y shadow-gray-600 shadow-2xl rounded-xl list-none">
            <li v-for="item in vars.usluga.popular_question" :key="item.id" class="py-5" itemscope itemprop="mainEntity"
              itemtype="https://schema.org/Question">
              <details class="group">
                <summary class="flex items-center gap-3 px-4 py-3 font-medium marker:content-none hover:cursor-pointer">
                  <svg class="w-5 h-5 text-gray-500 transition group-open:rotate-90" xmlns="http://www.w3.org/2000/svg"
                    width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                      d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z">
                    </path>
                  </svg>
                  <span itemprop="name">{{ item.question }}</span>
                </summary>

                <article class="px-4 pb-4 text-slate-500" itemscope itemprop="acceptedAnswer"
                  itemtype="https://schema.org/Answer">
                  <p itemprop="text">
                    {{ item.answer }}
                  </p>
                </article>
              </details>
            </li>
          </ul>
        </div>
      </div>
      <!-- popular question -->

      <ReviewCarousel :reviews="vars.reviews" :rating="vars.rating" :reviewscount="vars.reviewscount"
        :lwrid="vars.lawyer.id" :auth="vars.auth" :errors="vars.errors" :mainuslugaid="vars.usluga.main_usluga_id"
        :uslugaid="vars.usluga.id" />

      <Prices :subheader="vars.main_usluga.usl_name" :city="vars.city"
        :reviewcoutnt="vars.main_usluga.mainreview_count" :rating="Number(vars.main_usluga.avg_review)"
        :secondbannerimgmobile="secondbannerimgmobile" :metaimage="metaimage" :keyword="vars.main_usluga.usl_name" />

      <Address v-if="usluga.cities" :usl_name="usluga.usl_name" :phone="usluga.phone" :address="usluga.address"
        :dopadress="usluga.dopadress" :maps="usluga.maps" :metaimage="metaimage"
        :company="lawyer.name + ' юрист по городу ' + usluga.cities.title" />
    </div>

    <BreadcrumbsUslugi v-if="vars.city" :city="vars.city ? vars.city : null"
      :main_usluga="vars.main_usluga.url !== 0 ? vars.main_usluga : null"
      :second_usluga="vars.second_usluga ? vars.second_usluga : null" :usluga="vars.usluga" />
  </Body>
  <MainFooter />
  <Totop />
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
