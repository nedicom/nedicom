<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import CategoryFilter from "@/Components/CategoryFilter.vue";
import CityFilter from "@/Components/CityFilter.vue";
import OfferCard from "@/Components/OfferCard.vue";
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
  routeurl: String
  
});

let title = ref("Услуги");

let status = ref(false);

function alertForm(x) {
  status.value = x;
}
</script>

<template>

  <Head>
    <title>{{ set.main_usluga.usl_name }} {{ set.second_usluga.usl_name }} {{ set.city.title }}</title>
    <meta name="description" :content="main_usluga.usl_desc" />
  </Head>

  <div class="min-h-screen">
    <MainHeader />

    <Header :modalPageTitle="title" />

    <Body>

      <div class="bg-white py-6 grid grid-cols-1 md:grid-cols-4">
        <div>
          <CityFilter :cities="set.cities" :routeurl="set.routeurl" :status="status" :main_usluga_url="main_usluga.url"
          :second_usluga_url="second_usluga.url" @activeSts="alertForm" />

          <CategoryFilter :category="set.category" :cityUrl="set.city.url" :main_usluga_url="main_usluga.url"
          :second_usluga_url="second_usluga.url" @activeSts="alertForm" />
        </div>
        <div class="w-full h-full col-span-3 md:ml-10 my-5 md:my-10">
          <div v-if="set.uslugi">
            <div v-if="set.uslugi[0]">
            <!-- card -->
            <div v-for="offer in set.uslugi" :key="offer.id" class="px-3 md:px-0 w-full md:w-4/5 ">
                <OfferCard :offer="offer" :city="set.cities" />
                <hr class="h-px my-8 bg-gray-200 md:my-10 border-0 dark:bg-gray-700">
            </div>
            <!-- card -->
          </div>
          <div v-else>
            Юристов не обнаружено
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
