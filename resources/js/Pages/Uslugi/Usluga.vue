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
import { Head } from "@inertiajs/inertia-vue3";

let vars = defineProps({
  usluga: "Object",
  user: "Object",
  lawyers: "Object",
  lawyer: Object,
  practice: Object,
  flash: "Object",
  reviews: "Object",
  reviewscount: "String",
  rating: Number,
  main_usluga: Object,
  second_usluga: Object,
  city: Object,
  auth: Object,
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
    <title>{{ vars.usluga.usl_name }}</title>
    <meta
      head-key="description"
      name="description"
      :content="vars.usluga.usl_desc"
    />
  </Head>

  <MainHeader :auth="vars.auth" />

  <Header
    :phone="usluga.phone"
    :address="usluga.address"
    :modalPageTitle="'услуга - ' + vars.usluga.usl_name"
  />

  <Body>
    <div itemscope itemtype="https://schema.org/Product">
      <SecondBanner
        :statusonimage="usluga.usl_name"
        :phnform="false"
        :secondbannerpc="secondbannerpc"
        :secondbannerimgmobile="secondbannerimgmobile"
        :metaimage="metaimage"
      />
      <!-- edit btn -->
      <div
        v-if="user"
        class="flex justify-center pt-2 max-w-5xl mx-auto sm:px-6 lg:px-8 sm:rounded-lg"
      >
        <a
          v-if="vars.user.isadmin == 1 || vars.user.id == vars.usluga.user_id"
          :href="route('uslugi.edit', usluga.id)"
          class="text-white bg-green-500 hover:bg-green-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5"
          >Редактировать объявление</a
        >
      </div>
      <!-- edit btn -->

      <!-- header 2 -->
      <div class="my-20 flex justify-center">
        <div class="flex flex-col md:flex-row md:w-3/4 px-5">
          <!-- short desc -->
          <div class="flex items-center md:w-2/3">
            <div class="flex-col">
              <div
                itemprop="description"
                class="mb-10 md:mb-5 md:my-0 font-bold text-gray-800 text-2xl text-center md:text-justify"
              >
                {{ usluga.usl_desc }}
              </div>
              <div
                class="mt-2 font-bold text-gray-600 text-xl text-center md:text-right"
              >
                @ {{ lawyer.name }}
              </div>
              <div
                class="font-medium text-gray-900 text-base text-center md:text-right"
              >
                {{ main_usluga.usl_name }}.
                <span v-if="usluga.cities">{{ usluga.cities.title }}</span>
              </div>
            </div>
          </div>
          <!-- short desc -->

          <!-- short image -->
          <div class="flex mt-5 md:w-1/2 justify-center">
            <img
              :src="'https://nedicom.ru/' + lawyer.avatar_path"
              class="w-3/4 rounded-full"
              :alt="vars.usluga.usl_name"
            />
          </div>
          <!-- short image -->
        </div>
      </div>
      <!-- header 2 -->

      <!--video block-->
      <VideoBlock :video="vars.usluga.video" />
      <!--video block-->

      <PracticeGallery
        v-if="practice != 0"
        :sliderheader="'Доверяйте делам'"
        :practice="vars.practice"
      />

      <!-- seo description -->
      <div class="my-20">
        <div
          itemprop="disambiguatingDescription"
          class="mx-auto max-w-2xl px-6 space-y-6 text-gray-900 text-justify"
          v-html="usluga.longdescription"
        ></div>
      </div>
      <!-- seo description -->

      <!-- preimushestva 
      <div class="bg-white">
        <div class="mx-auto max-w-7xl py-24 sm:px-6 sm:py-32 lg:px-8">
          <div
            class="relative isolate overflow-hidden bg-gray-500 px-6 pt-16 shadow-2xl sm:rounded-3xl sm:px-16 md:pt-24 lg:flex lg:gap-x-20 lg:px-24 lg:pt-0">

            <div class="mx-auto text-center lg:mx-0 lg:py-32 lg:text-left">
              <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl md:h-12 h-36 text-white">
                <vue-writer :array="writerarr" :eraseSpeed="20" :typeSpeed="50" class="m-h-12" />
              </h2>
              <p class="mt-6 text-lg leading-8 text-white">
                Мы хотели тут написать для чего нужно записываться на консультацию

              </p>
              <div class="mt-10 flex items-center justify-center gap-x-6 lg:justify-start text-white">
                <a href="tel:+79788838978" class="text-3xl">{{ usluga.phone }}</a>
              </div>

              <div class="mt-10 md:flex items-center justify-center gap-x-6 lg:justify-start text-white"
                itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                <meta itemprop="priceValidUntil" content="2027-01-01">
                <h1 class="text-3xl">
                  Консультация
                </h1>
                <div class="md:flex justify-center text-2xl">
                  <span itemprop="price" content="1000.00"><strong class="font-semibold text-white mr-2 text-3xl">
                      <link itemprop="availability" href="https://schema.org/InStock" />1000
                    </strong></span>
                  <span itemprop="priceCurrency" content="RUB">рублей</span>
                </div>

                <div itemprop="shippingDetails" itemtype="https://schema.org/OfferShippingDetails" itemscope>
                  <div itemprop="shippingRate" itemtype="https://schema.org/MonetaryAmount" itemscope>
                    <meta itemprop="value" content="0" />
                    <meta itemprop="currency" content="RUB" />
                  </div>
                  <div itemprop="shippingDestination" itemtype="https://schema.org/DefinedRegion" itemscope>
                    <meta itemprop="addressCountry" content="RU" />
                  </div>
                  <div itemprop="deliveryTime" itemtype="https://schema.org/ShippingDeliveryTime" itemscope>
                    <div itemprop="handlingTime" itemtype="https://schema.org/QuantitativeValue" itemscope>
                      <meta itemprop="minValue" content="0" />
                      <meta itemprop="maxValue" content="1" />
                      <meta itemprop="unitCode" content="DAY" />
                    </div>
                    <div itemprop="transitTime" itemtype="https://schema.org/QuantitativeValue" itemscope>
                      <meta itemprop="minValue" content="0" />
                      <meta itemprop="maxValue" content="1" />
                      <meta itemprop="unitCode" content="DAY" />
                    </div>
                  </div>

                </div>

                <div itemprop="hasMerchantReturnPolicy" itemtype="https://schema.org/MerchantReturnPolicy" itemscope>
                  <meta itemprop="applicableCountry" content="RU" />
                  <meta itemprop="returnMethod" content="https://schema.org/ReturnByMail" />
                  <meta itemprop="returnFees" content="https://schema.org/FreeReturn" />
                  <meta itemprop="returnPolicyCategory" content="https://schema.org/MerchantReturnFiniteReturnWindow" />
                  <meta itemprop="merchantReturnDays" content="3" />
                </div>
              </div>


              <div class="mt-10 mb-10 lg:mb-0 flex items-center justify-center gap-x-6 lg:justify-start">
                <a href="https://nedicom.ru/policy" class="text-sm font-semibold text-white">Политика
                  конфиденциальности <span aria-hidden="true">→</span></a>
              </div>
            </div>
          </div>
        </div>
      </div>
      -->
      <!-- preimushestva -->
      <!-- popular question -->
      <div id="questions" v-if="vars.usluga.popular_question">
        <div
          v-if="vars.usluga.popular_question[0].answer"
          class="my-12 pb-12"
          itemscope
          itemtype="https://schema.org/FAQPage"
        >
          <h3 class="text-4xl mx-12 my-1 font-semibold text-grey text-center">
            Частые вопросы
          </h3>
          <ul
            class="md:w-4/6 w-11/12 mx-auto mt-10 md:mt-20 divide-y shadow-gray-600 shadow-2xl rounded-xl list-none"
          >
            <li
              v-for="item in vars.usluga.popular_question"
              :key="item.id"
              class="py-5"
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
                  class="px-4 pb-4 text-slate-500"
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

      <!--reviews carousel-->
      <div id="reviews" class="mt-12 py-12 bg-gray-100/75">
        <h2 class="text-xl mx-12 my-1 font-semibold text-grey text-center">
          Отзывы заказчиков
        </h2>
        <p
          itemprop="aggregateRating"
          itemscope
          itemtype="https://schema.org/AggregateRating"
          class="text-xs mx-12 font-semibold text-grey text-center md:text-end py-5"
        >
          общая оценка:
          <span itemprop="ratingValue">{{ vars.rating }}</span> всего отзывов:
          <span itemprop="reviewCount">{{ reviewscount }}</span>
        </p>
        <Carousel v-bind="settings" :breakpoints="breakpoints">
          <Slide v-for="card in vars.reviews" :key="card">
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

          <template #addons>
            <Navigation />
          </template>
        </Carousel>
      </div>
      <!--reviews carousel-->

      <Prices
        :subheader="vars.main_usluga.usl_name"
        :city="vars.usluga.cities"
        :reviewcoutnt="vars.main_usluga.mainreview_count"
        :rating="Number(vars.main_usluga.avg_review)"
        :secondbannerimgmobile="secondbannerimgmobile"
        :metaimage="metaimage"
      />

      <Address
        v-if="usluga.cities"
        :usl_name="usluga.usl_name"
        :phone="usluga.phone"
        :address="usluga.address"
        :dopadress="usluga.dopadress"
        :maps="usluga.maps"
        :metaimage="metaimage"
        :company="lawyer.name + ' юрист по городу ' + usluga.cities.title"
      />
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
