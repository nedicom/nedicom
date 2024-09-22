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
  uslugi: Object,
  cities: Object,
  city: String
});

let title = ref("Услуги");

let status = ref(false);

function alertForm (x) {
  status.value = x;
}
</script>

<template>

  <Head>
    <title>{{ title }}</title>
    <meta name="description" content="Все услуги, которые мы оказываем" />
  </Head>

  <div class="min-h-screen">
    <MainHeader />

    <Header :modalPageTitle="title" />

    <Body>

      <div class="bg-white py-6 grid grid-cols-1 md:grid-cols-3">

        <CategoryFilter :category="set.uslugi" :cityUrl="set.city" :main_usluga_url="'none'"
          :second_usluga_url="'none'" @activeSts="alertForm"/>

        <div class="w-full h-full col-span-2">

          <CityFilter :cities="set.cities" :routeurl="'/uslugi'" :status="status" @activeSts="alertForm"/>

          <div v-if="set.uslugi" class="">
            <!-- card -->
            <div v-for="u in set.uslugi" :key="u.id" class="">
              <div v-for="offer in u.mainhasoffer" :key="offer.id" class=" mx-3 md:mx-0">
                <OfferCard :offer="offer" :city="set.cities" />
              </div>
            </div>
            <!-- card -->
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
