<script setup>
import { ref, computed, watch, reactive } from "vue";
import { ModalsContainer, useModal } from "vue-final-modal";
import CitySet from "@/Components/CitySet.vue";
import LawyerModal from "@/Components/LawyerModal.vue";

const props = defineProps({
  city: Object,
  usluga_from_url: Object,
  lawyers: Object,
  backendurl: String,
  mainoffers: Object,
  secondoffers: Object,
  tracking: {
    type: Object,
    default: () => ({})
  }
});

// ── Phone ──────────────────────────────────────────────
const fullNumber = "8 (978) 8838 978";
const showFullNumber = ref(false);
const eventSent = ref(false);

const maskedNumber = computed(() => {
  const digits = fullNumber.replace(/\D/g, "");
  if (digits.length <= 3) return fullNumber;
  const visible = digits.slice(0, -3);
  let out = "", idx = 0;
  for (const ch of fullNumber) {
    if (/\d/.test(ch)) { out += idx < visible.length ? visible[idx] : "*"; idx++; }
    else out += ch;
  }
  return out;
});

function getCsrfToken() {
  const meta = document.querySelector('meta[name="csrf-token"]');
  return meta instanceof HTMLMetaElement ? meta.content : "";
}

const trackPhoneClick = () => {
  if (!props.tracking?.visit_uuid || !props.backendurl) return;
  fetch("/api/trackphoneclick", {
    method: "POST",
    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": getCsrfToken() },
    body: JSON.stringify({
      visit_uuid: props.tracking.visit_uuid,
      url: props.backendurl,
      phone_click_at: new Date().toISOString(),
    }),
  }).catch(() => {});
};

const handlePhoneClick = (event) => {
  if (!showFullNumber.value) {
    event.preventDefault();
    showFullNumber.value = true;
    if (!eventSent.value && typeof ym !== "undefined") {
      try { ym(24900584, "reachGoal", "SHOW_PHONE_MAIN_PAGE"); eventSent.value = true; } catch {}
    }
    trackPhoneClick();
  }
};

// ── Search ─────────────────────────────────────────────
const alloffers = computed(() => [
  ...(props.mainoffers || []),
  ...(props.secondoffers || []),
]);

const displayedService = computed(() => {
  if (!props.usluga_from_url) return null;
  const { id, main_usluga_id } = props.usluga_from_url;
  return alloffers.value.find(e => e.id === id)
      ?? alloffers.value.find(e => e.id === main_usluga_id)
      ?? props.usluga_from_url;
});

const form = reactive({ question: "" });
const hidden = ref(true);
const searcharr = ref(null);

const shuffle = (arr) => [...arr].sort(() => 0.5 - Math.random());

const startarr = computed(() => {
  const mainId = props.usluga_from_url?.main_usluga_id ?? null;
  const pick = (arr, n) => shuffle(arr).slice(0, n);

  if (!mainId) return pick(alloffers.value, 6);

  const relevant   = alloffers.value.filter(e => e.main_usluga_id === mainId || e.id === mainId);
  const irrelevant = alloffers.value.filter(e => e.main_usluga_id !== mainId && e.id !== mainId);

  const chosen = pick(relevant, 6);

  // если релевантных не хватает — добираем из остальных
  if (chosen.length < 6) {
    const usedIds = new Set(chosen.map(e => e.id));
    chosen.push(...pick(irrelevant.filter(e => !usedIds.has(e.id)), 6 - chosen.length));
  }

  return shuffle(chosen);
});

const shortName = (name) => {
  const idx = name.indexOf(' - ');
  return idx !== -1 ? name.slice(idx + 3) : name;
};

function filterItems(query) {
  searcharr.value = alloffers.value.filter(
    (e) => e.usl_name.toLowerCase().includes(query.toLowerCase())
  );
}

const searchFocused = ref(false);

function openDropdown() {
  searchFocused.value = true;
  const mainId = props.usluga_from_url?.main_usluga_id ?? null;
  const relevant = mainId
    ? alloffers.value.filter(e => e.main_usluga_id === mainId || e.id === mainId)
    : alloffers.value;
  searcharr.value = shuffle(relevant).slice(0, 8);
  hidden.value = false;
}

