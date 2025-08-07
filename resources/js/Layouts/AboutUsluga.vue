<script setup>
defineProps({
  usluga: Object,
  lawyer: Object,
  main_usluga: Object,
  reviewscount: Number,
  rating: Number,
});
</script>

<template>
  <!-- secondary banner - about -->
  <section class="bg-white border-b-4 border-indigo-500" id="about">
    <h1
      itemprop="name"
      class="p-2 lg:p-6 lg:py-12 text-4xl tracking-tight font-bold text-gray-900 text-center"
    >
      {{ usluga.usl_name }}
    </h1>
    <div
      class="gap-4 items-center justify-items-center px-4 mx-auto max-w-screen-xl grid lg:px-6"
    >
      <div class="mt-4 md:mt-0">
        <div
          class="mt-2 font-bold text-gray-700 text-3xl text-center lg:text-3xl"
          itemprop="brand"
        >
          {{ lawyer.name }}
        </div>
        <div
          class="font-medium text-gray-900 text-xl lg:text-2xl text-center"
          itemprop="category"
        >
          {{ main_usluga.usl_name }}.
          <span v-if="usluga.cities">{{ usluga.cities.title }}</span>
        </div>
      </div>
      <div class="flex flex-col items-center justify-center h-full">
        <img
          itemprop="image"
          class="w-full md:w-1/2 text-center rounded-3xl"
          :src="'https://nedicom.ru/' + usluga.file_path"
          :alt="usluga.usl_name"
        />

        <div v-if="reviewscount" class="w-full md:w-1/2 flex items-center justify-left mt-1">
          <svg
            class="w-4 h-4 text-yellow-300 me-1"
            aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg"
            fill="currentColor"
            viewBox="0 0 22 20"
          >
            <path
              d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"
            />
          </svg>
          <p class="text-sm font-bold text-gray-900">{{ rating }}</p>

          <a
            href="#reviews"
            class="ml-1 text-sm font-medium text-gray-900 underline hover:no-underline"
            >отзывы: {{ reviewscount }}</a
          >
        </div>
      </div>

      <p
        itemprop="description"
        class="mb-6 font-normal text-gray-900 text-base lg:text-xl leading-normal text-justify italic"
      >
        {{ usluga.usl_desc }}
      </p>
    </div>
  </section>
  <!--secondary banner - about -->
</template>