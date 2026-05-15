<script setup>
import ShareButtons from "@/Components/ShareButtons.vue";

defineProps({
  articles: Array,
  questions: Array,
  auth: Object,
});
</script>

<template>
  <section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- Практика -->
      <div class="mb-10">
        <h2 class="text-xl font-bold text-gray-900 text-center mb-5">Практика</h2>
        <Carousel v-bind="settings" :breakpoints="breakpoints" :wrapAround="true" :autoplay="0">
          <Slide v-for="item in articles" :key="item.id">
            <div class="mx-2 bg-white rounded-xl shadow-sm border border-gray-100 text-left flex flex-col w-full hover:shadow-md hover:border-gray-200 transition-shadow overflow-hidden">
              <a :href="'/articles/' + item.url" class="block">
                <img
                  v-if="item.practice_file_path"
                  :src="'https://nedicom.ru/' + item.practice_file_path"
                  class="w-full h-32 object-cover"
                  loading="lazy"
                />
                <div v-else class="w-full h-32 bg-gray-100 flex items-center justify-center">
                  <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                  </svg>
                </div>
                <div class="flex flex-col gap-2 p-4 pb-2">
                  <h3 class="text-sm font-semibold text-gray-900 line-clamp-2 leading-snug">{{ item.header }}</h3>
                  <p class="text-sm text-gray-700 line-clamp-3">{{ item.abody }}</p>
                  <div class="mt-auto flex items-center justify-between gap-2 pt-1">
                    <div class="flex items-center gap-1.5 min-w-0">
                      <img
                        v-if="item.avatar_path"
                        :src="'https://nedicom.ru/' + item.avatar_path"
                        class="w-5 h-5 rounded-full object-cover shrink-0"
                      />
                      <div v-else class="w-5 h-5 rounded-full bg-gray-200 shrink-0" />
                      <span class="text-xs text-gray-500 truncate">{{ item.name }}</span>
                    </div>
                    <span class="text-xs text-gray-500 shrink-0">{{ item.created_at }}</span>
                  </div>
                </div>
              </a>
              <div class="mt-auto px-4 pb-3 border-t border-gray-100 pt-2" style="zoom: 0.72; transform-origin: left top;">
                <ShareButtons :bundle="item" :auth="auth" />
              </div>
            </div>
          </Slide>
          <template #addons><Navigation /></template>
        </Carousel>
      </div>

      <!-- Вопросы -->
      <div>
        <h2 class="text-xl font-bold text-gray-900 text-center mb-5">Вопросы пользователей</h2>
        <Carousel v-bind="settings" :breakpoints="breakpoints" :wrapAround="true" :autoplay="0">
          <Slide v-for="item in questions" :key="item.id">
            <div class="mx-2 bg-white rounded-xl shadow-sm border border-gray-100 text-left flex flex-col w-full hover:shadow-md hover:border-gray-200 transition-shadow overflow-hidden">
              <a :href="'/questions/' + item.url" class="flex flex-col gap-2 p-4 pb-2 flex-1">
                <h3 class="text-sm font-semibold text-gray-900 line-clamp-2 leading-snug">{{ item.header }}</h3>
                <p class="text-sm text-gray-700 line-clamp-3 flex-1">{{ item.abody }}</p>
                <div class="flex items-center justify-between gap-2 pt-1">
                  <span class="text-xs text-gray-500 truncate">{{ item.name }}</span>
                  <span class="text-xs text-gray-500 shrink-0">{{ item.created_at }}</span>
                </div>
              </a>
              <div class="mt-auto px-4 pb-3 border-t border-gray-100 pt-2" style="zoom: 0.72; transform-origin: left top;">
                <ShareButtons :bundle="item" :auth="auth" />
              </div>
            </div>
          </Slide>
          <template #addons><Navigation /></template>
        </Carousel>
      </div>

    </div>
  </section>
</template>

<script>
import { defineComponent } from "vue";
import { Carousel, Navigation, Slide } from "vue3-carousel";
import "vue3-carousel/dist/carousel.css";

export default defineComponent({
  name: "FeedCarousels",
  components: { Carousel, Slide, Navigation },
  data: () => ({
    settings: {
      itemsToShow: 1.4,
      snapAlign: "start",
    },
    breakpoints: {
      480:  { itemsToShow: 2.2, snapAlign: "start" },
      768:  { itemsToShow: 3.2, snapAlign: "start" },
      1024: { itemsToShow: 4.2, snapAlign: "start" },
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
