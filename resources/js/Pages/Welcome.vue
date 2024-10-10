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
});


let secondbannerimgmobile = 'url("https://nedicom.ru/storage/images/landing/main/secondm.webp")';
let secondbannerpc = 'url("https://nedicom.ru/storage/images/landing/main/second.webp")';

let mainbannerimgmobile = 'url("https://nedicom.ru/storage/images/landing/main/firstm.webp")';
let mainbannerpc = 'url("https://nedicom.ru/storage/images/landing/main/1280on600.webp")';

let statusonimage = ref("Юридическая компания");
let nameonimage = ref("Мина");

let title = "Юридическая компания 'Мина'";
let description = "8 978 8838 978 юридические услуги по г. Москва и по Республике Крым";
</script>

<template>
  <FlashMessage :message="flash.message" />

  <Head>
    <title>{{ title }}</title>
    <meta name="description" :content="description" />
  </Head>

  <div class="min-h-screen" itemscope itemtype="https://schema.org/LegalService">
    <MainHeader />

    <Header :modalPageTitle="'Модальное окно главная'"/>

    <Mainbanner :mainbannerimgmobile="mainbannerimgmobile" :mainbannerpc="mainbannerpc" />

    <SliderUslug :uslugislider="uslugislider" />

    <SecondBanner :statusonimage="'© 2024 nedicom™'" :nameonimage="null"
      :secondbannerimgmobile="secondbannerimgmobile" :secondbannerpc="secondbannerpc" />

    <Slider :practice="practice" :sliderheader="'Каждая проблема для нас особенная'" />

    <Testimonials />

    <!--reviews carousel-->
    <div class="mt-12 py-12 bg-gray-100/75">
      <h3 class="text-4xl mx-12 my-1 font-semibold text-grey text-center">
        будем рады и Вашему отзыву
      </h3>
      <p itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating"
        class="text-xs mx-12 font-semibold text-grey text-center md:text-end py-5">
        общая оценка: <span itemprop="ratingValue">{{ rating }}</span>
        всего отзывов: <span itemprop="reviewCount">{{ reviewscount }}</span>
      </p>
      <Carousel v-bind="settings" :breakpoints="breakpoints">
        <Slide v-for="card in reviews" :key="card">
          <div class="carousel__item w-full mx-3" itemprop="review" itemscope itemtype="https://schema.org/Review">

            <!-- card -->
            <div class="max-w bg-white border border-gray-200 rounded-lg shadow">

              <span itemprop='itemReviewed' itemscope
                  itemtype='https://schema.org/LegalService'>
                <a :href="'https://nedicom.ru/uslugi/' + card.usluga.url">
                  <img v-if="card.usluga.file_path" class="rounded-t-lg object-cover  h-72 w-full"
                    :src="'https://nedicom.ru/' + card.usluga.file_path" :alt='card.usluga.usl_name'  itemprop='image'/>
                  <img v-else class="rounded-t-lg object-cover  h-72 w-full"
                    src="https://nedicom.ru/storage/images/landing/main/1280on600.webp"
                    alt='адвокатский Симферополь юрист Крым' 
                    itemprop='image'/>
                </a>

                <a :href="'https://nedicom.ru/uslugi/' + card.usluga.url" class="m-3">
                    <h5 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white"><span
                        itemprop='name'>{{ card.usluga.usl_name }}</span></h5>
                </a>

                <a href="tel: +79788838978" class="m-3">
                    <h5 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white"><span
                        itemprop='telephone'>8 978 8838 978</span></h5>
                </a>

              </span>

              <RatingReady :reviewRating="true" :rating="card.rating" class="flex-none" />

              <div class="grid grid-cols-2 place-content-between p-3">
                <div class="text-start">
                  пользовался услугой
                </div>
                <div class="text-end" itemprop="datePublished" :content="card.created_at">
                  {{ card.created_at }}
                </div>
              </div>

                <div class="grid grid-cols-2 place-content-between p-3">
                  <div class="">
                    <div class="rounded-full w-12">
                      <svg v-if="card.id % 2" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24"
                        viewBox="0 0 48 48">
                        <path fill="#fbc02d"
                          d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12	s5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24s8.955,20,20,20	s20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z">
                        </path>
                        <path fill="#e53935"
                          d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039	l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z">
                        </path>
                        <path fill="#4caf50"
                          d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36	c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z">
                        </path>
                        <path fill="#1565c0"
                          d="M43.611,20.083L43.595,20L42,20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571	c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z">
                        </path>
                      </svg>

                      <svg v-else xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24"
                        viewBox="0 0 48 48">
                        <linearGradient id="lpa7hSZqz_S376v76E9kia_wQ15B9zLAw61_gr1" x1="13.239" x2="37.906" y1="1.907"
                          y2="33.479" gradientUnits="userSpaceOnUse">
                          <stop offset="0" stop-color="#f52537"></stop>
                          <stop offset=".293" stop-color="#f32536"></stop>
                          <stop offset=".465" stop-color="#ea2434"></stop>
                          <stop offset=".605" stop-color="#dc2231"></stop>
                          <stop offset=".729" stop-color="#c8202c"></stop>
                          <stop offset=".841" stop-color="#ae1e25"></stop>
                          <stop offset=".944" stop-color="#8f1a1d"></stop>
                          <stop offset="1" stop-color="#7a1818"></stop>
                        </linearGradient>
                        <path fill="url(#lpa7hSZqz_S376v76E9kia_wQ15B9zLAw61_gr1)"
                          d="M32,24h-7l8-18h7L32,24z M27,36.689	c0-4.168-0.953-8.357-2.758-12.117L15,6H8l10.833,21.169C20.251,30.123,21,33.415,21,36.689V42h6V36.689z">
                        </path>
                      </svg>
                    </div>
                  </div>

                  <div class="">
                    <p class="text-gray-900 subpixel-antialiased text-right line-clamp-1 font-bold">
                      <span itemprop="author" itemscope="" itemtype="http://schema.org/Person"><span
                          itemprop="name">{{ card.fio }}</span></span>
                    </p>
                  </div>
                </div>

                <p class="p-3 font-normal text-gray-700 dark:text-gray-400">
                <div class="flex items-center h-24 col-span-3">
                  <p class="text-gray-700/75 line-clamp-3 flex text-left">
                    - <span itemprop="reviewBody">"{{ card.description }}"</span>
                  </p>
                </div>
                </p>
              
            </div>

            <!-- card -->

          </div>
        </Slide>

        <template #addons>
          <Navigation />
        </template>
      </Carousel>
    </div>



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