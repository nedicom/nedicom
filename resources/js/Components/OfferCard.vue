<script setup>
import { ref, onMounted } from "vue";

import Modal from "@/Components/Modal.vue";
import RatingReady from "@/Components/RatingReady.vue";

defineProps({
  offer: Object,
  city: Object,
});

let ModalBtnText = "На консультацию";
let secondtext = "";

const animate = ref("animate-pulse blur");

const onImgLoad = function () {
  animate.value = false;
}

</script>

<template>
  <section>
    <div class="mx-auto 2xl:px-0" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
      <div class="grid gap-4 grid-cols-1 md:grid-cols-2 content-center">
        <div class="bg-white flex items-center">
          <div class="h-72 w-full bg-cover" :class="animate"
            style="background-image: url('storage/images/services/appeal.webp');">
            <a :href="route('uslugi.canonical.url', [
              offer.cities.url,
              offer.main.url,
              offer.second.url,
              offer.url,
            ])
              ">
              <img class="h-full md:rounded-lg md:shadow-xl object-cover bg-" :src="'https://nedicom.ru/' + offer.file_path" :alt="offer.usl_name"
                @load="onImgLoad()" loading="lazy" itemprop="image"/>
            </a>
          </div>
        </div>
        <div class="">
          <div class="mb-4 flex items-center justify-between gap-4">
            <span
              class="me-2 bg-primary-100 px-2.5 py-0.5 text-xs font-medium text-primary-800 dark:bg-primary-900 dark:text-primary-300">
              Популярно
            </span>

            <div class="flex items-center justify-end gap-1">
              <button type="button" data-tooltip-target="tooltip-quick-look"
                class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                <span class="sr-only"> Перейти </span>
                <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                  fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-width="2"
                    d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                  <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
              </button>
              <div id="tooltip-quick-look" role="tooltip"
                class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300 dark:bg-gray-700"
                data-popper-placement="top">
                Перейти
                <div class="tooltip-arrow" data-popper-arrow=""></div>
              </div>

              <button type="button" data-tooltip-target="tooltip-add-to-favorites"
                class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                <span class="sr-only"> Добавить в избранное </span>
                <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                  viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6C6.5 1 1 8 5.8 13l6.2 7 6.2-7C23 8 17.5 1 12 6Z" />
                </svg>
              </button>
              <div id="tooltip-add-to-favorites" role="tooltip"
                class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300 dark:bg-gray-700"
                data-popper-placement="top">
                Добавить в избранное
                <div class="tooltip-arrow" data-popper-arrow=""></div>
              </div>
            </div>
          </div>

          <a itemprop="url" :href="route('uslugi.canonical.url', [
            offer.cities.url,
            offer.main.url,
            offer.second.url,
            offer.url,
          ])
            " class="text-lg font-semibold leading-tight text-gray-900 hover:underline dark:text-white"
            ><span itemprop="name">{{ offer.usl_name }}</span></a>

          <div class="mt-2 flex items-center gap-2">
            <div class="flex items-center">
              <RatingReady :rating="(offer.review_sum_rating / offer.review_count).toFixed(2)
                " :reviewRating="true"/>
            </div>

            <p class="text-sm font-medium text-gray-900 dark:text-white">
              отзывов - {{ offer.review_count }}
            </p>
          </div>

          <ul class="mt-2 flex flex-col items-start gap-1 px-0">
            <li class="flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 dark:text-gray-400" fill="none"
                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
              </svg>

              <p v-if="offer.cities" class="text-sm font-medium text-gray-500 dark:text-gray-400">
                {{ offer.cities.title }}
              </p>
            </li>

            <li v-if="offer.main" class="flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="h-4 w-4 text-gray-500 dark:text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
              </svg>

              <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                {{ offer.main.name }}
              </p>
            </li>

            <li v-if="offer.second" class="flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="h-4 w-4 text-gray-500 dark:text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
              </svg>

              <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                {{ offer.second.name }}
              </p>
            </li>
          </ul>
          - "<span class="text-sm font-bold text-gray-700 dark:text-gray-400" itemprop="description">{{ offer.usl_desc}}</span>"

          <div class="mt-4 flex items-center justify-between gap-4">
            <p class="text-2xl font-extrabold leading-tight text-gray-900 dark:text-white">
              {{ offer.price }} р.
            </p>
            <meta itemprop="price" :content="offer.price">
            <meta itemprop="priceCurrency" content="RUB">
            <link itemprop="availability" href="http://schema.org/InStock">

            <div class="hidden md:flex items-center justify-start px-2">
              <Modal :ModalBtnText="ModalBtnText" :secondtext="secondtext"
                :modalPageTitle="'страница поиска услуг - ' + offer.usl_name + ' - ' + offer.cities.title" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>