function closeDropdown() {
  setTimeout(() => {
    searchFocused.value = false;
    hidden.value = true;
  }, 150);
}

watch(
  () => form.question,
  (q) => {
    filterItems(q);
    hidden.value = q.length <= 1;
  }
);

const cityUrl = computed(() => props.city?.url || "");
const firstResult = computed(() => searcharr.value?.[0] ?? null);

const { open, close } = useModal({
  component: CitySet,
  attrs: { modalPageTitle: "front", onConfirm() { close(); } },
});


// ── Lawyer modal ───────────────────────────────────────
const selectedLawyer = ref(null);

const { open: openLawyerModal, close: closeLawyerModal } = useModal({
  component: LawyerModal,
  attrs: {
    get lawyer() { return selectedLawyer.value; },
    get showFullNumber() { return showFullNumber.value; },
    get fullNumber() { return fullNumber; },
    get maskedNumber() { return maskedNumber.value; },
    onPhoneClick(event) { handlePhoneClick(event); },
    onClose() { closeLawyerModal(); },
  },
});

function openLawyer(lawyer) {
  selectedLawyer.value = lawyer;
  openLawyerModal();
}
</script>

<template>
  <section class="relative border-b border-gray-100 overflow-hidden" style="height: 100vh">

    <!-- Фон — зал суда -->
    <img
      src="/court.webp"
      alt=""
      class="absolute inset-0 w-full h-full object-cover object-left"
      aria-hidden="true"
    />
    <!-- Лёгкий оверлей поверх всего чтобы текст читался -->
    <div class="absolute inset-0 bg-white/30"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8
                py-8 lg:py-0 h-full
                flex flex-col lg:grid lg:grid-cols-[1.4fr_1fr] lg:gap-4 lg:items-center">

      <!-- ── Левая колонка ── -->
      <div class="flex flex-col gap-4 lg:py-10 lg:pl-16">

        <!-- Юристы в ряд -->
        <div class="flex flex-col gap-5 my-4">
          <!-- Лозунг -->
          <p class="text-sm font-semibold leading-snug">
            <span class="bg-white px-3 py-1 rounded-lg box-decoration-clone text-gray-900 tracking-tight">
              Готовы встать на Вашу защиту
            </span>
          </p>
          <div class="flex flex-wrap gap-3">
            <button
              v-for="lawyer in props.lawyers?.slice(0, 6)"
              :key="lawyer.id"
              type="button"
              class="relative group flex flex-col items-center gap-1"
              @click="openLawyer(lawyer)"
            >
              <img
                :src="lawyer.avatar_path ? 'https://nedicom.ru/' + lawyer.avatar_path : '/default-avatar.jpg'"
                :alt="lawyer.name"
                class="w-16 h-16 rounded-full object-cover ring-2 ring-white shadow-md
                       transition-all duration-200 group-hover:ring-blue-400 group-hover:scale-110"
              />
              <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 text-xs text-white bg-gray-900 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-20">
                {{ lawyer.name }}
              </span>
            </button>
          </div>
        </div>

        <!-- Услуга + город -->
        <div>
          <h2 class="text-sm font-bold leading-snug">
            <span class="bg-white px-2 py-0.5 rounded box-decoration-clone text-gray-900">
              <span class="text-gray-400 font-semibold">Специализация: </span>
              <span v-if="displayedService">{{ displayedService.usl_name }}</span>
              <span v-else>Услуги юриста</span>
              <span class="text-blue-600 font-semibold"> · <span v-if="props.city && props.city.id !== 0">{{ props.city.title }}</span><span v-else>по всей России</span></span>
            </span>
          </h2>
        </div>

        <!-- Поиск (внизу левой колонки) -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-3 lg:w-[30rem]" style="zoom: 0.8">
          <p class="text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide">Найти юриста по услуге</p>

          <div class="relative">
            <div class="flex">
              <button
                type="button"
                @click="open()"
                class="shrink-0 text-white rounded-l-lg bg-blue-700 hover:bg-blue-800
                       font-medium text-xs px-3 py-2 whitespace-nowrap"
              >
                {{ props.city?.title || "Город" }}
              </button>
              <div class="relative flex-1">
                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                  <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                      stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                  </svg>
                </div>
                <input
                  v-model="form.question"
                  @focus="openDropdown"
                  @blur="closeDropdown"
                  type="search"
                  autocomplete="off"
                  class="w-full py-2 pl-8 pr-20 text-xs text-gray-900 border border-gray-300
                         rounded-r-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                  :placeholder="searchFocused ? 'Начните вводить...' : 'Какую услугу ищем?'"
                />
                <a
                  v-if="firstResult"
                  :href="'/uslugi/' + cityUrl + '/' + firstResult.url"
                  class="absolute right-1.5 top-1/2 -translate-y-1/2 text-white bg-blue-700
                         hover:bg-blue-800 rounded-md text-xs px-3 py-1.5 font-medium"
                >Найти</a>
                <span v-else
                  class="absolute right-1.5 top-1/2 -translate-y-1/2 text-white bg-blue-400
                         rounded-md text-xs px-3 py-1.5 font-medium cursor-default"
                >Найти</span>
              </div>
            </div>
            <div
              :class="{ hidden: hidden }"
              class="absolute z-50 left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg mt-1 overflow-hidden"
            >
              <div class="max-h-48 overflow-y-auto">
                <a
                  v-for="(item, num) in searcharr" :key="num"
                  :href="'/uslugi/' + cityUrl + '/' + item.url"
                  class="block px-3 py-2 text-xs text-gray-700 hover:bg-blue-50 hover:text-blue-700"
                >{{ shortName(item.usl_name) }}</a>
              </div>
            </div>
          </div>

          <div class="mt-2 flex flex-wrap gap-1.5">
            <a
              v-for="offer in startarr" :key="offer.id"
              :href="'/uslugi/' + cityUrl + '/' + offer.url"
              class="text-xs bg-gray-100 hover:bg-blue-50 hover:text-blue-700
                     text-gray-500 rounded-full px-2.5 py-1 transition-colors"
            >{{ shortName(offer.usl_name) }}</a>
          </div>
        </div>
      </div>

      <!-- ── Правая колонка: бренд + телефон ── -->
      <div class="mt-6 lg:mt-0 lg:self-center lg:justify-self-start lg:w-fit lg:-ml-16">
        <div class="flex flex-col justify-between gap-8">
          <!-- Бренд -->
          <h1 class="text-3xl font-extrabold text-gray-900 lg:text-4xl tracking-tight leading-tight">
            <span class="bg-white px-2 py-1 rounded-lg box-decoration-clone">Мина и партнёры</span>
          </h1>

          <a
            :href="showFullNumber ? 'tel:+79788838978' : 'javascript:void(0)'"
            class="inline-flex items-center gap-3 self-start py-3 px-6 text-white
                   bg-gradient-to-r from-blue-500 to-blue-700 rounded-xl shadow-lg
                   hover:scale-105 cursor-pointer transition-all duration-300
                   ring-4 ring-blue-300 ring-offset-2 animate-pulse-ring"
            @click="handlePhoneClick"
          >
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
            </svg>
            <div class="flex flex-col items-start">
              <span class="text-lg font-bold whitespace-nowrap">
                {{ showFullNumber ? fullNumber : maskedNumber }}
              </span>
              <span class="text-xs opacity-90 whitespace-nowrap">
                {{ showFullNumber ? "Нажмите для звонка" : "Показать номер" }}
              </span>
            </div>
          </a>
          <p class="text-sm font-semibold leading-relaxed">
            <span class="bg-white px-2 py-0.5 rounded box-decoration-clone text-gray-800">
              Первая консультация — <span class="text-blue-600">бесплатно</span>
            </span>
          </p>
        </div>
      </div>

    </div>
  </section>

  <ModalsContainer />
</template>

<style scoped>
@keyframes pulse-ring {
  0%, 100% { box-shadow: 0 0 0 0 rgba(96, 165, 250, 0.6); }
  50%       { box-shadow: 0 0 0 12px rgba(96, 165, 250, 0); }
}
.animate-pulse-ring {
  animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

</style>
