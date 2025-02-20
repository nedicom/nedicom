<script setup>
import RatingReady from "@/Components/RatingReady.vue";
import ReviewLawyer from "@/Components/ReviewLawyer.vue";

defineProps({
  reviewscount: Number,
  rating: Number,
  reviews: Object,
  lwrid: Number,
  auth: Object,
  mainuslugaid: Number,
  uslugaid: Number,
  errors: Object,
});
</script>

<template>
  <!--reviews carousel-->
  <div id="reviews" class="py-12">
    <h2 class="text-4xl mx-12 my-1 font-semibold text-grey text-center">
      Отзывы заказчиков
    </h2>
    <p
      itemprop="aggregateRating"
      itemscope
      itemtype="https://schema.org/AggregateRating"
      class="text-xs mx-12 font-semibold text-grey text-center md:text-end py-5"
    >
      общая оценка: 
      <span  itemprop="ratingValue" v-if="rating"
        >{{ (rating / reviewscount).toFixed(1) }}</span 
      ><span itemprop="ratingValue" v-else>0</span>
      всего отзывов:
      <span itemprop="reviewCount">{{ reviewscount }}</span>
    </p>
    <Carousel v-bind="settings" :breakpoints="breakpoints">
      <Slide v-for="(card, n) in 1" :key="n">
        <!-- add review -->
        <div class="carousel__item w-full h-full mx-3">
          <div
            class="w-full h-full mx-1 rounded-lg border border-gray-400 content-center bg-white py-2 px-4"
          >
            <ReviewLawyer
              :mainuslugaid="mainuslugaid"
              :uslugaid="uslugaid"
              :lwrid="lwrid"
              :auth="auth"
              :errors="errors"
            />
          </div>
        </div>
        <!-- add review -->
      </Slide>
      <Slide v-for="(card, n) in reviews" :key="n">
        <div class="carousel__item w-full mx-3">
          <!-- card -->
          <div
            itemprop="review"
            itemscope
            itemtype="https://schema.org/Review"
            class="w-full mx-1 rounded-lg border border-gray-400 grid grid-cols-3 content-centerl bg-white p-5"
          >
            <div
              itemprop="datePublished"
              :content="card.created_at"
              class="flex items-center justify-left col-span-2"
            >
              {{ card.created_at }}
            </div>

            <RatingReady :reviewRating="true" :rating="card.rating" />

            <div class="flex items-center justify-start">
              <div class="rounded-full w-12">
                <svg
                  v-if="card.id % 2"
                  xmlns="http://www.w3.org/2000/svg"
                  x="0px"
                  y="0px"
                  width="24"
                  height="24"
                  viewBox="0 0 48 48"
                >
                  <path
                    fill="#fbc02d"
                    d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12	s5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24s8.955,20,20,20	s20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"
                  ></path>
                  <path
                    fill="#e53935"
                    d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039	l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"
                  ></path>
                  <path
                    fill="#4caf50"
                    d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36	c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"
                  ></path>
                  <path
                    fill="#1565c0"
                    d="M43.611,20.083L43.595,20L42,20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571	c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"
                  ></path>
                </svg>

                <svg
                  v-else
                  xmlns="http://www.w3.org/2000/svg"
                  x="0px"
                  y="0px"
                  width="24"
                  height="24"
                  viewBox="0 0 48 48"
                >
                  <linearGradient
                    id="lpa7hSZqz_S376v76E9kia_wQ15B9zLAw61_gr1"
                    x1="13.239"
                    x2="37.906"
                    y1="1.907"
                    y2="33.479"
                    gradientUnits="userSpaceOnUse"
                  >
                    <stop offset="0" stop-color="#f52537"></stop>
                    <stop offset=".293" stop-color="#f32536"></stop>
                    <stop offset=".465" stop-color="#ea2434"></stop>
                    <stop offset=".605" stop-color="#dc2231"></stop>
                    <stop offset=".729" stop-color="#c8202c"></stop>
                    <stop offset=".841" stop-color="#ae1e25"></stop>
                    <stop offset=".944" stop-color="#8f1a1d"></stop>
                    <stop offset="1" stop-color="#7a1818"></stop>
                  </linearGradient>
                  <path
                    fill="url(#lpa7hSZqz_S376v76E9kia_wQ15B9zLAw61_gr1)"
                    d="M32,24h-7l8-18h7L32,24z M27,36.689	c0-4.168-0.953-8.357-2.758-12.117L15,6H8l10.833,21.169C20.251,30.123,21,33.415,21,36.689V42h6V36.689z"
                  ></path>
                </svg>
              </div>
            </div>

            <div class="h-12 flex items-center justify-end col-span-2">
              <p
                class="text-gray-900 subpixel-antialiased text-right line-clamp-2 font-bold"
              >
                <span
                  itemprop="author"
                  itemscope=""
                  itemtype="http://schema.org/Person"
                  ><span itemprop="name">{{ card.fio }}</span></span
                >
              </p>
            </div>

            <div class="flex items-center h-24 col-span-3">
              <p class="text-gray-700/75 line-clamp-3 flex text-left">
                -
                <span itemprop="reviewBody">"{{ card.description }}"</span>
              </p>
            </div>
          </div>
          <!-- card -->
        </div>
      </Slide>

    </Carousel>
  </div>
  <!--reviews carousel-->
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
      autoplay: 2000,
      pauseAutoplayOnHover: true,
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
