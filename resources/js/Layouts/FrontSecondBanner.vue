<script setup>
const props = defineProps({
  city: Object,
  usluga_from_url: Object,
});
</script>

<template>
  <section class="">
    <div
      class="mx-auto h-[50vh] max-w-screen-xl text-center flex flex-col justify-between"
    >

      <div>
        <a
          v-if="props.city"
          href="#utp"
          class="inline-flex justify-between items-center px-1 pr-4 text-sm text-gray-700 bg-gray-100 rounded-full"
          role="alert"
        >
          <span
            v-if="props.city.id != 0"
            class="text-xs bg-blue-600 rounded-full text-white px-4 py-1.5 mr-3"
            >{{ props.city.title }}</span
          >
          <span
            v-else
            class="text-xs bg-blue-600 rounded-full text-white px-4 py-1.5 mr-3"
            >Это к нам</span
          >
          <span class="text-sm font-medium">Нужны гарантии?</span>
          <svg
            class="ml-2 w-5 h-5"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"
            ></path>
          </svg>
        </a>
      </div>

      <h1
        v-if="usluga_from_url"
        class="text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl"
      >
        <span v-if="usluga_from_url.usl_name">{{
          usluga_from_url.usl_name
        }}</span>
        <span v-else>Чтобы разобраться в проблеме достаточно позвонить</span>
      </h1>
      <h1
        v-else
        class="text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl"
      >
        Чтобы разобраться в проблеме достаточно позвонить
      </h1>

      <p
        class="text-lg font-normal text-gray-500 lg:text-xl sm:px-16 xl:px-48"
      >
        Нажмите на номер чтобы позвонить
      </p>

      <div
        class="px-5 flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4"
      >
        <a
          href="tel:+79788838978"
          class="inline-flex justify-center items-center py-5 px-5 text-4xl font-bold text-center text-gray-900 rounded-lg border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-800"
        >
          89788838978
        </a>
      </div>

    </div>
  </section>
</template>