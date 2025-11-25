<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import BreadcrumbsUslugi from "@/Components/BreadcrumbsUslugi.vue";
import Body from "@/Layouts/Body.vue";
import Seodesc from "@/Components/Seodesc.vue";
import Seoquestion from "@/Components/Seoquestion.vue";
import CtaLwr from "@/Components/CtaLwr.vue";
import CategoryFilter from "@/Components/CategoryFilter.vue";
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
  cityheader: Object,
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
  ? set.second_usluga.usl_name
  : set.main_usluga.usl_name;

title.value =
  set.city.title != ""
    ? title.value + " " + set.city.title
    : title.value + " по всем городам";

const childRef = ref(null);

const callChildMethod = () => {
  childRef.value?.open();
};

</script>

<template>
  <Head>
    <title>{{ title }}</title>
    <meta name="description" :content="description" />
  </Head>

  <div class="min-h-screen">
    <MainHeader
      :auth="set.auth"
      :city="set.city"
      ref="childRef"
      :mainurl="set.main_usluga?.url || null"
      :secondurl="set.second_usluga ? set.second_usluga.url : null"
      :reloadpage="true"
    />

    <Body>
      <div class="bg-white grid grid-cols-1 md:grid-cols-4 px-3">
        <div class="" flex>
          <div
            class="flex max-w-xl justify-end items-center cursor-pointer my-5 mr-5 lg:hidden"
          >
            <button
              @click="callChildMethod()"
              type="button"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5"
            >
              <span v-if="set.city.title">{{ set.city.title }}</span>
              <span v-else>Выберите регион (город)</span>
            </button>
          </div>

          <CategoryFilter
            :category="set.category"
            :cityUrl="set.city.url"
            :main_usluga_url="set.main_usluga.url ? set.main_usluga.url : '0'"
            :second_usluga_url="
              set.second_usluga ? set.second_usluga.url : 'false'
            "
          />
        </div>

        <div
          class="w-full h-full col-span-3 md:pl-10"
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
          <meta itemprop="name" :content="title" />

          <div class="my-6 md:mb-0">
            <h1
              class="md:mb-4 flex md:justify-start justify-center leading-tight text-gray-900 text-xl lg:text-2xl"
            >
              <span class="font-bold border-b-2">
                <a
                  v-if="set.city.title != ''"
                  :href="route('uslugi.url', [set.city.url])"
                  class="font-medium text-blue-600 hover:underline"
                  ><span> {{ set.city.title }}</span>
                </a>

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

            <p
              itemprop="description"
              class="flex md:justify-start justify-center my-5"
            >
              <span v-if="set.second_usluga">{{
                set.second_usluga.usl_desc
              }}</span>
              <span v-else>{{ set.main_usluga.usl_desc }}</span>
            </p>

            <div
              v-if="set.sumrating !== 0 && set.countrating !== 0"
              class="mb-4 flex justify-start gap-2 text-sm font-medium text-gray-900"
              itemprop="aggregateRating"
              itemscope
              itemtype="https://schema.org/AggregateRating"
            >
              <span class="flex items-center">
                <RatingReady
                  :rating="Number((set.sumrating / set.countrating).toFixed(2))"
                  :reviewRating="false"
                />
              </span>
              <span itemprop="ratingValue">{{
                Number((set.sumrating / set.countrating).toFixed(2))
              }}</span>
              из <span itemprop="bestRating">5</span> на основании
              <span itemprop="ratingCount">{{ set.countrating }}</span> отзывов
            </div>

            <div
              v-else
              class="mb-4 flex justify-start gap-2 text-sm font-medium text-gray-900"
            >
              <span class="flex items-center">
                <RatingReady :rating="0" :reviewRating="false" />
              </span>
              <span>0</span> из <span>5</span> на основании
              <span>0</span> отзывов
            </div>
          </div>

          <div v-if="set.uslugi">
            <div
              v-if="set.uslugi.data"
              itemprop="offers"
              itemscope
              itemtype="https://schema.org/AggregateOffer"
            >
              <!-- Мета-теги вынесены из видимого контента -->
              <meta itemprop="priceCurrency" content="RUB" />
              <meta itemprop="lowPrice" :content="set.min" />
              <meta itemprop="highPrice" :content="set.max" />
              <meta itemprop="offerCount" :content="set.count" />

              <div
                class="flex justify-start my-2 w-full text-sm font-medium text-gray-900"
              >
                Цены за консультацию от {{ set.min }} до {{ set.max }} рублей.
                Найдено юристов -
                {{ set.count }}
              </div>

              <!-- card -->
              <hr class="h-px my-3 bg-gray-200 border-0" />
              <OfferCard
                v-for="offer in set.uslugi.data"
                :key="offer.id"
                :offer="offer"
                :city="set.cities"
                :getlwr="set.getLawyer"
                :auth="set.auth"
              />
              <!-- card -->
            </div>

            <div v-else class="px-5">
              <!-- empty card -->
              <div class="flex flex-col justify-center my-10">
                <p
                  class="mb-10 flex justify-center md:justify-start text-center md:text-left text-4xl tracking-tight font-extrabold text-gray-900"
                >
                  Не нашел своего юриста? <br />
                  Мы поможем
                </p>
                <div class="flex justify-center md:justify-start">
                  <a
                    :href="route('questions.add')"
                    class="rounded-lg inline-block bg-blue-700 px-6 py-3.5 text-center font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                  >
                    задать вопрос онлайн
                  </a>
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
              v-if="allcity.cities"
              :href="route('uslugi.url', [allcity.cities.url])"
              class="bg-gray-100 rounded-md px-3 py-2"
            >
              {{ allcity.cities.title }}
            </a>
          </div>
        </div>
      </div>
    </div>

    <Pagination v-if="uslugi.total > 10" :links="uslugi.links" />

    <Seodesc :blockdata="set.main_usluga" />

    <Seoquestion :questiondata="set.main_usluga" />

    <CtaLwr v-if="true" />

    <MainFooter>
      <BreadcrumbsUslugi
        class="p-4 bg-white flex flex-col items-center mb-2 w-full"
        :city="set.city.title !== '' ? set.city : null"
        :main_usluga="set.main_usluga.url !== 0 ? set.main_usluga : null"
        :second_usluga="set.second_usluga ? set.second_usluga : null"
        :usluga="null"
      />
    </MainFooter>
  </div>
</template>
