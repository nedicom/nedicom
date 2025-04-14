<script setup>
import Bundle from "@/Components/Bundle.vue";

defineProps({
  bundles: Object,
  auth: Object,
});
</script>

<template>
  <div class="text-center mt-20 mb-10">
    <h1
      class="text-2xl/7 font-bold text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight"
    >
      Популярное
    </h1>
  </div>
  <div class="flex justify-center px-3 md:px-10">
    <div
      class="w-full grid grid-cols-1 lg:grid-cols-2 gap-4 place-content-center"
    >
      <Bundle :bundles="bundles" :auth="auth" />
    </div>
  </div>
</template>