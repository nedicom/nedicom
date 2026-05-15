<script setup>
defineProps({
  reviews: Array,
});

function stars(rating) {
  return Math.min(5, Math.max(0, Math.round(rating ?? 0)));
}

function formatDate(dateStr) {
  if (!dateStr) return null;
  const d = new Date(dateStr.replace(' ', 'T'));
  if (isNaN(d.getTime())) return null;
  return d.toLocaleDateString('ru-RU', { day: 'numeric', month: 'long', year: 'numeric' });
}
</script>

<template>
  <section v-if="reviews && reviews.length" class="py-6 bg-gray-50">
    <div>
      <h2 class="text-xl font-bold text-gray-900 text-center mb-5">Отзывы клиентов</h2>

      <Carousel v-bind="settings" :breakpoints="breakpoints" :wrapAround="true" :autoplay="5000" :pauseAutoplayOnHover="true">
        <Slide v-for="review in reviews" :key="review.id">
          <div class="mx-2 bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-left flex flex-col gap-2 min-h-[140px] w-full">

            <!-- Верхняя строка: звёзды + бейдж + дата -->
            <div class="flex items-center justify-between gap-2">
              <div class="flex items-center gap-0.5">
                <template v-for="i in 5" :key="i">
                  <svg class="w-4 h-4 shrink-0"
                    :class="i <= stars(review.rating) ? 'text-yellow-400' : 'text-gray-200'"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                  </svg>
                </template>
              </div>
              <div class="flex items-center gap-1.5 shrink-0">
                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-green-700 bg-green-50 border border-green-200 px-1.5 py-0.5 rounded-full">
                  <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                  Проверен
                </span>
                <span v-if="formatDate(review.created_at)" class="text-[10px] text-gray-400">
                  {{ formatDate(review.created_at) }}
                </span>
              </div>
            </div>

            <p class="text-sm text-gray-700 line-clamp-4 flex-1">{{ review.description }}</p>

            <!-- Нижняя строка: клиент + юрист -->
            <div class="mt-auto pt-2 border-t border-gray-100 flex items-center justify-between gap-2">
              <div class="min-w-0">
                <p class="font-semibold text-sm text-gray-900 truncate">{{ review.fio }}</p>
                <p v-if="review.usluga" class="text-[11px] text-blue-600 truncate">{{ review.usluga.usl_name }}</p>
              </div>
              <div v-if="review.lawyer" class="flex items-center gap-1.5 shrink-0">
                <img
                  :src="review.lawyer.avatar_path ? 'https://nedicom.ru/' + review.lawyer.avatar_path : '/default-avatar.jpg'"
                  :alt="review.lawyer.name"
                  class="w-7 h-7 rounded-full object-cover ring-1 ring-gray-200"
                />
                <span class="text-[11px] text-gray-500 max-w-[80px] truncate">{{ review.lawyer.name }}</span>
              </div>
            </div>

          </div>
        </Slide>

        <template #addons>
          <Navigation />
        </template>
      </Carousel>
    </div>
  </section>
</template>

<script>
import { defineComponent } from "vue";
import { Carousel, Navigation, Slide } from "vue3-carousel";
import "vue3-carousel/dist/carousel.css";

export default defineComponent({
  name: "ReviewsCarousel",
  components: { Carousel, Slide, Navigation },
  data: () => ({
    settings: {
      itemsToShow: 1.4,
      snapAlign: "start",
    },
    breakpoints: {
      480:  { itemsToShow: 2.2,  snapAlign: "start" },
      768:  { itemsToShow: 3.2,  snapAlign: "start" },
      1024: { itemsToShow: 4.2,  snapAlign: "start" },
      1280: { itemsToShow: 5.2,  snapAlign: "start" },
    },
  }),
});
</script>

<style scoped>
.carousel__slide {
  padding: 4px 0;
  align-items: stretch;
}
</style>
