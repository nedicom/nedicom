<script setup>
import { ref, onMounted } from "vue";

const props = defineProps({
  auth: {
    type: Object,
    default: null,
  },
});

const showBanner = ref(false);

onMounted(() => {
  const savedConsent = localStorage.getItem("yandex_metrica_consent");

  if (savedConsent === "accepted") {
    showBanner.value = false;
    console.log("🍪 [Cookie Consent] Согласие ПОЛУЧЕНО");
  } else if (savedConsent === "declined") {
    showBanner.value = false;
    console.log("🍪 [Cookie Consent] Согласие НЕ ПОЛУЧЕНО");
  } else {
    showBanner.value = !props.auth;
    console.log("🍪 [Cookie Consent] Ожидание решения пользователя");
  }
});

const acceptConsent = () => {
  localStorage.setItem("yandex_metrica_consent", "accepted");
  showBanner.value = false;
  console.log("🍪 [Cookie Consent] ✅ Пользователь СОГЛАСИЛСЯ");
  console.log("📊 [Yandex.Metrica] Метрика работает");
};

const declineConsent = () => {
  localStorage.setItem("yandex_metrica_consent", "declined");
  showBanner.value = false;
  console.log("🍪 [Cookie Consent] ❌ Пользователь ОТКАЗАЛСЯ. Метрика выключена.");
};
</script>

<template>
  <div
    v-if="showBanner"
    class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg z-50 p-4 md:p-5"
    role="alert"
  >
    <div class="container mx-auto max-w-7xl">
      <div
        class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4"
      >
        <!-- Иконка и текст -->
        <div class="flex items-start gap-3">
          <div
            class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-blue-500 bg-blue-100 rounded-lg"
          >
            <svg
              class="w-4 h-4"
              aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 18 20"
            >
              <path
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15.147 15.085a7.159 7.159 0 0 1-6.189 3.307A6.713 6.713 0 0 1 3.1 15.444c-2.679-4.513.287-8.737.888-9.548A4.373 4.373 0 0 0 5 1.608c1.287.953 6.445 3.218 5.537 10.5 1.5-1.122 2.706-3.01 2.853-6.14 1.433 1.049 3.993 5.395 1.757 9.117Z"
              />
            </svg>
          </div>
          <div class="text-sm text-gray-700 max-w-2xl">
            <p class="font-semibold mb-1">📊 Мы используем Яндекс.Метрику</p>
            <p>
              Для анализа посещаемости и улучшения работы сайта мы используем
              <strong>Яндекс.Метрику</strong>, которая собирает технические
              данные: IP-адрес, тип устройства, просмотренные страницы, источник
              перехода, время на сайте, локацию пользователя. А так же сведения
              о посещенных страницах сайта в целях аналитики и защиты от спама.
              <strong class="text-gray-800"
                >Это является обработкой персональных данных.</strong
              >
            </p>
            <p class="mt-1 text-xs text-gray-500">
              Подробнее в
              <a
                href="/personal"
                target="_blank"
                class="text-blue-500 underline hover:text-blue-700"
              >
                Согласии на обработку персональных данных
              </a>
              и
              <a
                href="/cookie"
                target="_blank"
                class="text-blue-500 underline hover:text-blue-700"
              >
                Правилах обработки cookie
              </a>
            </p>
          </div>
        </div>

        <!-- Кнопки -->
        <div class="flex gap-2 shrink-0">
          <button
            @click="acceptConsent"
            class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors whitespace-nowrap"
          >
            ✅ Согласен
          </button>
          <button
            @click="declineConsent"
            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors whitespace-nowrap"
          >
            ❌ Не согласен
          </button>
        </div>
      </div>
    </div>
  </div>
</template>