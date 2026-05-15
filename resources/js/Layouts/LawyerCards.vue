<script setup>
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
  lawyers: Array,
});

function stars(rating) {
  const r = Math.round((rating || 0) * 2) / 2;
  return Math.min(5, Math.max(0, r));
}

function avatarUrl(path) {
  if (!path) return '/default-avatar.jpg';
  return 'https://nedicom.ru/' + path;
}
</script>

<template>
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Заголовок секции -->
    <div class="text-center mb-8">
      <h2 class="text-xl font-bold text-gray-900">Мина и партнёры</h2>
      <p class="mt-2 text-sm text-gray-500">Проверенные юристы — выберите специалиста</p>
    </div>

    <!-- Сетка карточек -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
      <Link
        v-for="lawyer in props.lawyers"
        :key="lawyer.id"
        :href="lawyer.has_uslugi && lawyer.has_uslugi[0] ? '/uslugi/' + lawyer.has_uslugi[0].url : '/lawyers/' + lawyer.id"
        class="group flex flex-col bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 overflow-hidden"
      >
        <!-- Фото -->
        <div class="aspect-square overflow-hidden bg-gray-100">
          <img
            :src="avatarUrl(lawyer.avatar_path)"
            :alt="lawyer.name"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            loading="lazy"
          />
        </div>

        <!-- Контент -->
        <div class="flex flex-col flex-1 p-3 gap-1">
          <!-- Имя -->
          <p class="font-semibold text-gray-900 text-sm leading-tight line-clamp-2">{{ lawyer.name }}</p>

          <!-- Специализация или bio -->
          <p class="text-xs text-gray-500 line-clamp-2 flex-1">
            <span v-if="lawyer.has_uslugi && lawyer.has_uslugi[0]?.main?.usl_name">{{ lawyer.has_uslugi[0].main.usl_name }}</span>
            <span v-else-if="lawyer.about">{{ lawyer.about }}</span>
            <span v-else class="italic">Юрист</span>
          </p>

          <!-- Рейтинг + город -->
          <div class="flex items-center justify-between mt-1">
            <!-- Звёзды -->
            <div class="flex items-center gap-0.5" v-if="lawyer.total_rating > 0">
              <template v-for="i in 5" :key="i">
                <svg
                  class="w-3 h-3"
                  :class="i <= stars(lawyer.total_rating) ? 'text-yellow-400' : 'text-gray-200'"
                  fill="currentColor" viewBox="0 0 20 20"
                >
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
              </template>
              <span class="text-xs text-gray-500 ml-1">{{ lawyer.total_rating.toFixed(1) }}</span>
            </div>
            <div v-else class="text-xs text-gray-500">Новый</div>

            <!-- Город -->
            <span v-if="lawyer.cities" class="text-xs text-gray-500 truncate max-w-[60px]">
              {{ lawyer.cities.title }}
            </span>
          </div>

          <!-- Стаж -->
          <div v-if="lawyer.expirience" class="text-xs text-blue-600 font-medium">
            Стаж {{ lawyer.expirience }} лет
          </div>
        </div>
      </Link>
    </div>

    <!-- Ссылка "Все юристы" -->
    <div class="text-center mt-6">
      <Link
        href="/lawyers"
        class="inline-flex items-center gap-2 text-blue-700 hover:text-blue-900 font-medium text-sm transition-colors"
      >
        Все юристы
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </Link>
    </div>

  </section>
</template>
