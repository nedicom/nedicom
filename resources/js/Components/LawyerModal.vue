<script setup>
import { VueFinalModal } from "vue-final-modal";
import { computed } from "vue";

const props = defineProps({
  lawyer: Object,
  showFullNumber: Boolean,
  fullNumber: String,
  maskedNumber: String,
});

const emit = defineEmits(["phone-click", "close"]);

function stars(rating) {
  return Math.min(5, Math.max(0, Math.round(rating ?? 0)));
}

const avatarUrl = computed(() =>
  props.lawyer?.avatar_path
    ? "https://nedicom.ru/" + props.lawyer.avatar_path
    : "/default-avatar.jpg"
);

const profileUrl = computed(() => {
  const u = props.lawyer?.has_uslugi?.[0];
  return u ? "/uslugi/" + u.url : "/lawyers/" + props.lawyer?.id;
});

const specialization = computed(
  () => props.lawyer?.has_uslugi?.[0]?.main?.usl_name ?? "Юрист"
);
</script>

<template>
  <VueFinalModal
    class="flex items-end sm:items-center justify-center"
    content-class="w-full sm:max-w-sm bg-white rounded-t-3xl sm:rounded-2xl shadow-2xl overflow-hidden"
    overlay-transition="vfm-fade"
    content-transition="vfm-slide-down"
    :click-to-close="true"
    :esc-to-close="true"
    @closed="emit('close')"
  >
    <!-- Шапка с фото -->
    <div class="relative bg-gradient-to-b from-blue-50 to-white pt-8 pb-4 px-6 flex flex-col items-center gap-3">
      <button
        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors"
        @click="emit('close')"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>

      <img
        :src="avatarUrl"
        :alt="lawyer?.name"
        class="w-24 h-24 rounded-full object-cover ring-4 ring-white shadow-lg"
      />

      <div class="text-center">
        <p class="text-lg font-bold text-gray-900">{{ lawyer?.name }}</p>
        <p class="text-sm text-blue-600 font-medium mt-0.5">{{ specialization }}</p>
      </div>

      <!-- Рейтинг + мета -->
      <div class="flex items-center gap-4 text-sm text-gray-500">
        <span v-if="lawyer?.total_rating > 0" class="flex items-center gap-1">
          <span class="flex gap-0.5">
            <svg v-for="i in 5" :key="i"
              class="w-3.5 h-3.5"
              :class="i <= stars(lawyer.total_rating) ? 'text-yellow-400' : 'text-gray-200'"
              fill="currentColor" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
          </span>
          <span class="font-medium text-gray-700">{{ lawyer.total_rating.toFixed(1) }}</span>
        </span>
        <span v-if="lawyer?.cities?.title">{{ lawyer.cities.title }}</span>
        <span v-if="lawyer?.expirience">Стаж {{ lawyer.expirience }} лет</span>
      </div>
    </div>

    <!-- Тело -->
    <div class="px-6 pb-6 flex flex-col gap-4">
      <p v-if="lawyer?.has_uslugi?.[0]?.usl_desc" class="text-sm text-gray-600 line-clamp-4 leading-relaxed">
        {{ lawyer.has_uslugi[0].usl_desc }}
      </p>
      <p v-else-if="lawyer?.about" class="text-sm text-gray-600 line-clamp-4 leading-relaxed">
        {{ lawyer.about }}
      </p>

      <!-- CTA: позвонить -->
      <a
        :href="showFullNumber ? 'tel:+79788838978' : 'javascript:void(0)'"
        class="flex items-center justify-center gap-3 w-full py-4 text-white font-semibold
               bg-gradient-to-r from-blue-500 to-blue-700 rounded-xl shadow-md
               hover:scale-[1.02] transition-transform duration-200"
        @click="emit('phone-click', $event)"
      >
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
        </svg>
        <span>{{ showFullNumber ? fullNumber : "Позвонить бесплатно" }}</span>
      </a>

      <!-- Ссылка на профиль -->
      <a
        :href="profileUrl"
        class="text-center text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors"
      >
        Полный профиль юриста →
      </a>
    </div>
  </VueFinalModal>
</template>
