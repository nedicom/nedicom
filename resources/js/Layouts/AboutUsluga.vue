<script setup>
defineProps({
  usluga: Object,
  lawyer: Object,
  main_usluga: Object,
});
</script>

<template>
  <!-- secondary banner - about -->
  <section class="bg-white " id="about">
    <h1
      itemprop="name"
      class="mb-4 px-6 py-6 lg:py-12 lg:my-12 text-4xl tracking-tight font-bold text-gray-900 lg:text-center"
    >
      {{ usluga.usl_name }}
    </h1>
    <div
      class="gap-8 items-center px-4 mx-auto max-w-screen-xl xl:gap-16 md:grid md:grid-cols-2 lg:px-6"
    >
      <div class="mt-4 md:mt-0">
        <p
          itemprop="description"
          class="mb-6 font-light text-gray-700 text-lg lg:text-2xl text-justify"
        >
          {{ usluga.usl_desc }}
        </p>

        <div
          class="mt-2 font-bold text-gray-700 text-xl text-center lg:text-4xl"
          itemprop="brand"
        >
          {{ lawyer.name }}
        </div>
        <div
          class="font-medium text-gray-900 text-base lg:text-xl text-center "
          itemprop="category"
        >
          {{ main_usluga.usl_name }}.
          <span v-if="usluga.cities">{{ usluga.cities.title }}</span>
        </div>
      </div>
      <img
        itemprop="image"
        class="w-full rounded-3xl"
        :src="'https://nedicom.ru/' + usluga.file_path"
        :alt="usluga.usl_name"
      />
    </div>
  </section>
  <!--secondary banner - about -->
</template>