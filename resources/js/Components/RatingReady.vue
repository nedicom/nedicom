<script setup>
defineProps({
  rating: String,
  reviewRating: Boolean,
});
const stars = [1, 2, 3, 4, 5];
</script>

<template>
  <div class="flex items-center justify-center">
    <span v-for="item in stars" :key="item">
      <svg v-if="item <= rating" class="w-4 h-4 text-yellow-300" aria-hidden="true"
        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
        <path
          d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
      </svg>
      <svg v-else class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
        fill="currentColor" viewBox="0 0 22 20">
        <path
          d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
      </svg>
    </span> 
    <span v-if="reviewRating" itemprop="reviewRating" class="flex items-center justify-center" itemscope itemtype="https://schema.org/Rating">
      <meta itemprop="worstRating" content="1">
      <span v-if="!isNaN(rating)" itemprop="ratingValue" class="ml-1 text-sm font-bold text-gray-900 dark:text-gray-400">{{rating}}</span>
      <span v-else itemprop="ratingValue" class="ml-1 text-sm font-bold text-gray-900 dark:text-gray-400">0</span>
      <span itemprop="bestRating" content="5"></span>
    </span>

    <span v-else itemprop="reviewRating" class="flex items-center justify-center" itemscope itemtype="https://schema.org/Rating">
      <meta itemprop="worstRating" content="1">
      <span v-if="!isNaN(rating)" itemprop="ratingValue" class="ml-1 text-sm font-bold text-gray-900 dark:text-gray-400">{{rating}}</span>
      <span v-else itemprop="ratingValue" class="ml-1 text-sm font-bold text-gray-900 dark:text-gray-400">0</span>
      <span itemprop="bestRating" content="5"></span>
    </span>
  </div>
</template>