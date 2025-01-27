<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import BreadcrumbsUslugi from "@/Components/BreadcrumbsUslugi.vue";
import Body from "@/Layouts/Body.vue";
import CategoryFilter from "@/Components/CategoryFilter.vue";
import CityFilter from "@/Components/CityFilter.vue";
import OfferCard from "@/Components/OfferCard.vue";
import RatingReady from "@/Components/RatingReady.vue";
import Pagination from "@/Components/Pagination.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import { ref } from "vue";
import { Head } from "@inertiajs/inertia-vue3";

let set = defineProps({
  city: Object,
  main_usluga: Object,
  second_usluga: Object,
  uslugi: Object,
  cities: Object,
  allsities: Object,
  category: Object,
  routeurl: String,
  count: Number,
  max: Number,
  min: Number,
  countrating: Number,
  sumrating: Number,
  getLawyer: String,
  auth: Object,
});

let title = ref("Услуги юристов");
let description = ref("Услуги юристов: цены, отзывы, адреса.");

set.main_usluga ? (description.value = set.main_usluga.usl_desc) : null;
set.second_usluga ? (description.value = set.second_usluga.usl_desc) : null;
description.value =
  description.value[0].toLowerCase() + description.value.slice(1);
description.value =
  set.city.title != ""
    ? set.city.title + ": " + description.value
    : "Вся Россия: " + description.value;
description.value =
  "☎️" +
  description.value +
  "⚖️ Качество услуг юристов проверено системой nedicom";

title.value = set.second_usluga
  ? set.main_usluga.usl_name + " - " + set.second_usluga.usl_name
  : set.main_usluga.usl_name;
title.value =
  set.city.title != ""
    ? title.value + " - " + set.city.title
    : title.value + " по всем городам";
let status = ref(false);

function alertForm(x) {
  status.value = x;
}
</script>

