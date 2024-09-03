<script setup>
import Modal from "@/Components/Modal.vue";
import RatingReady from "@/Components/RatingReady.vue";

defineProps({
  uslugi: Object,
  city: Object,
  main_usluga: Object,
});

let ModalBtnText = "На консультацию";
</script>

<template>
  <section class="bg-gray-50 py-8 antialiased dark:bg-gray-900 md:py-12">
    <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
      <div
        class="mb-4 items-end justify-between space-y-4 sm:flex sm:space-y-0 md:mb-8"
      >
        <div>
          <h2
            class="mt-3 text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl"
          >
            {{ city.title }} - {{ main_usluga.usl_name }}
          </h2>
        </div>
      </div>
      
        <section   v-if="!uslugi.length"
          class="bg-white px-4 py-8 antialiased dark:bg-gray-900 md:py-16"
        >
          <div
            class="mx-auto grid max-w-screen-xl rounded-lg bg-gray-50 p-4 dark:bg-gray-800 md:p-8 lg:grid-cols-12 lg:gap-8 lg:p-16 xl:gap-16"
          >
            <div class="lg:col-span-5 lg:mt-0">
              <a href="#">
                <img
                  class="mb-4 h-56 w-56 dark:hidden sm:h-96 sm:w-96 md:h-full md:w-full"
                  src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-components.svg"
                  alt="peripherals"
                />
                <img
                  class="mb-4 hidden dark:block md:h-full"
                  src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-components-dark.svg"
                  alt="peripherals"
                />
              </a>
            </div>
            <div class="me-auto place-self-center lg:col-span-7">
              <h1
                class="mb-3 text-2xl font-bold leading-tight tracking-tight text-gray-900 dark:text-white md:text-4xl"
              >
                В этой категории еще нет объявлений <br />
                
              </h1>
              <p class="mb-6 text-gray-500 dark:text-gray-400">
                Станьте первым, к кому обратятся клиенты
              </p>
              <a
          class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
          :href="route('uslugi.add')"
        >
          Добавить объявление
        </a>
            </div>
          </div>
        </section>

   
      

      <div
        v-else
        class="mb-4 grid gap-4 sm:grid-cols-1 md:mb-8 lg:grid-cols-2 xl:grid-cols-2"
      >
        <div
          v-for="usluga in uslugi"
          :key="usluga.id"
          class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800"
        >
          <div class="h-72 w-full">
            <a :href="route('uslugi.url', [usluga.url])">
              <img
                class="mx-auto h-full dark:hidden"
                :src="'https://nedicom.ru/' + usluga.file_path"
                :alt="usluga.usl_name"
              />
            </a>
          </div>
          <div class="pt-6">
            <div class="mb-4 flex items-center justify-between gap-4">
              <span
                class="me-2 rounded bg-primary-100 px-2.5 py-0.5 text-xs font-medium text-primary-800 dark:bg-primary-900 dark:text-primary-300"
              >
                Популярно
              </span>

              <div class="flex items-center justify-end gap-1">
                <button
                  type="button"
                  data-tooltip-target="tooltip-quick-look"
                  class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                >
                  <span class="sr-only"> Перейти </span>
                  <svg
                    class="h-5 w-5"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    fill="none"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke="currentColor"
                      stroke-width="2"
                      d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"
                    />
                    <path
                      stroke="currentColor"
                      stroke-width="2"
                      d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                    />
                  </svg>
                </button>
                <div
                  id="tooltip-quick-look"
                  role="tooltip"
                  class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300 dark:bg-gray-700"
                  data-popper-placement="top"
                >
                  Перейти
                  <div class="tooltip-arrow" data-popper-arrow=""></div>
                </div>

                <button
                  type="button"
                  data-tooltip-target="tooltip-add-to-favorites"
                  class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                >
                  <span class="sr-only"> Добавить в избранное </span>
                  <svg
                    class="h-5 w-5"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke="currentColor"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 6C6.5 1 1 8 5.8 13l6.2 7 6.2-7C23 8 17.5 1 12 6Z"
                    />
                  </svg>
                </button>
                <div
                  id="tooltip-add-to-favorites"
                  role="tooltip"
                  class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300 dark:bg-gray-700"
                  data-popper-placement="top"
                >
                  Добавить в избранное
                  <div class="tooltip-arrow" data-popper-arrow=""></div>
                </div>
              </div>
            </div>

            <a
              :href="route('uslugi.url', [usluga.url])"
              class="text-lg font-semibold leading-tight text-gray-900 hover:underline dark:text-white"
              >{{ usluga.usl_name }}</a
            >

            <div class="mt-2 flex items-center gap-2">
              <div class="flex items-center">
                <RatingReady
                  :rating="
                    (usluga.review_sum_rating / usluga.review_count).toFixed(2)
                  "
                />
              </div>

              <p class="text-sm font-medium text-gray-900 dark:text-white">
                отзывов - {{ usluga.review_count }}
              </p>
            </div>

            <ul class="mt-2 flex items-center gap-4 px-0">
              <li class="flex items-center gap-2">
                <svg
                  class="h-4 w-4 text-gray-500 dark:text-gray-400"
                  aria-hidden="true"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke="currentColor"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13 7h6l2 4m-8-4v8m0-8V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v9h2m8 0H9m4 0h2m4 0h2v-4m0 0h-5m3.5 5.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm-10 0a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"
                  />
                </svg>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                  Быстрая запись
                </p>
              </li>

              <li class="flex items-center gap-2">
                <svg
                  class="h-4 w-4 text-gray-500 dark:text-gray-400"
                  aria-hidden="true"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke="currentColor"
                    stroke-linecap="round"
                    stroke-width="2"
                    d="M8 7V6c0-.6.4-1 1-1h11c.6 0 1 .4 1 1v7c0 .6-.4 1-1 1h-1M3 18v-7c0-.6.4-1 1-1h11c.6 0 1 .4 1 1v7c0 .6-.4 1-1 1H4a1 1 0 0 1-1-1Zm8-3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"
                  />
                </svg>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                  Лучшая цена
                </p>
              </li>
            </ul>

            <div class="mt-4 flex items-center justify-between gap-4">
              <p
                class="text-2xl font-extrabold leading-tight text-gray-900 dark:text-white"
              >
                {{ usluga.price }} р.
              </p>

              <div class="hidden md:flex items-center justify-start px-2">
                <Modal :ModalBtnText="ModalBtnText" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>