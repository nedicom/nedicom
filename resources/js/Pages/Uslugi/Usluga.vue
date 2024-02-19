<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";

import Mainbanner from "@/Layouts/Mainbanner.vue";
import Body from "@/Layouts/Body.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import RatingReady from "@/Components/RatingReady.vue";
import Prices from "@/Components/Prices.vue";
import Tg from "@/Layouts/TG/TeleGram.vue";
import Address from "@/Layouts/Address.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import Slider from "@/Layouts/Slider.vue";
import { Head } from "@inertiajs/inertia-vue3";

const value = '{ @context: "http://schema.org" }';

let vars = defineProps({
  usluga: "Object",
  user: "Object",
  lawyers: "Object",
  firstlawyer: "Array",
  practice: "$Array",
  flash: "Object",
  reviews: "Object",
  reviewscount: String,
});

let sliderheader = "Доверяйте делам";

let mainbannerimg = vars.firstlawyer[0].file_path;
if (!mainbannerimg) {
  mainbannerimg =
    "https://nedicom.ru/storage/images/landing/main/1280on600.webp";
} else {
  mainbannerimg = "https://nedicom.ru/" + vars.firstlawyer[0].file_path;
}
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
</style>

<template>
  <FlashMessage :message="flash.message" />

  <Head>
    <title>{{ vars.usluga.usl_name }}</title>
    <meta name="description" :content="vars.usluga.usl_desc" />
  </Head>

  <div itemscope itemtype="https://schema.org/LegalService">
    <MainHeader />

    <Header :phone="usluga.phone" :address="usluga.address" />

    <Mainbanner
      :statusonimage="usluga.usl_name"
      :nameonimage="usluga.desc"
      :mainbannerimg="mainbannerimg"
    />

    <Body>
      <div class="py-6">
        <!-- edit btn -->
        <div
          class="max-w-5xl mx-auto sm:px-6 lg:px-8 bg-white overflow-hidden shadow-sm sm:rounded-lg"
        >
          <div v-if="user">
            <div v-if="vars.user.isadmin == 1">
              <a
                :href="route('uslugi.edit', usluga.id)"
                class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                >Редактировать</a
              >
            </div>
          </div>
        </div>
        <!-- edit btn -->

        <!-- header 2 -->
        <div
          itemprop="legalName"
          class="text-4xl m-12 px-6 font-semibold text-grey text-center"
        >
          {{ usluga.usl_name }}
        </div>
        <!-- header 2 -->

        <!-- short desc -->
        <div class="mx-12 px-6 text-gray-900 text-center">
          {{ usluga.usl_desc }}
        </div>
        <!-- short desc -->

        <!-- preimushestva -->
        <div class="my-12">
          <div class="m-6 flex justify-center">
            <div class="">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="32"
                height="32"
                fill="currentColor"
                class="fill-indigo-500 hover:fill-indigo-600 bi bi-file-earmark-check"
                viewBox="0 0 16 16"
              >
                <path
                  d="M10.854 7.854a.5.5 0 0 0-.708-.708L7.5 9.793 6.354 8.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3-3z"
                />
                <path
                  d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"
                />
              </svg>
            </div>
          </div>

          <h2 class="py-3 text-3xl font-semibold text-grey text-center">
            {{ usluga.preimushestvo1 }}
          </h2>

          <div class="m-6 flex justify-center">
            <div class="">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="32"
                height="32"
                fill="currentColor"
                class="fill-indigo-500 hover:fill-indigo-600 bi bi-bar-chart-line"
                viewBox="0 0 16 16"
              >
                <path
                  d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1V2zm1 12h2V2h-2v12zm-3 0V7H7v7h2zm-5 0v-3H2v3h2z"
                />
              </svg>
            </div>
          </div>

          <h2 class="py-3 text-3xl font-semibold text-grey text-center">
            {{ usluga.preimushestvo2 }}
          </h2>

          <div class="m-6 flex justify-center">
            <div class="">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="32"
                height="32"
                fill="currentColor"
                class="fill-indigo-500 hover:fill-indigo-600 bi bi-hand-index"
                viewBox="0 0 16 16"
              >
                <path
                  d="M6.75 1a.75.75 0 0 1 .75.75V8a.5.5 0 0 0 1 0V5.467l.086-.004c.317-.012.637-.008.816.027.134.027.294.096.448.182.077.042.15.147.15.314V8a.5.5 0 1 0 1 0V6.435a4.9 4.9 0 0 1 .106-.01c.316-.024.584-.01.708.04.118.046.3.207.486.43.081.096.15.19.2.259V8.5a.5.5 0 0 0 1 0v-1h.342a1 1 0 0 1 .995 1.1l-.271 2.715a2.5 2.5 0 0 1-.317.991l-1.395 2.442a.5.5 0 0 1-.434.252H6.035a.5.5 0 0 1-.416-.223l-1.433-2.15a1.5 1.5 0 0 1-.243-.666l-.345-3.105a.5.5 0 0 1 .399-.546L5 8.11V9a.5.5 0 0 0 1 0V1.75A.75.75 0 0 1 6.75 1zM8.5 4.466V1.75a1.75 1.75 0 1 0-3.5 0v5.34l-1.2.24a1.5 1.5 0 0 0-1.196 1.636l.345 3.106a2.5 2.5 0 0 0 .405 1.11l1.433 2.15A1.5 1.5 0 0 0 6.035 16h6.385a1.5 1.5 0 0 0 1.302-.756l1.395-2.441a3.5 3.5 0 0 0 .444-1.389l.271-2.715a2 2 0 0 0-1.99-2.199h-.581a5.114 5.114 0 0 0-.195-.248c-.191-.229-.51-.568-.88-.716-.364-.146-.846-.132-1.158-.108l-.132.012a1.26 1.26 0 0 0-.56-.642 2.632 2.632 0 0 0-.738-.288c-.31-.062-.739-.058-1.05-.046l-.048.002zm2.094 2.025z"
                />
              </svg>
            </div>
          </div>

          <h2 class="py-3 text-3xl font-semibold text-grey text-center">
            {{ usluga.preimushestvo3 }}
          </h2>
        </div>
        <!-- preimushestva -->

        <div
          class="px-6 text-gray-900 text-center"
          v-html="usluga.longdescription"
        ></div>

        <Slider
          v-if="practice != 0"
          :sliderheader="sliderheader"
          :sldimg="practice"
        />

        <Address
          :phone="usluga.phone"
          :address="usluga.address"
          :maps="usluga.maps"
        />

        <!--reviews carousel-->
        <div class="mt-12 py-12 bg-gray-100/75">
          <h3 class="text-4xl mx-12 my-1 font-semibold text-grey text-center">
            будем рады и Вашему отзыву
          </h3>
          <p class="text-xs mx-12 font-semibold text-grey text-center md:text-end py-5">всего отзывов: {{reviewscount}}
            </p>
          <Carousel v-bind="settings" :breakpoints="breakpoints">
            <Slide v-for="card in vars.reviews" :key="card">
              <div class="carousel__item w-full mx-3">
                <!-- card -->
                <div
                  class="w-full mx-1 rounded-lg border border-gray-400 grid grid-cols-3 content-centerl bg-white p-5"
                >
                  <div class="flex items-center justify-left col-span-2">
                    {{ card.created_at }}12.12.12
                  </div>

                  <RatingReady :rating="card.rating"/>

                  <div class="flex items-center justify-start">
                    <div class="rounded-full w-12">
                      <img
                        :src="'https://nedicom.ru/'"
                        width="120"
                        class="rounded-full"
                      />
                    </div>
                  </div>

                  <div class="h-12 flex items-center justify-end col-span-2">
                    <h5
                      class="text-gray-900 subpixel-antialiased text-right line-clamp-2 font-bold"
                    >
                      {{ card.fio }}
                    </h5>
                  </div>

                  <div class="flex items-center h-24 col-span-3">
                    <p class="text-gray-700/75 line-clamp-3 flex text-left">
                      - "{{ card.description }}"
                    </p>
                  </div>
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

        <Prices />

        <!--lawyers-->
        <div v-if="lawyers != 0">
          <h1 class="text-4xl font-semibold text-grey text-center py-10">
            Юристы в этой категории
          </h1>

          <div class="">
            <div class="grid md:grid-cols-3 gap-4 mx-4">
              <div v-for="item in vars.lawyers" :key="item.id">
                <div>
                  <div
                    class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700"
                  >
                    <a :href="route('lawyer', item.id)">
                      <img
                        class="rounded-t-lg"
                        :src="'https://nedicom.ru/' + item?.file_path"
                        alt=""
                      />
                    </a>
                    <div class="p-5">
                      <p
                        class="mb-3 font-normal text-gray-700 dark:text-gray-400"
                      >
                        {{ item.about }}
                      </p>
                      <h5
                        class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"
                      >
                        {{ item.name }}
                      </h5>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--lawyers-->
      </div>
    </Body>
  </div>
  <MainFooter />

  <Tg />
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
