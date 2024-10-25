<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import { ref } from "vue";
import { Head } from "@inertiajs/inertia-vue3";

defineProps({
  cities: Object,
  auth: Object,
});

let title = ref("Услуги по городам");
</script>

<template>
  <Head>
    <title>{{ title }}</title>
    <meta name="description" content="Все услуги и объявления юристов по городам" />
  </Head>

  <MainHeader :auth="auth" />

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
                Все предложения (сеты) и объявления юристов по городам
              </h2>
            </div>

            <div v-for="city in cities" :key="city.id">
              <h2
                class="mb-2 text-lg font-semibold text-gray-900 dark:text-white"
              >
                

                <a
                    :href="route('offers.city', [city.url])"
                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                  >
                  {{ city.title }}</a
                  >
              </h2>
            </div>
          </div>
        </section>

        <!--... -->
      </div>
    </div>
  </Body>

  <MainFooter />
</template>

