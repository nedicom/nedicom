<script setup>
import ReviewLawyer from "@/Components/ReviewLawyer.vue";
import VerifiedIcon from "@/Components/VerifiedIcon.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import { Carousel, Pagination, Slide } from "vue3-carousel";
import "vue3-carousel/dist/carousel.css";

defineProps({
  reviewscount: Number,
  rating: Number,
  reviews: Object,
  lwrid: Number,
  auth: Object,
  mainuslugaid: Number,
  uslugaid: Number,
  errors: Object,
  mainurl: String,
});

const settings = {
  itemsToShow: 1.25,
  snapAlign: "center",
};

const breakpoints = {
  700:  { itemsToShow: 2.5, snapAlign: "center" },
  1024: { itemsToShow: 2.5, snapAlign: "start" },
  1280: { itemsToShow: 3.5, snapAlign: "start" },
};

const verifiedLabels = {
  yandex_id: 'Яндекс ID',
  gosuslugi:  'Госуслуги',
  in_person:  'Лично',
  vk_id:      'VK ID',
};

function stars(rating) {
  return Math.min(5, Math.max(0, Math.round(rating ?? 0)));
}

const active = ref(null);
const confirmDelete = ref(false);
const editMode = ref(false);
const editForm = ref({});

function open(review) {
  active.value = review;
  confirmDelete.value = false;
  editMode.value = false;
}

function close() {
  active.value = null;
  confirmDelete.value = false;
  editMode.value = false;
  document.body.style.overflow = '';
}

function startEdit() {
  editForm.value = {
    fio: active.value.fio,
    description: active.value.description,
    rating: active.value.rating,
    created_at: active.value.rawDate ?? active.value.created_at,
    verified_type: active.value.verified_type ?? '',
  };
  editMode.value = true;
}

function saveReview() {
  router.patch(`/review/${active.value.id}`, editForm.value, {
    onSuccess: () => close(),
  });
}

function deleteReview() {
  router.delete(`/review/${active.value.id}`, {
    onSuccess: () => close(),
  });
}
</script>

