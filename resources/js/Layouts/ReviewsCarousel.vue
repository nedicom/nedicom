<script setup>
import { ref } from 'vue';
import VerifiedIcon from '@/Components/VerifiedIcon.vue';

defineProps({
  reviews: Array,
});

const active = ref(null);

const verifiedLabels = {
  yandex_id: 'Яндекс ID',
  gosuslugi:  'Госуслуги',
  in_person:  'Лично',
  vk_id:      'VK ID',
};

function open(review) {
  active.value = review;
  document.body.style.overflow = 'hidden';
}

function close() {
  active.value = null;
  document.body.style.overflow = '';
}

function stars(rating) {
  return Math.min(5, Math.max(0, Math.round(rating ?? 0)));
}


</script>

<template>
  <section v-if="reviews && reviews.length" class="py-6 bg-gray-50">
    <div>
      <h2 class="text-xl font-bold text-gray-900 text-center mt-6 mb-8">Отзывы клиентов</h2>

      <Carousel v-bind="settings" :breakpoints="breakpoints" :wrapAround="true" :autoplay="5000" :pauseAutoplayOnHover="true">
        <Slide v-for="review in reviews" :key="review.id">
          <div
            class="mx-2 bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-left flex flex-col gap-2 min-h-[140px] w-full cursor-pointer hover:shadow-md hover:border-gray-200 transition-shadow"
            @click="open(review)"
          >
            <!-- Звёзды + дата -->
            <div class="flex items-center justify-between gap-2">
              <div class="flex items-center gap-0.5">
                <template v-for="i in 5" :key="i">
                  <svg class="w-4 h-4 shrink-0"
                    :class="i <= stars(review.rating) ? 'text-yellow-300' : 'text-gray-300'"
                    fill="currentColor" viewBox="0 0 22 20">
                    <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                  </svg>
                </template>
              </div>
              <span v-if="review.human_date" class="text-xs text-gray-500 shrink-0">{{ review.human_date }}</span>
            </div>

            <!-- Проверен + иконка + тип -->
            <div class="flex items-center gap-1.5 justify-end">
              <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Проверен
              </span>
              <VerifiedIcon v-if="review.verified_type" :type="review.verified_type" />
              <span v-if="review.verified_type" class="text-xs text-gray-500">{{ verifiedLabels[review.verified_type] }}</span>
            </div>

            <p class="text-sm text-gray-700 line-clamp-4 flex-1">{{ review.description }}</p>

            <!-- Нижняя строка: клиент + юрист -->
            <div class="mt-auto pt-2 border-t border-gray-100">
              <p class="font-semibold text-sm text-gray-900 truncate">{{ review.fio }}</p>
              <p v-if="review.lawyer || review.usluga?.user" class="text-xs text-gray-500 leading-snug mt-0.5">
                юрист <span class="text-gray-700 font-medium">{{ (review.lawyer ?? review.usluga.user).name }}</span>
              </p>
              <p v-if="review.usluga" class="text-xs text-gray-500 line-clamp-2 leading-snug mt-0.5">
                по услуге <span class="text-blue-600">«{{ review.usluga.usl_name }}»</span>
              </p>
            </div>
          </div>
        </Slide>

        <template #addons>
          <Navigation />
        </template>
      </Carousel>
    </div>
  </section>

  <!-- Модалка -->
  <Teleport to="body">
    <Transition name="modal">
      <div v-if="active" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="close">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="close" />
        <div class="relative bg-white rounded-2xl shadow-xl max-w-lg w-full max-h-[85vh] flex flex-col overflow-hidden">

          <!-- Шапка -->
          <div class="flex items-start justify-between gap-3 p-5 pb-4 border-b border-gray-100">
            <div class="flex items-center gap-1">
              <template v-for="i in 5" :key="i">
                <svg class="w-4 h-4 shrink-0"
                  :class="i <= stars(active.rating) ? 'text-yellow-300' : 'text-gray-300'"
                  fill="currentColor" viewBox="0 0 22 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
              </template>
            </div>
            <div class="flex items-center gap-1.5 ml-auto flex-wrap justify-end">
              <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Проверен
              </span>
              <VerifiedIcon v-if="active.verified_type" :type="active.verified_type" />
              <span v-if="active.verified_type" class="text-xs text-gray-500">{{ verifiedLabels[active.verified_type] }}</span>
              <span v-if="active.human_date" class="text-xs text-gray-500">· {{ active.human_date }}</span>
            </div>
            <button @click="close" class="shrink-0 p-1 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <!-- Текст отзыва -->
          <div class="overflow-y-auto px-5 py-4 flex-1">
            <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ active.description }}</p>
          </div>

          <!-- Подвал: клиент + юрист -->
          <div class="px-5 py-4 border-t border-gray-100 bg-gray-50">
            <div class="flex items-center justify-between gap-2">
              <p class="font-semibold text-sm text-gray-900">{{ active.fio }}</p>
              <span v-if="active.lawyer || active.usluga?.user" class="text-xs text-gray-500 shrink-0">
                юрист <span class="text-gray-700 font-medium">{{ (active.lawyer ?? active.usluga.user).name }}</span>
              </span>
            </div>
            <p v-if="active.usluga" class="text-xs text-gray-500 leading-snug mt-0.5">
              по услуге <span class="text-blue-600">«{{ active.usluga.usl_name }}»</span>
            </p>
          </div>

        </div>
      </div>
    </Transition>
  </Teleport>
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

.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.2s ease;
}
.modal-enter-active .relative,
.modal-leave-active .relative {
  transition: transform 0.2s ease, opacity 0.2s ease;
}
.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}
.modal-enter-from .relative {
  transform: scale(0.95) translateY(8px);
  opacity: 0;
}
</style>
