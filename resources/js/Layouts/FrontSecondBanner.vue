<script setup>
import { ref, computed } from "vue";

const props = defineProps({
  city: Object,
  usluga_from_url: Object,
  lawyers: Object,
  backendurl: String,
  tracking: {
    type: Object,
    default: () => ({})
  }
});

const fullNumber = "8 (978) 8838 978";
const showFullNumber = ref(false);
const eventSent = ref(false);

// Вычисляем замаскированный номер (последние 3 цифры заменены на *)
const maskedNumber = computed(() => {
  // Удаляем все нецифровые символы
  const digits = fullNumber.replace(/\D/g, '');
  if (digits.length <= 3) return fullNumber;
  
  const visibleDigits = digits.slice(0, -3); // Все цифры кроме последних трех
  const maskedDigits = '***'; // Последние 3 цифры заменяем на *
  
  // Форматируем обратно в читаемый вид
  let formatted = '';
  let digitIndex = 0;
  
  for (let i = 0; i < fullNumber.length; i++) {
    if (/\d/.test(fullNumber[i])) {
      if (digitIndex < visibleDigits.length) {
        formatted += visibleDigits[digitIndex];
      } else {
        formatted += '*';
      }
      digitIndex++;
    } else {
      formatted += fullNumber[i];
    }
  }
  
  return formatted;
});

// Функция для отправки данных о клике на телефон
const trackPhoneClick = () => {
  if (!props.tracking?.visit_uuid || !props.backendurl) {
    console.log('❌ Нет данных для трекинга телефона');
    return;
  }

  console.log('📞 Отправка клика на телефон:', {
    visitUuid: props.tracking.visit_uuid,
    url: props.backendurl
  });

  function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    if (meta instanceof HTMLMetaElement) {
      return meta.content;
    }
    return '';
  }

  // Отправляем на сервер
  fetch('/api/trackphoneclick', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': getCsrfToken()
    },
    body: JSON.stringify({
      visit_uuid: props.tracking.visit_uuid,
      url: props.backendurl,
      phone_click_at: new Date().toISOString()
    })
  })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        console.log('✅ Клик на телефон сохранён:', data.phone_click_at);
      } else {
        console.warn('⚠️ Ошибка сохранения:', data.error);
      }
    })
    .catch(err => console.error('❌ Ошибка сети:', err));
};

// Функция для отправки метрик
const sendYandexMetric = () => {
  if (!eventSent.value && typeof ym !== "undefined") {
    try {
      ym(24900584, "reachGoal", "SHOW_PHONE_MAIN_PAGE");
      eventSent.value = true;
      console.log("Yandex Metric event sent: SHOW_PHONE_MAIN_PAGE");
    } catch (error) {
      console.log("Yandex Metric error:", error);
    }
  }
};

// Функция для отправки Google Analytics
const sendGoogleAnalytics = () => {
  if (typeof gtag !== "undefined") {
    try {
      gtag('event', 'click_phone', {
        'event_category': 'engagement',
        'event_label': 'main_page'
      });
      console.log("Google Analytics event sent: click_phone");
    } catch (error) {
      console.log("Google Analytics error:", error);
    }
  }
};

// Обработчик клика для раскрытия телефона
const handlePhoneClick = (event) => {
  // Если номер еще не раскрыт, показываем его и отправляем события
  if (!showFullNumber.value) {
    event.preventDefault(); // Предотвращаем переход по ссылке при первом клике
    
    showFullNumber.value = true;
    
    // Отправляем все метрики
    sendYandexMetric();
    sendGoogleAnalytics();
    trackPhoneClick();
  }
  // Если номер уже раскрыт, позволяем ссылке работать нормально
};
</script>

<template>
  <section class="">
    <div class="mx-auto h-[40vh] min-h-[300px] 
            sm:h-[45vh] sm:min-h-[400px]
            lg:h-[50vh] lg:min-h-[500px]
            xl:h-[55vh] xl:min-h-[600px]
             max-w-screen-xl text-center flex flex-col justify-evenly">
      <h1 class="text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl">
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
        <img v-for="lawyer in props.lawyers" :key="lawyer.id"
          class="inline-block h-10 w-10 lg:h-16 lg:w-16 rounded-full ring-2 ring-white object-cover transition-transform duration-300 ease-in-out hover:scale-125 hover:z-10 hover:shadow-lg"
          :src="lawyer.avatar_path
            ? 'https://nedicom.ru/' + lawyer.avatar_path
            : '/default-avatar.jpg'
            " 
          :alt="'Юрист ' + (lawyer.name || '')" 
          width="40" 
          height="40" />
      </div>

      <div class="p-5 flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">
        <a :href="showFullNumber ? 'tel:+79788838978' : 'javascript:void(0)'"
          class="inline-flex flex-row items-center justify-center py-6 px-10 text-center bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-2xl shadow-2xl hover:shadow-3xl hover:scale-105 cursor-pointer transition-all duration-300 border-0"
          @click="handlePhoneClick">

          <!-- Контент в колонку -->
          <div class="flex flex-col items-center">
            <span class="text-3xl lg:text-4xl font-bold tracking-wide whitespace-nowrap mb-2">{{
              showFullNumber ? fullNumber : maskedNumber
            }}</span>

            <span class="flex items-center justify-center"> <!-- Иконка телефона -->
              <svg class="w-8 h-8 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
              <span v-if="!showFullNumber" class="text-xl font-semibold opacity-90 whitespace-nowrap">Показать
                номер телефона</span>
              <span v-else class="text-xl font-semibold opacity-90 whitespace-nowrap">Нажмите для звонка</span>
            </span>
          </div>
        </a>
      </div>

      <h2 class="text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-5xl">
        Запишитесь на консультацию
      </h2>
    </div>
  </section>
</template>