<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import CategoryFilter from "@/Components/CategoryFilter.vue";
import CityFilter from "@/Components/CityFilter.vue";
import OfferCard from "@/Components/OfferCard.vue";
import RatingReady from "@/Components/RatingReady.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import { ref } from "vue";
import { Head } from "@inertiajs/inertia-vue3";

let set = defineProps({
  city: Object,
  main_usluga: Object,
  second_usluga: Object,
  uslugi: Object,
  cities: Object,
  category: Object,
  routeurl: String,
  count: Number,
  max: Number,
  min: Number,
  countrating: Number,
  sumrating: Number,
});

if (set.city.id == 0) {
  set.city.title = 'в котором Вы живете';
}
let title = ref("Услуги");

let status = ref(false);

function alertForm(x) {
  status.value = x;
}
</script>

<template>

  <Head>
    <title v-if="set.second_usluga">Юристы по городу {{ set.city.title }} в категории - {{ set.second_usluga.usl_name }}
    </title>
    <title v-else>Юристы по городу {{ set.city.title }} в категории - {{ set.main_usluga.usl_name }} </title>
    <meta v-if="set.second_usluga" name="description" :content="set.second_usluga.usl_desc" />
    <meta v-else name="description" :content="set.main_usluga.usl_desc" />
  </Head>

  <div class="min-h-screen">
    <MainHeader />

    <Header :modalPageTitle="title" />

    <Body>
      <div class="bg-white grid grid-cols-1 md:grid-cols-4">

        <div>
          <CityFilter :cities="set.cities" :routeurl="set.routeurl" :status="status" :main_usluga_url="main_usluga.url"
            :second_usluga_url="[second_usluga ? second_usluga.url : false]" @activeSts="alertForm" />

          <CategoryFilter :category="set.category" :cityUrl="set.city.url" :main_usluga_url="main_usluga.url"
            :second_usluga_url="[second_usluga ? second_usluga.url : false]" @activeSts="alertForm" />
        </div>
        <div class="w-full h-full col-span-3 md:pl-10 my-5 md:my-0" itemscope itemtype="https://schema.org/Product">

          <meta v-if="set.second_usluga" itemprop="image"
            :content="'https://nedicom.ru/' + set.second_usluga.file_path">
          <meta v-else itemprop="image" :content="'https://nedicom.ru/' + set.main_usluga.file_path">

          <div class="mx-auto grid max-w-screen-xl px-4 md:grid-cols-12 lg:gap-12 xl:gap-0 mb-6 md:mb-0">
            <div class="content-center justify-self-start md:col-span-7 md:text-start">
              <span>
                <h1
                  class="md:mb-4 text-sm leading-tight text-gray-900 dark:text-white md:max-w-2xl md:text-2xl xl:text-2xl">
                  <span v-if="set.second_usluga">Юристы в категории "<span itemprop="name" class="font-bold">{{
                    set.second_usluga.usl_name
                  }}</span>"</span>
                  <span v-else>Юристы в категории "<span itemprop="name" class="font-bold">{{ set.main_usluga.usl_name
                      }}</span>"</span>
                  по городу
                  <span class="font-bold">{{ set.city.title }}</span>
                </h1>
              </span>

              <div v-if="set.sumrating && set.countrating" class="mb-4 flex justify-start gap-2 text-sm font-medium text-gray-900" itemprop="aggregateRating"
                itemscope itemtype="https://schema.org/AggregateRating">
                <span class="flex items-center" itemprop="ratingValue">
                  <RatingReady :rating="(set.sumrating / set.countrating).toFixed(2)
                    " />
                </span> из <span itemprop="bestRating">5.00</span>
                <span itemprop="ratingCount">
                  на основании {{ set.countrating }} отзывов
                </span>
              </div>


              <p
                class="hidden md:block mb-4 max-w-2xl text-gray-500 dark:text-gray-400 md:mb-12 md:text-lg mb-3 lg:mb-5 lg:text-xl">
                Не нашел своего? звони нам</p>
              <a href="tel:89788838978"
                class="hidden md:inline-block rounded-lg bg-blue-700 px-6 py-3.5 text-center font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                8 (978) 8838 978</a>
            </div>

          </div>

          <div v-if="set.uslugi">
            <div v-if="set.uslugi[0]">
              <!-- card -->
              <div v-for="offer in set.uslugi" :key="offer.id" class="px-3 my-2 md:px-0 w-full md:w-4/5 text-sm font-medium text-gray-900" itemprop="offers"
                itemscope itemtype="https://schema.org/AggregateOffer">
                Цены за консультацию
                <meta itemprop="priceCurrency" content="RUB" />
                от <span itemprop="lowPrice" content="1000">{{ set.min }}</span>
                до <span itemprop="highPrice" content="2000">{{ set.max }} рублей</span>
                у <span itemprop="offerCount">{{ set.count }}</span> <span v-if="set.count == 1">юриста</span><span v-else>юристов</span>
                <OfferCard :offer="offer" :city="set.cities" />
                <hr class="h-px my-8 bg-gray-200 md:my-10 border-0 dark:bg-gray-700">
              </div>
              <!-- card -->
            </div>
          </div>

        </div>

      </div>
    </Body>

    <MainFooter />
  </div>
</template>

<script>
import Pagination from "@/Components/Pagination.vue";
export default {
  components: {
    Pagination,
  },
};
</script>
