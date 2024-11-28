<script setup>
import { Link } from '@inertiajs/inertia-vue3';
defineProps({
  links: Array,
})
</script>

<template>
  <div class="flex justify-center py-3 my-3">
    <div class="flex justify-between border-gray-200 rounded-lg">
      <nav class="isolate inline-flex -space-x-px shadow-sm" aria-label="Pagination">
        <Link v-for="(link, k) in links" :href="link.url" :key="k" :disabled="link.url === null"
          :class="{ 'text-blue-500 font-extrabold': link.active, 'font-normal': link.url === null }" v-html="link.label"
          aria-current="page"
          class="relative z-10 inline-flex items-center px-1 md:px-4 md:py-2 text-sm font-medium text-indigo-600 focus:z-20 hover:bg-gray-200 rounded-full" />
      </nav>

    </div>
  </div>
</template>