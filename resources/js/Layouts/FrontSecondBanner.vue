<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  city: Object,
  usluga_from_url: Object,
  lawyers: Object,
});

const fullNumber = '8 (978) 8838 978';
const showFullNumber = ref(false);
const eventSent = ref(false);

// Вычисляем замаскированный номер (последние 3 цифры заменены на *)
const maskedNumber = computed(() => {
  const visiblePart = fullNumber.slice(0, -3); // Все цифры кроме последних трех
  const maskedPart = fullNumber.slice(-3).replace(/./g, '*'); // Последние 3 цифры заменяем на *
  return visiblePart + maskedPart;
});

// Универсальный обработчик для наведения и клика
const handlePhoneInteraction = () => {
  showFullNumber.value = true;

  // Отправляем событие только один раз
  if (!eventSent.value && typeof ym !== 'undefined') {
    try {
      ym(24900584, 'reachGoal', 'SHOW_PHONE_MAIN_PAGE');
      eventSent.value = true;
      console.log('Yandex Metric event sent: SHOW_PHONE_MAIN_PAGE');
    } catch (error) {
      console.log('Yandex Metric error:', error);
    }
  }
};
</script>

<template>
  <section class="">
    <div class="mx-auto h-[50vh] max-w-screen-xl text-center flex flex-col justify-evenly">

      <h1 class="text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl">
        <span v-if="props.usluga_from_url">
          {{ props.usluga_from_url.usl_name }} -
        </span>
        <span v-else>
          Услуги юриста -
        </span>

        <span v-if="props.city && props.city.id !== 0">
          {{ props.city.title }}
        </span>
        <span v-else>
          для Вас
        </span>
      </h1>


      <div class="flex  overflow-hidden p-6 items-center justify-center">
        <img v-for="lawyer in props.lawyers" :key="lawyer.id" 
        class="inline-block h-10 w-10 lg:h-16 lg:w-16 rounded-full ring-2 ring-white object-cover transition-transform duration-300 ease-in-out hover:scale-125 hover:z-10 hover:shadow-lg"
        :src="lawyer.avatar_path ? 'https://nedicom.ru/' + lawyer.avatar_path : '/default-avatar.jpg'"
        :alt="'Юрист ' + (lawyer.name || '')"
        width="40"
        height="40"
        />
      </div>

      <div class="px-5 flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">
        <a href="tel:+79788838978"
          class="inline-flex justify-center items-center py-5 px-5 text-4xl font-bold text-center text-gray-900 rounded-lg border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-100"
          @mouseover="handlePhoneInteraction" @click="handlePhoneInteraction">
          {{ showFullNumber ? fullNumber : maskedNumber }}
        </a>
      </div>

      <h2 class="text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-5xl">
        Запишитесь на консультацию
      </h2>
    </div>
  </section>
</template>