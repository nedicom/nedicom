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

// ── Avatar orbital layout (center=170,170 in 340×340) ──
// [0] — центр. [1] — внутренняя орбита r=90, угол 45°. [2-5] — внешняя r=130, по сторонам.
const avatarLayout = [
  { size: 'w-32 h-32', top: 106, left: 106 }, // главная планета   center=(170,170)
  { size: 'w-20 h-20', top: 66,  left: 194 }, // внутренняя 45°    center=(234,106)
  { size: 'w-14 h-14', top: 142, left: 272 }, // внешняя 0°        center=(300,170)
  { size: 'w-14 h-14', top: 272, left: 142 }, // внешняя 90°       center=(170,300)
  { size: 'w-14 h-14', top: 142, left: 12  }, // внешняя 180°      center=(40,170)
  { size: 'w-14 h-14', top: 12,  left: 142 }, // внешняя 270°      center=(170,40)
];

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
  <section class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8
                py-12 lg:py-0 lg:min-h-[60vh]
                flex flex-col lg:grid lg:grid-cols-2 lg:gap-16 lg:items-center">

      <!-- ── Левая колонка ── -->
      <div class="flex flex-col gap-6 lg:py-16">

        <!-- Бренд -->
        <div>
          <h1 class="text-4xl font-extrabold text-gray-900 lg:text-5xl tracking-tight">Мина и партнёры</h1>
          <p class="mt-2 text-base text-gray-500">Мы делаем правовую защиту доступной</p>
        </div>

        <!-- Планеты и спутники -->
        <div class="relative h-[300px] w-[300px]">

          <!-- Орбиты SVG -->
          <svg class="absolute inset-0 w-full h-full pointer-events-none" viewBox="0 0 300 300" fill="none">
            <!-- Свечение за главной планетой -->
            <circle cx="150" cy="150" r="82" fill="rgba(59,130,246,0.07)"/>
            <circle cx="150" cy="150" r="64" fill="rgba(59,130,246,0.06)"/>
            <!-- Внутренняя орбита r=92 -->
            <circle cx="150" cy="150" r="92"
              stroke="#BFDBFE" stroke-width="1.5" stroke-dasharray="5 6"/>
            <!-- Внешняя орбита r=115 -->
            <circle cx="150" cy="150" r="115"
              stroke="#DBEAFE" stroke-width="1" stroke-dasharray="3 9"/>
            <!-- Декоративные точки на орбитах -->
            <circle cx="215" cy="57"  r="2.5" fill="#93C5FD"/>
            <circle cx="57"  cy="215" r="2.5" fill="#93C5FD"/>
            <circle cx="243" cy="215" r="2"   fill="#BFDBFE"/>
            <circle cx="57"  cy="83"  r="2"   fill="#BFDBFE"/>
            <circle cx="218" cy="240" r="2"   fill="#BFDBFE"/>
          </svg>

          <button
            v-for="(lawyer, idx) in props.lawyers.slice(0, 6)"
            :key="lawyer.id"
            type="button"
            class="absolute group"
            :style="{ top: avatarLayout[idx].top + 'px', left: avatarLayout[idx].left + 'px' }"
            @click="openLawyer(lawyer)"
          >
            <img
              :src="lawyer.avatar_path ? 'https://nedicom.ru/' + lawyer.avatar_path : '/default-avatar.jpg'"
              :alt="lawyer.name"
              :class="[
                avatarLayout[idx].size,
                'rounded-full object-cover ring-2 ring-white shadow-md',
                'transition-all duration-200 group-hover:ring-blue-400 group-hover:scale-110 group-hover:z-10',
              ]"
            />
            <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-900 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-20">
              {{ lawyer.name }}
            </span>
          </button>
        </div>

        <!-- Услуга + город -->

        <h2 class="text-xl font-semibold text-gray-700 leading-snug lg:text-2xl">
          <span v-if="props.usluga_from_url">{{ props.usluga_from_url.usl_name }} — </span>
          <span v-else>Услуги юриста — </span>
          <span v-if="props.city && props.city.id !== 0">{{ props.city.title }}</span>
          <span v-else>для Вас</span>
        </h2>

        <!-- CTA + телефон -->
        <div class="flex flex-col gap-2">
          <p class="text-base font-semibold text-gray-800">
            Первая консультация — <span class="text-blue-600">бесплатно</span>
          </p>
          <p class="text-sm text-gray-500">Юрист ответит в течение 15 минут</p>
          <a
            :href="showFullNumber ? 'tel:+79788838978' : 'javascript:void(0)'"
            class="inline-flex items-center gap-4 self-start mt-1 py-4 px-8 text-white
                   bg-gradient-to-r from-blue-500 to-blue-700 rounded-2xl shadow-xl
                   hover:scale-105 cursor-pointer transition-all duration-300
                   ring-4 ring-blue-300 ring-offset-2 animate-pulse-ring"
            @click="handlePhoneClick"
          >
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
            </svg>
            <div class="flex flex-col items-start">
              <span class="text-xl font-bold whitespace-nowrap">
                {{ showFullNumber ? fullNumber : maskedNumber }}
              </span>
              <span class="text-sm opacity-90 whitespace-nowrap">
                {{ showFullNumber ? "Нажмите для звонка" : "Показать номер" }}
              </span>
            </div>
          </a>
        </div>
      </div>

      <!-- ── Правая колонка: поиск ── -->
      <div class="mt-8 lg:mt-0 lg:py-16">
        <p class="text-sm font-semibold text-gray-500 mb-3 uppercase tracking-wide">Найти юриста по услуге</p>

        <div class="relative">
          <div class="flex">
            <!-- Кнопка города -->
            <button
              type="button"
              @click="open()"
              class="shrink-0 text-white rounded-l-lg bg-blue-700 hover:bg-blue-800
                     font-medium text-sm px-4 py-4 whitespace-nowrap"
            >
              {{ props.city?.title || "Город" }}
            </button>

            <!-- Инпут поиска -->
            <div class="relative flex-1">
              <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 20 20">
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
                class="w-full py-4 pl-10 pr-24 text-sm text-gray-900 border border-gray-300
                       rounded-r-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                :placeholder="searchFocused ? 'Начните вводить название услуги...' : 'Какую услугу ищем?'"
              />
              <a
                v-if="firstResult"
                :href="'/uslugi/' + cityUrl + '/' + firstResult.url"
                class="absolute right-2 top-1/2 -translate-y-1/2 text-white bg-blue-700
                       hover:bg-blue-800 rounded-lg text-sm px-4 py-2 font-medium"
              >Найти</a>
              <span v-else
                class="absolute right-2 top-1/2 -translate-y-1/2 text-white bg-blue-400
                       rounded-lg text-sm px-4 py-2 font-medium cursor-default"
              >Найти</span>
            </div>
          </div>

          <!-- Dropdown с результатами -->
          <div
            :class="{ hidden: hidden }"
            class="absolute z-50 left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg mt-1 overflow-hidden"
          >
            <div class="max-h-64 overflow-y-auto">
              <a
                v-for="(item, num) in searcharr" :key="num"
                :href="'/uslugi/' + cityUrl + '/' + item.url"
                class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700"
              >{{ item.usl_name }}</a>
            </div>
          </div>
        </div>

        <!-- Теги — быстрый выбор -->
        <div class="mt-5 flex flex-wrap gap-2">
          <a
            v-for="offer in startarr" :key="offer.id"
            :href="'/uslugi/' + cityUrl + '/' + offer.url"
            class="text-xs bg-gray-100 hover:bg-blue-50 hover:text-blue-700
                   text-gray-600 rounded-full px-3 py-1.5 transition-colors"
          >{{ offer.usl_name }}</a>
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
