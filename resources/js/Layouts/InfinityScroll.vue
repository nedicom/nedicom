<script setup>
import Bundle from "@/Components/Bundle.vue";

defineProps({
  bundles: Object,
  auth: Object,
});
</script>

<template>
  <div class="text-center mb-6 mt-10">
    <h2 class="text-xl font-bold text-gray-900">
      Практика и посты
    </h2>
  </div>
  <div class="flex justify-center px-3 md:px-10">
    <div
      class="w-full grid grid-cols-1 lg:grid-cols-2 gap-4 place-content-center"
    >
      <Bundle :bundles="bundles" :auth="auth" />
    </div>
  </div>

</template>