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
    <title>{{ h1 }}</title>
    <meta
      name="description"
      :content="
        h1 +
        ' у юристов на сайте. Тут юристы дают консультации онлайн, бесплатно, без регистрации и смс.'
      "
    />
  </Head>

  <MainHeader :auth="auth" />

  <Header :modalPageTitle="'лента'" />

  <Body>
    <div class="grid grid-cols-1 md:grid-cols-4 bg-slate-100 py-5">
      <div class="">
        <div
          class="w-full flex justify-between md:flex-col md:text-xl md:mt-12 px-5"
        >
          <NavLinkLeft
            :href="route('profile.edit')"
            :active="route().current('profile.edit')"
          >
            Профиль
          </NavLinkLeft>
          <NavLinkLeft
            :href="route('lenta.liked')"
            :active="route().current('lenta.liked')"
          >
            Понравилось
          </NavLinkLeft>
          <NavLinkLeft
            :href="route('lenta.bookmarked')"
            :active="route().current('lenta.bookmarked')"
          >
            Закладки
          </NavLinkLeft>
        </div>

        <div
          class="w-full flex justify-between md:flex-col md:text-xl md:mt-12 px-5 my-5"
        >
          <NavLinkLeft
            :href="route('lenta.popular')"
            :active="route().current('lenta.popular')"
          >
            Популярное
          </NavLinkLeft>
          <NavLinkLeft
            :href="route('lenta.new')"
            :active="route().current('lenta.new')"
          >
            Свежее
          </NavLinkLeft>
          <NavLinkLeft
            :href="route('lenta.articles')"
            :active="route().current('lenta.articles')"
          >
            Статьи
          </NavLinkLeft>
          <NavLinkLeft
            :href="route('lenta.questions')"
            :active="route().current('lenta.questions')"
          >
            Вопросы
          </NavLinkLeft>
        </div>
      </div>

      <div
        class="md:col-span-3 md:mt-12 px-3 md:px-10 w-full"
      >
        <div class="flex flex-col gap-9">
          <h1
            class="text-center text-2xl font-bold tracking-tight"
          >
            {{ h1 }}
          </h1>
          <!-- card -->
          <Bundle :bundles="bundles" :auth="auth" />
          <!-- card -->

          <div v-if="bundles.total < 1">
            <div v-if="!auth">
              <a
                :href="route('login')"
                class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                >Войдите</a
              >
              /

              <a
                :href="route('register')"
                class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                >Зарегистрируйтесь</a
              >, чтобы посмотреть, что Вам понравилось и что Вы добавляли в закладки
            </div>
            <div v-else>
              Посмотрите <a
                :href="route('uslugi')"
                class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                >юристов</a
              > или <a
                :href="route('lenta.popular')"
                class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                >посты</a
              >, чтобы дабавить их в закладки или
              понравившееся
            </div>
          </div>
        </div>
        <Pagination v-if="bundles.total > 10" :links="bundles.links" />
      </div>
    </div>
  </Body>

  <MainFooter />
</template>