<template>
  <div id="reviews" class="py-10 border-b-4 border-indigo-500 overflow-hidden">

    <div class="flex items-baseline gap-3 px-4 mb-5">
      <h2 class="text-xl font-bold text-gray-900">Отзывы заказчиков</h2>
      <span
        v-if="reviewscount > 0"
        itemprop="aggregateRating"
        itemscope
        itemtype="https://schema.org/AggregateRating"
        class="text-xs text-gray-500"
      >
        <span itemprop="bestRating" content="5" class="hidden"></span>
        <span itemprop="worstRating" content="1" class="hidden"></span>
        оценка <span itemprop="ratingValue" class="font-semibold text-gray-700">{{ rating }}</span>
        · <span itemprop="reviewCount">{{ reviewscount }}</span> отз.
      </span>
    </div>

    <Carousel v-bind="settings" :breakpoints="breakpoints">

      <Slide v-for="(card, index) in reviews" :key="index">
        <div class="carousel__item w-full mx-3 h-full">
          <div
            itemprop="review"
            itemscope
            itemtype="https://schema.org/Review"
            class="w-full h-full mx-1 rounded-xl border border-gray-100 bg-white shadow-sm p-4 text-left flex flex-col gap-2 cursor-pointer hover:shadow-md hover:border-gray-200 transition-shadow"
            @click="open(card)"
          >
            <!-- Звёзды + дата -->
            <div class="flex items-center justify-between gap-2">
              <div class="flex items-center gap-0.5">
                <template v-for="i in 5" :key="i">
                  <svg
                    class="w-4 h-4 shrink-0"
                    :class="i <= stars(card.rating) ? 'text-yellow-300' : 'text-gray-300'"
                    fill="currentColor" viewBox="0 0 22 20"
                  >
                    <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                  </svg>
                </template>
              </div>
              <span
                itemprop="datePublished"
                :content="card.created_at"
                class="text-xs text-gray-500 shrink-0"
              >{{ card.created_at }}</span>
            </div>

            <!-- Проверен + иконка -->
            <div v-if="card.verified_type" class="flex items-center gap-1.5 justify-end">
              <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Проверен
              </span>
              <VerifiedIcon :type="card.verified_type" />
              <span class="text-xs text-gray-500">{{ verifiedLabels[card.verified_type] }}</span>
            </div>

            <!-- Текст -->
            <p
              itemprop="reviewBody"
              class="text-sm text-gray-700 line-clamp-4 flex-1"
            >{{ card.description }}</p>

            <!-- Имя -->
            <div class="mt-auto pt-2 border-t border-gray-100">
              <p
                itemprop="author"
                itemscope
                itemtype="http://schema.org/Person"
                class="text-sm font-semibold text-gray-900 truncate"
              >
                <span itemprop="name">{{ card.fio }}</span>
              </p>
            </div>
          </div>
        </div>
      </Slide>

      <!-- Слайд добавления отзыва -->
      <Slide :key="'add-review'">
        <div class="carousel__item w-full h-full mx-3">
          <div class="w-full h-full mx-1 rounded-xl border border-gray-100 bg-white shadow-sm py-2 px-4">
            <ReviewLawyer
              :mainuslugaid="mainuslugaid"
              :uslugaid="uslugaid"
              :lwrid="lwrid"
              :auth="auth"
              :errors="errors"
            />
          </div>
        </div>
      </Slide>

      <Slide v-if="reviews.length === 0" :key="'empty-slide'">
        <div class="carousel__item text-sm text-gray-500 p-4">
          Оставьте Ваш отзыв для оценки работы юриста
        </div>
      </Slide>

      <template #addons>
        <Pagination />
      </template>
    </Carousel>
  </div>

  <!-- Модалка отзыва -->
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
                  <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                </svg>
              </template>
            </div>
            <div class="flex items-center gap-1.5 ml-auto flex-wrap justify-end">
              <template v-if="active.verified_type">
                <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                  <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                  Проверен
                </span>
                <VerifiedIcon :type="active.verified_type" />
                <span class="text-xs text-gray-500">{{ verifiedLabels[active.verified_type] }}</span>
              </template>
              <span class="text-xs text-gray-500">· {{ active.created_at }}</span>
            </div>
            <button @click="close" class="shrink-0 p-1 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <!-- Текст отзыва / форма редактирования -->
          <div class="overflow-y-auto px-5 py-4 flex-1">
            <template v-if="!editMode">
              <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ active.description }}</p>
            </template>
            <template v-else>
              <div class="flex flex-col gap-3">
                <div>
                  <label class="block text-xs font-medium text-gray-500 mb-1">Имя</label>
                  <input v-model="editForm.fio" type="text" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" />
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-500 mb-1">Текст отзыва</label>
                  <textarea v-model="editForm.description" rows="5" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 resize-none"></textarea>
                </div>
                <div class="flex gap-4">
                  <div class="flex-1">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Оценка</label>
                    <select v-model="editForm.rating" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                      <option v-for="n in 5" :key="n" :value="n">{{ n }} ★</option>
                    </select>
                  </div>
                  <div class="flex-1">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Дата</label>
                    <input v-model="editForm.created_at" type="date" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" />
                  </div>
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-500 mb-1">Верификация</label>
                  <select v-model="editForm.verified_type" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <option value="">— нет —</option>
                    <option value="yandex_id">Яндекс ID</option>
                    <option value="gosuslugi">Госуслуги</option>
                    <option value="in_person">Лично</option>
                    <option value="vk_id">VK ID</option>
                  </select>
                </div>
              </div>
            </template>
          </div>

          <!-- Подвал: клиент + кнопки -->
          <div class="px-5 py-4 border-t border-gray-100 bg-gray-50">
            <p class="font-semibold text-sm text-gray-900">{{ active.fio }}</p>

            <template v-if="auth?.isadmin">
              <!-- Режим просмотра -->
              <div v-if="!editMode && !confirmDelete" class="mt-3 flex items-center gap-4">
                <button @click="startEdit" class="text-xs text-blue-500 hover:text-blue-700 hover:underline transition-colors">
                  Редактировать
                </button>
                <button @click="confirmDelete = true" class="text-xs text-red-500 hover:text-red-700 hover:underline transition-colors">
                  Удалить
                </button>
              </div>

              <!-- Подтверждение удаления -->
              <div v-if="confirmDelete" class="mt-3 flex items-center gap-3">
                <span class="text-xs text-gray-600">Удалить этот отзыв?</span>
                <button @click="deleteReview" class="text-xs font-semibold text-white bg-red-500 hover:bg-red-600 px-3 py-1 rounded-lg transition-colors">
                  Да, удалить
                </button>
                <button @click="confirmDelete = false" class="text-xs text-gray-500 hover:text-gray-700 transition-colors">
                  Отмена
                </button>
              </div>

              <!-- Режим редактирования -->
              <div v-if="editMode" class="mt-3 flex items-center gap-3">
                <button @click="saveReview" class="text-xs font-semibold text-white bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded-lg transition-colors">
                  Сохранить
                </button>
                <button @click="editMode = false" class="text-xs text-gray-500 hover:text-gray-700 transition-colors">
                  Отмена
                </button>
              </div>
            </template>
          </div>

        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<style scoped>
.carousel__slide {
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
