<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import Bundle from "@/Components/Bundle.vue";
import NavLinkLeft from "@/Components/NavLinkLeft.vue";
import Pagination from "@/Components/Pagination.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";

defineProps({
  bundles: Object,
  auth: Object,
  h1: String,
});
</script>

<template>

  <Head>
    <title>{{ h1 }} у юристов</title>
    <meta name="description" :content="h1 + ' у юристов на сайте. Тут юристы дают консультации онлайн, бесплатно, без регистрации и смс.'" />
  </Head>

  <MainHeader :auth="auth" />

  <Header :modalPageTitle="'лента'" />

  <Body>
    <div class="grid grid-cols-1 md:grid-cols-4 bg-slate-100">

      <div class="flex md:justify-end md:mt-12 md:ml-16 px-5 my-5">
        <div class="w-full flex justify-between md:justify-start md:flex-col md:min-w-full md:text-xl md:mr-5">
          <NavLinkLeft :href="route('lenta.popular')" :active="route().current('lenta.popular')">
            Популярное
          </NavLinkLeft>
          <NavLinkLeft :href="route('lenta.new')" :active="route().current('lenta.new')">
            Свежее
          </NavLinkLeft>
          <NavLinkLeft :href="route('lenta.articles')" :active="route().current('lenta.articles')">
            Статьи
          </NavLinkLeft>
          <NavLinkLeft :href="route('lenta.questions')" :active="route().current('lenta.questions')">
            Вопросы
          </NavLinkLeft>
        </div>
      </div>

      <div class="md:col-span-3 md:max-w-7xl md:mt-12 md:mx-auto sm:px-6 lg:px-8 overflow-hidden">
        <div class="flex flex-col gap-9">
          <h1 class="text-center text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ h1 }} у юристов в ленте</h1>
          <!-- card -->
            <Bundle :bundles="bundles"/>
          <!-- card -->
        </div>
        <Pagination v-if="bundles.total > 10" :links="bundles.links" />
      </div>

      

    </div>
  </Body>

  <MainFooter />
</template>
