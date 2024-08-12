<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import PopupDialogue from "@/Layouts/PopupDialogue/PopupDialogue.vue";
import NavLinkLeft from "@/Components/NavLinkLeft.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { ref } from "vue";

defineProps({
  bundles: "Array",
});

let title = ref("Лента");
</script>

<template>
  <Head>
    <title>{{ title }}</title>
    <meta name="description" content="Перечень статей сайта" />
  </Head>

  <MainHeader />

  <Header :ttl="title" />
  <Body>
    <div class="grid grid-cols-4 bg-slate-100">
      <div class="flex justify-end mt-12 ml-16">
        <div class="flex flex-col min-w-full text-xl mr-5">
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
        class="col-span-3 py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 overflow-hidden"
      >
        <div class="grid md:grid-cols-1 gap-9">
          
          <!-- card -->
          <div
            v-for="bundles in bundles"
            :key="bundles.id"
            class="flex justify-center mx-3 md:mx-0 bg-white rounded"
          >
            <article
              class="min-w-full p-6 bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700"
            >
              <div class="flex justify-between items-center mb-5 text-gray-500">
                <span
                  v-if="bundles.aheader"
                  class="bg-primary-100 text-primary-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded dark:bg-primary-200 dark:text-primary-800"
                >
                  <svg
                    class="mr-1 w-3 h-3"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"
                    ></path>
                  </svg>
                  Статья
                </span>

                <span
                  v-else
                  class="bg-primary-100 text-primary-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded dark:bg-primary-200 dark:text-primary-800"
                >
                  <svg
                    class="mr-1 w-3 h-3"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"
                    ></path>
                  </svg>
                  Вопрос
                </span>
                <span class="text-sm">{{ bundles.created_at }}</span>
              </div>
              <h2
                class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"
              >
                <a :href="route('articles/url', bundles.url)">{{
                  bundles.header
                }}</a>
              </h2>
              <p
                class="mb-5 font-light text-gray-500 dark:text-gray-400"
              >
                {{ bundles.abody }}
              </p>

              <div class="flex justify-between items-center">
                <!-- tooltip component-->
                <div class="group flex item-center mb-2">
                  <div>
                    <Link
                      :href="route('lawyer', bundles.id)"
                      class="hover:underline flex items-center"
                    >
                      <img
                        :src="'https://nedicom.ru/' + bundles.avatar_path"
                        class="w-10 h-10 rounded-full"
                        alt="{{ bundles.name }} avatar"
                      />
                      <span class="font-medium dark:text-white ml-2">
                        {{ bundles.name }}
                      </span>
                    </Link>
                  </div>
                </div>

                <!--
                <div class="flex items-center justify-center">
                  <span
                    class="h-1/2 group-hover:opacity-100 transition-opacity bg-gray-800 mx-3 px-1 text-sm text-gray-100 rounded-md opacity-0"
                  >
                    автор -
                    <Link
                      :href="route('lawyer', bundles.userid)"
                      class="hover:underline"
                      target="_blank"
                      >{{ bundles.name }}
                    </Link>
                  </span>
                </div>
                tooltip component -->

                <a
                  :href="route('articles/url', bundles.url)"
                  class="inline-flex items-center font-medium text-primary-600 dark:text-primary-500 hover:underline"
                >
                  подробнее

                  <svg
                    class="ml-2 w-4 h-4"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                      clip-rule="evenodd"
                    ></path>
                  </svg>
                </a>
              </div>
            </article>
          </div>
          <!-- card -->
        </div>
        <!-- card 
      <Pagination :links="bundles.links" />
      --></div>
    </div>
  </Body>

  <MainFooter />

  <PopupDialogue />
</template>


