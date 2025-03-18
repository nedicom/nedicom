<script setup>
defineProps({
  lawyer: Object,
});
</script>

<template>
  <!-- secondary banner - about -->
  <div class="py-10 w-full lg:w-2/3 text-center lg:text-left" id="#about">
    <div class="flex flex-col items-center lg:flex-row lg:items-left gap-4">
      <img
        itemprop="image"
        class="rounded-full w-48 h-48"
        :src="'https://nedicom.ru/' + lawyer.avatar_path"
        alt="image description"
      />
      <h1 class="text-4xl font-semibold py-5 lg:text-left" itemprop="name">
        {{ lawyer.name }}
      </h1>
    </div>
    <p class="py-5" itemprop="description">{{ lawyer.about }}</p>
    <a type="button"
      :href="'tel:' + lawyer.phone"
      itemprop="telephone"
      class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-lg px-6 py-3.5 me-2 mb-2"
    >
      {{ lawyer.phone }}</a
    >
    <meta itemprop="telephone" :content="lawyer.phone" />
    <meta itemprop="priceRange" content="1000р. - 2000р." />
    <div
      class="py-5"
      itemprop="address"
      itemscope
      itemtype="https://schema.org/PostalAddress"
    >
      <span v-if="lawyer.address" itemprop="streetAddress">{{
        lawyer.address
      }}</span
      >,
      <span v-if="lawyer.city" itemprop="addressLocality">{{
        lawyer.city
      }}</span
      >,
      <span v-if="lawyer.cities">
        <span v-if="lawyer.cities.region" itemprop="addressRegion">{{
          lawyer.cities.region
        }}</span
        >,
        <span v-if="lawyer.cities.postalcode" itemprop="postalCode">{{
          lawyer.cities.postalcode
        }}</span>
        <meta itemprop="addressCountry" content="RU" />
      </span>
    </div>
  </div>
  <!-- secondary banner - about -->
</template>