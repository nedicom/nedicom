<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import SecondBanner from "@/Layouts/SecondBanner.vue";
import AboutUsluga from "@/Layouts/AboutUsluga.vue";
import Body from "@/Layouts/Body.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import RatingReady from "@/Components/RatingReady.vue";
import Prices from "@/Components/Prices.vue";
import VideoBlock from "@/Components/VideoBlock.vue";
import VKwidjet from "@/Components/VKwidjet.vue";
import OKwidjet from "@/Components/OKwidjet.vue";

import BreadcrumbsUslugi from "@/Components/BreadcrumbsUslugi.vue";
import Address from "@/Layouts/Address.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import PracticeGallery from "@/Layouts/PracticeGallery.vue";
import ReviewCarousel from "@/Layouts/ReviewCarousel.vue";
import { Head } from "@inertiajs/inertia-vue3";

let vars = defineProps({
  usluga: Object,
  userprices: Object,
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
    <title>{{ vars.usluga.usl_name }} ({{ vars.city.title }})</title>
    <meta
      head-key="description"
      name="description"
      :content="vars.usluga.usl_name + ' - ' + vars.usluga.usl_desc"
    />
    <meta property="og:title" :content="vars.usluga.usl_name" />
    <meta property="og:description" :content="vars.usluga.usl_desc" />
    <meta property="og:type" content="website" />
    <meta
      property="og:url"
      :content="'https://nedicom.ru/uslugi/' + vars.url"
    />
    <meta property="og:image" :content="metaimage" />
    <meta property="og:site_name" content="nedicom.ru" />
    <meta property="og:locale" content="ru_RU" />
    <link rel="canonical" :href="'https://nedicom.ru/uslugi/' + vars.url" />
  </Head>

  <MainHeader :auth="vars.auth" :city="vars.cityheader" :hideBtn="true" />

  <Body>
    <div>
      <div class="grid grid-cols-1 md:grid-cols-4 py-5">
        <div
          class="w-full flex justify-between md:flex-col md:text-xl lg:px-5 fixed lg:sticky bottom-0 lg:top-0 z-40 h-10 bg-white"
        >
          <div
            class="flex justify-between md:flex-col md:text-xl md:mt-12 px-2"
          >
            <a
              class="flex justify-end md:min-w-full md:mx-5 md:p-3 my-1 mr-1 text-gray-600 focus:outline-none transition hover:translate-y-1 lg:hover:translate-x-1 duration-100"
              href="#about"
            >
              <div class="">Контакты</div>
            </a>
            <a v-if="vars.userprices[0]"
              class="flex justify-end md:min-w-full md:mx-5 md:p-3 my-1 mr-1 text-gray-600 focus:outline-none transition hover:translate-y-1 lg:hover:translate-x-1 duration-100"
              href="#prices"
            >
              <div class="">Цены</div>
            </a>
            <a 
              class="flex justify-end md:min-w-full md:mx-5 md:p-3 my-1 mr-1 text-gray-600 focus:outline-none transition hover:translate-y-1 lg:hover:translate-x-1 duration-100"
              href="#reviews"
            >
              <div class="">Отзывы</div> </a
            ><a v-if="practice != 0"
              class="flex justify-end md:min-w-full md:mx-5 md:p-3 my-1 mr-1 text-gray-600 focus:outline-none transition hover:translate-y-1 lg:hover:translate-x-1 duration-100"
              href="#prctglr"
            >
              <div class="">Практика</div> </a
            ><a v-if="vars.usluga.popular_question"
              class="flex justify-end md:min-w-full md:mx-5 md:p-3 my-1 mr-1 text-gray-600 focus:outline-none transition hover:translate-y-1 lg:hover:translate-x-1 duration-100"
              href="#questions"
            >
              <div class="">Вопросы</div> </a
            ><a
              class="flex justify-end md:min-w-full md:mx-5 md:p-3 my-1 mr-1 text-gray-600 focus:outline-none transition hover:translate-y-1 lg:hover:translate-x-1 duration-100"
              href="#description"
            >
              <div class="">Описание</div>
            </a>
          </div>
        </div>

        <div
          itemscope
          itemtype="https://schema.org/Product"
          class="md:col-span-3 px-3 md:px-10 w-full"
        >
          <!-- edit btn -->
          <span v-if="auth" class="">
            <a
              v-if="
                vars.auth.isadmin == 1 || vars.auth.id == vars.usluga.user_id
              "
              :href="route('uslugi.edit', usluga.id)"
              class="text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5"
              >Редактировать объявление</a
            >
          </span>
          <!-- edit btn -->
          <!--<SecondBanner :statusonimage="usluga.usl_name" :phnform="false" :secondbannerpc="secondbannerpc"
            :secondbannerimgmobile="secondbannerimgmobile" :metaimage="metaimage" />
        -->

          <AboutUsluga
            :usluga="vars.usluga"
            :lawyer="vars.lawyer"
            :main_usluga="vars.main_usluga"
          />

          <Address
            v-if="usluga.cities"
            :usl_name="usluga.usl_name"
            :region="usluga.cities"
            :phone="usluga.phone"
            :address="usluga.address"
            :dopadress="usluga.dopadress"
            :maps="usluga.maps"
            :metaimage="metaimage"
            :company="lawyer.name + ' юрист по городу ' + usluga.cities.title"
          />

          <Prices
            :userprices="vars.userprices"
            :subheader="vars.main_usluga.usl_name"
            :city="vars.city"
            :reviewcoutnt="vars.main_usluga.mainreview_count"
            :rating="Number(vars.main_usluga.avg_review)"
            :secondbannerimgmobile="secondbannerimgmobile"
            :metaimage="metaimage"
            :keyword="vars.main_usluga.usl_name"
          />

          <ReviewCarousel
            :reviews="vars.reviews"
            :rating="vars.rating"
            :reviewscount="vars.reviewscount"
            :lwrid="vars.lawyer.id"
            :auth="vars.auth"
            :errors="vars.errors"
            :mainuslugaid="vars.usluga.main_usluga_id"
            :uslugaid="vars.usluga.id"
          />

          <!--vk widjet-->
          <div v-if="usluga.vk || usluga.ok" class="pb-12">
            <h2 class="mx-auto max-w-5xl font-semibold my-6 text-2xl tracking-tight px-4 2xl:px-0">
              Присоединяйтесь, чтобы не потерять контакты
            </h2>
            <VKwidjet v-if="usluga.vk" :groupid="usluga.vk" />
            <div class="flex justify-center">
              <OKwidjet v-if="usluga.ok" :groupid="usluga.ok" />
            </div>
          </div>

          <!--vk widjet-->

          <!--video block-->
          <VideoBlock :video="vars.usluga.video" />
          <!--video block-->

          <PracticeGallery
            v-if="practice != 0"
            :sliderheader="'Доверяйте делам'"
            :keyword="vars.main_usluga.usl_name"
            :practice="vars.practice"
          />

          <!-- popular question -->
          <div id="questions" v-if="vars.usluga.popular_question">
            <div
              v-if="vars.usluga.popular_question[0].answer"
              class="pb-12 md:w-11/12"
              itemscope
              itemtype="https://schema.org/FAQPage"
            >
              <h2
                class="font-semibold mt-6 text-2xl tracking-tight mx-auto max-w-5xl px-4 2xl:px-0"
              >
                Частые вопросы
              </h2>
              <ul
                class="w-full mx-auto mt-10 divide-y shadow-gray-600 shadow-2xl rounded-xl list-none"
              >
                <li
                  v-for="item in vars.usluga.popular_question"
                  :key="item.id"
                  class="py-5 text-base"
                  itemscope
                  itemprop="mainEntity"
                  itemtype="https://schema.org/Question"
                >
                  <details class="group">
                    <summary
                      class="flex items-center gap-3 px-4 py-3 font-medium marker:content-none hover:cursor-pointer"
                    >
                      <svg
                        class="w-5 h-5 text-gray-500 transition group-open:rotate-90"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="currentColor"
                        viewBox="0 0 16 16"
                      >
                        <path
                          fill-rule="evenodd"
                          d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"
                        ></path>
                      </svg>
                      <span itemprop="name">{{ item.question }}</span>
                    </summary>

                    <article
                      class="px-4 pb-4 text-slate-500 text-sm"
                      itemscope
                      itemprop="acceptedAnswer"
                      itemtype="https://schema.org/Answer"
                    >
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

          <!-- seo description -->
          <div class="my-20 mx-auto max-w-5xl" id="description">
            <h2 class="font-semibold mb-6 text-2xl tracking-tight px-4 2xl:px-0">
              Подробнее
            </h2>
            <div
              itemprop="disambiguatingDescription"
              class="mx-auto space-y-6 text-gray-900 text-justify"
              v-html="usluga.longdescription"
            ></div>
          </div>
          <!-- seo description -->
        </div>
      </div>
    </div>
    <BreadcrumbsUslugi
      v-if="vars.city"
      :city="vars.city ? vars.city : null"
      :main_usluga="vars.main_usluga.url !== 0 ? vars.main_usluga : null"
      :second_usluga="vars.second_usluga ? vars.second_usluga : null"
      :usluga="vars.usluga"
    />
  </Body>
  <MainFooter />
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