<template>
  <Head>
    <title>{{ title }}</title>
    <meta name="description" :content="description" />
  </Head>

  <div class="min-h-screen">
    <MainHeader :auth="set.auth" />

    <Header :modalPageTitle="title" />

    <Body>
      <div class="bg-white grid grid-cols-1 md:grid-cols-4">
        <div>
          <CityFilter
            :cities="set.cities"
            :routeurl="set.routeurl"
            :status="status"
            :main_usluga_url="set.main_usluga.url"
            :second_usluga_url="[
              set.second_usluga ? set.second_usluga.url : false,
            ]"
            @activeSts="alertForm"
          />

          <div v-if="set.allsities" сlass="flex justify-center md:justify-end">
            <div class="mb-10 px-2">
              <div class="flex flex-wrap gap-1 justify-center md:justify-end">
                <p class="w-full text-md font-bold text-center md:text-right">
                  Недавно юристов искали в этих городах
                </p>
                <div v-for="(allcity, n) in set.allsities" :key="n">
                  <h3 class="m-0">
                    <a
                      :href="route('uslugi.url', [allcity.cities.url])"
                      class="bg-gray-100 text-xs rounded-md px-2 py-1"
                      >{{ allcity.cities.title }}</a
                    >
                  </h3>
                </div>
              </div>
            </div>
          </div>

          <CategoryFilter
            :category="set.category"
            :cityUrl="set.city.url"
            :main_usluga_url="set.main_usluga.url ? set.main_usluga.url : '0'"
            :second_usluga_url="
              set.second_usluga ? set.second_usluga.url : 'false'
            "
            @activeSts="alertForm"
          />
        </div>
        <div
          class="w-full h-full col-span-3 md:pl-10 my-5 md:my-0"
          itemscope
          itemtype="https://schema.org/Product"
        >
          <meta
            v-if="set.second_usluga"
            itemprop="image"
            :content="'https://nedicom.ru/' + set.second_usluga.file_path"
          />
          <meta
            v-else
            itemprop="image"
            :content="'https://nedicom.ru/' + set.main_usluga.file_path"
          />

          <div class="my-6 md:mb-0">
            <span>
              <h1
                class="md:mb-4 flex md:justify-start justify-center text-sm leading-tight text-gray-900 dark:text-white md:text-2xl xl:text-2xl"
              >
                <span itemprop="name" class="font-bold">
                  <a
                    v-if="set.city.title != ''"
                    :href="route('uslugi.url', [set.city.url])"
                    class="font-medium text-blue-600 hover:underline"
                  >
                    {{ set.city.title }}</a
                  >

                  <span v-if="set.main_usluga && set.city.title != ''">
                    -
                    <a
                      v-if="set.main_usluga.url != 0"
                      :href="
                        route('offer.main', [set.city.url, set.main_usluga.url])
                      "
                      class="font-medium text-blue-600 hover:underline"
                    >
                      {{ set.main_usluga.usl_name }}</a
                    >
                    <span v-else class="font-medium">
                      {{ set.main_usluga.usl_name }}</span
                    >
                  </span>

                  <span v-if="set.second_usluga && set.city.title != ''">
                    -
                    <a
                      :href="
                        route('offer.second', [
                          set.city.url,
                          set.main_usluga.url,
                          set.second_usluga.url,
                        ])
                      "
                      class="font-medium"
                    >
                      {{ set.second_usluga.usl_name }}</a
                    >
                  </span>
                </span>
              </h1>
              <span
                v-if="set.second_usluga"
                itemprop="description"
                class="font-bold flex md:justify-start justify-center"
                >{{ set.second_usluga.usl_desc }}</span
              >
              <span
                v-else
                itemprop="description"
                class="font-bold flex md:justify-start justify-center"
                >{{ set.main_usluga.usl_desc }}</span
              >
            </span>

            <div
              v-if="set.sumrating !== 0 && set.countrating !== 0"
              class="mb-4 flex md:justify-start justify-center gap-2 text-sm font-medium text-gray-900"
              itemprop="aggregateRating"
              itemscope
              itemtype="https://schema.org/AggregateRating"
            >
              <span class="flex items-center" itemprop="ratingValue">
                <RatingReady
                  :rating="Number((set.sumrating / set.countrating).toFixed(2))"
                  :reviewRating="false"
                />
              </span>
              из <span itemprop="bestRating">5.00</span> на основании
              <span itemprop="ratingCount">{{ set.countrating }} </span>отзывов
            </div>
          </div>

          <div v-if="set.uslugi">
            <div v-if="set.uslugi.data[0]">
              <div
                class="flex justify-start px-3 my-2 md:px-0 w-full md:w-4/5 text-sm font-medium text-gray-900 text-center"
                itemprop="offers"
                itemscope
                itemtype="https://schema.org/AggregateOffer"
              >
                Цены за консультацию
                <meta itemprop="priceCurrency" content="RUB" />
                от &nbsp;<span itemprop="lowPrice" content="1000">{{
                  set.min
                }}</span>
                &nbsp;до &nbsp;<span itemprop="highPrice" content="2000">
                  {{ set.max }} рублей
                </span>
                &nbsp;у &nbsp;<span itemprop="offerCount">{{ set.count }}</span>
                &nbsp;<span v-if="set.count == 1">юриста</span
                ><span v-else>юристов</span>
              </div>
              <!-- card -->
              <div v-for="offer in set.uslugi.data" :key="offer.id">
                
                <OfferCard
                  :offer="offer"
                  :city="set.cities"
                  :getlwr="set.getLawyer"
                  :auth="set.auth"
                />
                <hr
                  class="h-px my-8 bg-gray-200 md:my-10 border-0 dark:bg-gray-700"
                />
              </div>
              <!-- card -->
            </div>

            <div v-else class="px-5">
              <!-- empty card -->
              <div class="flex flex-col justify-center my-10">
                <p
                  class="mb-4 mb-10 flex justify-center md:justify-start text-center md:text-left text-4xl tracking-tight font-extrabold text-gray-900"
                >
                  Не нашел своего юриста? <br />
                  Мы поможем
                </p>
                <div class="flex justify-center md:justify-start">
                  <a
                    href="tel:89788838978"
                    class="rounded-lg flex inline-block bg-blue-700 px-6 py-3.5 text-center font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="w-4 h-4 mr-2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"
                      />
                    </svg>

                    8 (978) 8838 978</a
                  >
                </div>
              </div>
              <hr
                class="h-px my-8 bg-gray-200 md:my-10 border-0 dark:bg-gray-700"
              />

              <!-- empty card -->
            </div>
          </div>
        </div>
      </div>
    </Body>
    <div v-if="set.allsities">
      <div class="m-6 p-4 rounded-md">
        <div class="flex flex-wrap gap-3 justify-center">
          <div class="flex items-center w-full">
            <span class="flex-grow bg-gray-200 rounded h-0.5"></span>
            <h3 class="text-2xl font-bold my-3 text-center">
              Города где есть наши юристы
            </h3>
            <span class="flex-grow bg-gray-200 rounded h-0.5"></span>
          </div>
          <div v-for="(allcity, n) in set.allsities" :key="n">
            <a
              :href="route('uslugi.url', [allcity.cities.url])"
              class="bg-gray-100 rounded-md px-3 py-2"
              >{{ allcity.cities.title }}</a
            >
          </div>
        </div>
      </div>
    </div>

    <Pagination v-if="uslugi.total > 10" :links="uslugi.links" />

    <MainFooter>
      <BreadcrumbsUslugi
        class="p-4 bg-white flex flex-col items-center mb-2"
        :city="set.city.title !== '' ? set.city : null"
        :main_usluga="set.main_usluga.url !== 0 ? set.main_usluga : null"
        :second_usluga="set.second_usluga ? set.second_usluga : null"
        :usluga="null"
      />
    </MainFooter>
  </div>
</template>
