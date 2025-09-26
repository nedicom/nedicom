<script setup>
import { ref, computed } from "vue";

const props = defineProps({
  city: Object,
  usluga_from_url: Object,
  lawyers: Object,
});

const fullNumber = "8 (978) 8838 978";
const showFullNumber = ref(false);
const eventSent = ref(false);

// Вычисляем замаскированный номер (последние 3 цифры заменены на *)
const maskedNumber = computed(() => {
  const visiblePart = fullNumber.slice(0, -3); // Все цифры кроме последних трех
  const maskedPart = fullNumber.slice(-3).replace(/./g, "*"); // Последние 3 цифры заменяем на *
  return visiblePart + maskedPart;
});

// Обработчик клика для раскрытия телефона
const handlePhoneClick = (event) => {
  // Если номер еще не раскрыт, показываем его и отправляем событие
  if (!showFullNumber.value) {
    showFullNumber.value = true;

    // Отправляем событие только один раз
    if (!eventSent.value && typeof ym !== "undefined") {
      try {
        ym(24900584, "reachGoal", "SHOW_PHONE_MAIN_PAGE");
        eventSent.value = true;
        console.log("Yandex Metric event sent: SHOW_PHONE_MAIN_PAGE");
      } catch (error) {
        console.log("Yandex Metric error:", error);
      }
    }

    // Предотвращаем переход по ссылке, если нужно сначала показать номер
    // event.preventDefault();
  }
  // Если номер уже раскрыт, позволим переходу по tel: ссылке произойти нормально
};
</script>

<template>
  <section class="">
    <div
      class="mx-auto h-[40vh] min-h-[300px] 
            sm:h-[45vh] sm:min-h-[400px]
            lg:h-[50vh] lg:min-h-[500px]
            xl:h-[55vh] xl:min-h-[600px]
             max-w-screen-xl text-center flex flex-col justify-evenly"
    >
      <h1
        class="text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl"
      >
        <span v-if="props.usluga_from_url">
          {{ props.usluga_from_url.usl_name }} -
        </span>
        <span v-else> Услуги юриста - </span>

        <span v-if="props.city && props.city.id !== 0">
          {{ props.city.title }}
        </span>
        <span v-else> для Вас </span>
      </h1>

      <div class="flex overflow-hidden p-6 items-center justify-center">
        <img
          v-for="lawyer in props.lawyers"
          :key="lawyer.id"
          class="inline-block h-10 w-10 lg:h-16 lg:w-16 rounded-full ring-2 ring-white object-cover transition-transform duration-300 ease-in-out hover:scale-125 hover:z-10 hover:shadow-lg"
          :src="
            lawyer.avatar_path
              ? 'https://nedicom.ru/' + lawyer.avatar_path
              : '/default-avatar.jpg'
          "
          :alt="'Юрист ' + (lawyer.name || '')"
          width="40"
          height="40"
        />
      </div>

      <div
        class="px-5 flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4"
      >
        <a
          href="tel:+79788838978"
          class="inline-flex flex-row items-center justify-center py-6 px-10 text-center bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-2xl shadow-2xl hover:shadow-3xl hover:scale-105 cursor-pointer transition-all duration-300 border-0"
          @click="handlePhoneClick"
        >
          <!-- Иконка телефона -->
          <svg
            class="w-8 h-8 mr-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
            />
          </svg>

          <!-- Контент в колонку -->
          <div class="flex flex-col items-center">
            <span class="text-4xl font-bold tracking-wide">{{
              showFullNumber ? fullNumber : maskedNumber
            }}</span>
            <span
              v-if="!showFullNumber"
              class="mt-2 text-xl font-semibold opacity-90"
              >Показать номер телефона</span
            >
            <span v-else class="mt-2 text-xl font-semibold opacity-90"
              >Нажмите для звонка</span
            >
          </div>
        </a>
      </div>

      <h2
        class="text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-5xl"
      >
        Запишитесь на консультацию
      </h2>
    </div>
  </section>
</template>