<script setup>
import { computed } from 'vue';

const props = defineProps({
  city: Object,
});

const crimeaRegions = [10, 93];
const moscowRegions = [6, 66];

const mapUrl = computed(() => {
  const rid = props.city?.regionId;
  if (crimeaRegions.includes(rid))
    return 'https://yandex.ru/map-widget/v1/org/mina_i_partnery/13809978899/?ll=34.096419%2C44.954004&z=16';
  if (moscowRegions.includes(rid))
    return 'https://yandex.ru/map-widget/v1/org/mina_i_partnery/241478349122/?ll=37.649453%2C55.777555&z=16';
  return null;
});
</script>

<template>
  <section v-if="mapUrl" class="py-6 bg-white">
    <div class="max-w-5xl mx-auto px-4">
      <h2 class="text-xl font-bold text-gray-900 text-center mt-6 mb-8">Приходите в офис</h2>
      <div class="rounded-xl overflow-hidden border border-gray-200 shadow-sm" style="position:relative;height:400px;">
        <iframe
          :src="mapUrl"
          width="100%"
          height="100%"
          frameborder="1"
          allowfullscreen="true"
          style="position:relative;display:block;"
        />
      </div>
    </div>
  </section>
</template>
