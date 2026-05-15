<script setup>
import { computed } from 'vue';

const props = defineProps({
  city: Object,
});

const crimeaRegions = [10, 93];
const moscowRegions = [6, 66];

const mapUrl = computed(() => {
  const rid = props.city?.regionId;
  if (crimeaRegions.includes(rid)) return 'https://yandex.ru/profile/-/CPgwQ0LY';
  if (moscowRegions.includes(rid)) return 'https://yandex.ru/profile/-/CPgwQ-6q';
  return null;
});
</script>

<template>
  <section v-if="mapUrl" class="py-6 bg-white">
    <div class="max-w-5xl mx-auto px-4">
      <h2 class="text-xl font-bold text-gray-900 text-center mb-4">Приходите в офис</h2>
      <div class="rounded-xl overflow-hidden border border-gray-200 shadow-sm" style="height: 380px;">
        <iframe
          :src="mapUrl"
          width="100%"
          height="100%"
          frameborder="0"
          allowfullscreen
          style="display:block;"
        />
      </div>
    </div>
  </section>
</template>
