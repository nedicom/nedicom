<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import { ref } from "vue";
import { Head } from "@inertiajs/inertia-vue3";

defineProps({
  offers: Object,
  cities: Object,
  user: Object,
});

let title = ref("Все услуги");
</script>

<template>
  <Head>
    <title>{{ title }}</title>
    <meta name="description" content="Все услуги" />
  </Head>

  <MainHeader />

  <Header :ttl="title" />

  <Body>
    <div class="bg-white py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <section class="bg-white dark:bg-gray-900">
          <div
            class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-6"
          >
            <div class="mx-auto mb-8 max-w-screen-sm lg:mb-16">
              <h2
                class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white"
              >
                Все предложения (сеты) по городам
              </h2>
              <p
                v-if="user.isadmin == 1"
                class="font-light text-gray-500 sm:text-xl dark:text-gray-400"
              >
                <a
                  :href="route('offer.add')"
                  class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                >
                  Добавить сет
                </a>
              </p>
            </div>

            <div v-for="city in cities" :key="city.id">
              <h2
                class="mb-2 text-lg font-semibold text-gray-900 dark:text-white"
              >
                {{ city.title }}
              </h2>

              <div
                class="flex items-start"
              >
                <div
                  v-for="offer in offers"
                  :key="offer.id"                  
                >
                  <div v-if="city.id == offer.city_id" class="text-center w-64 text-gray-500 dark:text-gray-400">
                    <img
                      class="mx-auto mb-4 w-36 h-36 object-cover rounded-full"
                      :src="offer.uslugis_file_path"
                      :alt="offer.uslugis_usl_name"
                    />
                    <h3
                      class="mb-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"
                    >
                      <a
                        :href="route('uslugi.url', [offer.uslugis_url])"
                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                      >
                        {{ offer.uslugis_usl_name }}</a
                      >
                    </h3>
                    <p>
                      <a
                        :href="route('offer.url', [offer.offer_id])"
                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                      >
                        {{ offer.offer_title }}</a
                      >
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!--... -->
      </div>
    </div>
  </Body>

  <MainFooter />
</template>

<script>
import Pagination from "@/Components/Pagination.vue";
export default {
  components: {
    Pagination,
  },
};
</script>

