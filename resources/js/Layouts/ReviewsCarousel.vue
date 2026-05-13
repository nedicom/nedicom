<script setup>
defineProps({
  reviews: Array,
});

function stars(rating) {
  return Math.min(5, Math.max(0, Math.round(rating ?? 0)));
}
</script>

<template>
  <section v-if="reviews && reviews.length" class="py-10 bg-gray-50">
    <div>
      <h2 class="text-2xl font-bold text-gray-900 text-center mb-8">Отзывы клиентов</h2>

      <Carousel v-bind="settings" :breakpoints="breakpoints" :wrapAround="true" :autoplay="5000" :pauseAutoplayOnHover="true">
        <Slide v-for="review in reviews" :key="review.id">
          <div class="mx-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-5 text-left flex flex-col gap-3 min-h-[180px] w-full">

            <div class="flex items-center gap-0.5">
              <template v-for="i in 5" :key="i">
                <svg class="w-4 h-4 shrink-0"
                  :class="i <= stars(review.rating) ? 'text-yellow-400' : 'text-gray-200'"
                  fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
              </template>
            </div>

            <p class="text-sm text-gray-700 line-clamp-4 flex-1">{{ review.description }}</p>

            <div class="flex items-center justify-between mt-auto pt-2 border-t border-gray-50">
              <span class="font-semibold text-sm text-gray-900 truncate mr-2">{{ review.fio }}</span>
              <span v-if="review.usluga" class="text-xs text-blue-600 truncate max-w-[140px] shrink-0">
                {{ review.usluga.usl_name }}
              </span>
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
