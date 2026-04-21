<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { ref, computed } from "vue";

const props = defineProps({
  auth: {
    type: Object,
    default: () => null,
  },
});

const consentRef = ref(null);

// Безопасная проверка авторизации
const isAuthenticated = computed(() => {
  return props.auth && typeof props.auth === "object" && !!props.auth.id;
});

// Безопасная проверка, является ли пользователь юристом
const isLawyer = computed(() => {
  return isAuthenticated.value && props.auth.lawyer === 1;
});

// Безопасная проверка, является ли пользователь клиентом
const isClient = computed(() => {
  return isAuthenticated.value && props.auth.lawyer !== 1;
});

// Форматируем дату регистрации
const userRegistrationDate = computed(() => {
  if (isAuthenticated.value && props.auth.created_at) {
    return props.auth.created_at;
  }
  return null;
});

const printConsent = () => {
  window.print();
};
</script>

<template>
  <Head>
    <title>Согласие на обработку персональных данных</title>
    <meta
      name="description"
      content="Согласие на обработку персональных данных в соответствии с 152-ФЗ"
    />
  </Head>

  <div class="min-h-screen bg-gray-50">
    <MainHeader :auth="auth" />

    <div class="container mx-auto px-4 py-8 max-w-4xl">
      <!-- Кнопки для скачивания/печати -->
      <div class="flex gap-3 mb-6 justify-end print:hidden">
        <button
          @click="printConsent"
          class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm text-sm font-medium"
        >
          <svg
            class="w-4 h-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"
            />
          </svg>
          Сохранить как PDF / Печать
        </button>
      </div>

      <!-- Содержание согласия -->
      <div
        ref="consentRef"
        class="bg-white rounded-xl shadow-lg p-6 md:p-8 print:shadow-none print:p-0"
      >
        <h1
          class="text-2xl md:text-3xl font-bold mb-6 print:text-2xl text-gray-900 border-b pb-4"
        >
          Согласие на обработку персональных данных
        </h1>

        <!-- Информация о пользователе (только для авторизованных) -->
        <div
          v-if="isAuthenticated"
          class="mb-6 p-4 bg-gray-100 rounded-lg print:bg-gray-100 print:border print:border-gray-300"
        >
          <h2 class="text-md font-semibold mb-3 text-gray-700">
            📋 Информация о субъекте персональных данных
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
            <p>
              <strong>Пользователь:</strong> {{ auth.name || "Не указано" }}
            </p>
            <p><strong>Email:</strong> {{ auth.email }}</p>
            <p><strong>Телефон:</strong> {{ auth.phone || "Не указан" }}</p>
            <p><strong>Город:</strong> {{ auth.city || "Не указан" }}</p>
            <p><strong>Дата регистрации:</strong> {{ userRegistrationDate }}</p>
            <p v-if="isLawyer"><strong>Статус:</strong> 👨‍⚖️ Юрист</p>
            <p v-else-if="isClient"><strong>Статус:</strong> 👤 Клиент</p>
          </div>
        </div>

        <p class="mb-6 text-gray-700 print:text-gray-800 leading-relaxed">
          Настоящее согласие дается в соответствии с Федеральным законом от
          27.07.2006 № 152-ФЗ «О персональных данных» свободно, своей волей и в
          своем интересе.
        </p>

        <!-- Оператор -->
        <div
          class="mb-6 p-4 bg-gray-50 rounded-lg print:bg-gray-50 print:border print:border-gray-300"
        >
          <h2 class="text-lg font-semibold mb-2 text-gray-800">
            1. Оператор персональных данных
          </h2>
          <p class="text-gray-700">
            Мина Марк Анатольевич<br />
            295000, Крым Респ., г. Симферополь, ул. Долгоруковская, д. 5<br />
            E-mail:
            <a href="mailto:m6132@yandex.ru" class="text-blue-600 underline"
              >m6132@yandex.ru</a
            >
          </p>
        </div>

        <!-- Перечень ПДн -->
        <div class="mb-6">
          <h2 class="text-lg font-semibold mb-3 text-gray-800">
            2. Перечень персональных данных, на обработку которых дается
            согласие
          </h2>
          <ul class="list-disc pl-6 space-y-1 text-gray-700">
            <li>ФИО или никнейм;</li>
            <li>контактный телефон;</li>
            <li>адрес электронной почты;</li>
            <li>город;</li>
            <li>изображение;</li>
            <li>данные пользователя "о себе";</li>
            <li v-if="isLawyer">
              профессиональные данные (специализация, опыт работы);
            </li>
            <li>
              статистика активности (просмотры, лайки, закладки, конверсия,
              вовлечения);
            </li>
            <li>
              данные о публикациях (статьи, объявления, названия, категории,
              статусы);
            </li>
            <li>идентификаторы Яндекс.Аналитики и Яндекс.Метрики;</li>
            <li>
              номер визита и uuid визита, сведения о utm-метках, дате посещения;
            </li>
            <!-- Добавить в список -->
            <li>
              данные для пенсионного калькулятора (пол, трудовой стаж до 2002,
              стаж до 1991, зарплата до 2002, сумма отчислений с 2002, ИПКн);
            </li>
            <li>
              данные для калькулятора неустойки по ДДУ (цена квартиры, плановая
              дата сдачи, фактическая дата передачи, статус — физлицо/юрлицо);
            </li>
            <li>
              история расчетов и сохраненные результаты калькуляторов (при
              наличии авторизации).
            </li>
            <li>
              иные данные, которые пользователь добровольно указывает при
              использовании сайта
            </li>
          </ul>
        </div>

        <!-- Цели обработки -->
        <div class="mb-6">
          <h2 class="text-lg font-semibold mb-3 text-gray-800">
            3. Цели обработки персональных данных
          </h2>
          <ul class="list-disc pl-6 space-y-1 text-gray-700">
            <li>
              оказание юридических услуг (консультации, запись на прием,
              обратная связь);
            </li>
            <li v-if="isLawyer">
              публикация и продвижение статей, объявлений и материалов;
            </li>
            <li v-if="isLawyer">расчет статистики и конверсии материалов;</li>
            <li>аналитика посещений сайта и защита от спама;</li>
            <li>
              предоставление бесплатных онлайн-расчетов (пенсионный калькулятор,
              калькулятор неустойки по ДДУ);
            </li>
            <li>соблюдение требований законодательства РФ.</li>
          </ul>
        </div>

        <!-- Публичный доступ для юристов -->
        <div
          class="mb-6 p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-400 print:bg-yellow-50"
        >
          <h2 class="text-lg font-semibold mb-3 text-gray-800">
            4. Особенности при регистрации в качестве юриста (перечень
            персональных данных, на обработку которых дается согласие)
          </h2>

          <div class="mb-4">
            <h3 class="font-semibold text-lg text-gray-800 mb-2">
              4.1. Публичный доступ к профилю
            </h3>
            <ul class="list-disc pl-6 text-gray-700">
              <li>ФИО;</li>
              <li>контактным данным (телефон, email, адрес);</li>
              <li>информации о специализации и опыте работы;</li>
              <li>фотографии (аватар);</li>
              <li>рейтингам и статистике активности.</li>
            </ul>
          </div>

          <div class="mb-4">
            <h3 class="font-semibold text-lg text-gray-800 mb-2">
              4.2. Публикация материалов
            </h3>
            <ul class="list-disc pl-6 text-gray-700">
              <li>публикацию статей и объявлений на сайте;</li>
              <li>отображение названий, категорий, дат публикации;</li>
              <li>возможность комментирования и отзывов от пользователей;</li>
              <li>
                отображение статистики материалов (просмотры, вовлечения,
                конверсия);
              </li>
              <li>индексацию материалов поисковыми системами (Яндекс).</li>
            </ul>
          </div>

          <div class="mb-3">
            <h3 class="font-semibold text-lg text-gray-800 mb-2">
              4.3. Обработка аналитических данных
            </h3>
            <ul class="list-disc pl-6 text-gray-700">
              <li>
                сбор и анализ статистики по материалам (статьям и страницам
                услуг на сайте);
              </li>
              <li>расчет конверсии и вовлеченности аудитории;</li>
              <li>хранение истории изменений материалов;</li>
            </ul>
          </div>

          <p class="mt-3 text-sm text-gray-600 bg-white/50 p-2 rounded">
            <strong>Доступ является публичным, </strong> что означает — данные
            видны всем посетителям сайта в профиле юриста, карточках статей и
            объявлений. Материалы индексируются поисковыми системами.
          </p>
        </div>

        <!-- Доступ к данным в кабинете юриста -->
        <div
          class="mb-6 p-4 bg-purple-50 rounded-lg border-l-4 border-purple-400 print:bg-purple-50"
        >
          <h2 class="text-lg font-semibold mb-3 text-gray-800">
            5. Доступ к данным в личном кабинете юриста (перечень персональных
            данных, на обработку которых дается согласие)
          </h2>
          <ul class="list-disc pl-6 mb-3 text-gray-700">
            <li>
              статистика по материалам (просмотры, вовлечения, конверсия,
              клики);
            </li>
            <li>
              список статей и объявлений (названия, даты, категории, статусы);
            </li>
            <li>данные для фильтрации и сортировки материалов;</li>
            <li>история изменений и модерации материалов;</li>
            <li>личные сообщения от клиентов и администрации.</li>
          </ul>
          <p class="text-sm text-gray-600">
            <strong>🔒 Доступ имеют:</strong> пользователь и администрация сайта
            для технической поддержки и модерации.
          </p>
        </div>

        <!-- Доступ к данным клиента -->
        <div
          class="mb-6 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-400 print:bg-blue-50"
        >
          <h2 class="text-lg font-semibold mb-3 text-gray-800">
            6. Особенности при регистрации в качестве клиента при заключении
            договора на оказание услуг (перечень персональных данных, на
            обработку которых дается согласие):
          </h2>
          <ul class="list-disc pl-6 mb-3 text-gray-700">
            <li>контактные данные (ФИО, email);</li>
            <li>сведения о платежах (сумма и дата);</li>
            <li>сведения об оказанных услугах;</li>
          </ul>
          <p class="text-sm text-gray-600">
            <strong
              >🔒 Доступ не является публичным, предоставляется исключительно по
              заявке пользователя и осуществляется для контроля качества
              оказания услуг</strong
            >
          </p>
        </div>

        <!-- Способы обработки -->
        <div class="mb-6">
          <h2 class="text-lg font-semibold mb-3 text-gray-800">
            7. Способы обработки персональных данных
          </h2>
          <p class="text-gray-700 leading-relaxed">
            Обработка осуществляется с использованием средств автоматизации и
            без их использования, включая сбор, запись, систематизацию,
            накопление, хранение, уточнение, извлечение, использование, передачу
            (третьим лицам — только для оказания услуг), обезличивание,
            блокирование, удаление, уничтожение.
          </p>
        </div>

        <!-- Срок обработки -->
        <div
          class="mb-6 p-4 bg-green-50 rounded-lg border-l-4 border-green-500 print:bg-green-50"
        >
          <h2 class="text-lg font-semibold mb-2 text-green-800">
            8. Срок обработки персональных данных
          </h2>
          <p class="mb-2 text-gray-700">
            Настоящее согласие действует в течение следующих сроков в
            зависимости от цели обработки:
          </p>
          <ul class="list-disc pl-6 mb-2 text-gray-700">
            <li>
              <strong>публикация материалов и профиля пользователя</strong>
              — достижением целей, для которых были собраны персональные данные;
            </li>
            <li>
              <strong
                >предоставление бесплатных онлайн-расчетов (пенсионный
                калькулятор, калькулятор ДДУ)</strong
              >
              — данные, введенные пользователем для разового расчета, обрабатываются
              <strong>в течение одной пользовательской сессии</strong> и не хранятся оператором;
            </li>
            <li>
              <strong
                >сбор статистики и аналитика посещений (в т.ч.
                Яндекс.Метрика)</strong
              >
              — достижением целей, для которых были собраны персональные данные, но
              не дольше <strong>1 (одного) года</strong> с даты последнего
              посещения сайта;
            </li>
            <li>
              <strong>оказание услуг</strong> — в течение срока действия
              договора и <strong>1 (одного) года</strong> после полного
              исполнения сторонами обязательств по договору (для возможного
              урегулирования споров и претензионной работы).
            </li>
          </ul>
          <p class="text-sm text-gray-600">
            📌 В соответствии с ч. 4 ст. 21 Федерального закона № 152-ФЗ, после
            достижения указанных целей обработки, а также в случае Вашего отзыва
            согласия (если это не препятствует исполнению договора), Оператор
            обязан прекратить обработку и уничтожить Ваши персональные данные в
            срок, не превышающий <strong>30 рабочих дней</strong> с даты
            достижения целей или получения отзыва.
          </p>
        </div>

        <!-- Право отзыва -->
        <div class="mb-6">
          <h2 class="text-lg font-semibold mb-3 text-gray-800">
            9. Право на отзыв согласия
          </h2>
          <p class="text-gray-700">
            Отозвать согласие можно, направив уведомление на email:
            <strong>m6132@yandex.ru</strong>
          </p>
        </div>

        <!-- Подтверждение ознакомления -->
        <div class="mb-6 p-4 bg-indigo-50 rounded-lg print:bg-indigo-50">
          <h2 class="text-lg font-semibold mb-2 text-gray-800">
            10. Подтверждение
          </h2>
          <p class="text-gray-700">
            Я подтверждаю, что ознакомлен(а) с
            <a href="/policy" class="text-blue-600 underline"
              >Политикой обработки персональных данных</a
            >
            и
            <a href="/cookie" class="text-blue-600 underline"
              >Правилами обработки cookie</a
            >.
          </p>
        </div>

        <!-- Блок с датой согласия -->
        <div
          class="mt-8 pt-4 border-t text-sm text-gray-500 print:text-gray-600"
        >
          <div v-if="isAuthenticated">
            <p>
              <strong>📅 Дата предоставления согласия:</strong>
              {{ userRegistrationDate }}
            </p>
            <p class="mt-1">
              ✅ Способ: проставление отметки (галочки) при регистрации
            </p>
            <p class="mt-1">🟢 Статус: действует с даты регистрации</p>
          </div>
          <div v-else>
            <p>
              ✅ Согласие дается путем проставления отметки (галочки) под формой
              сбора данных
            </p>
            <p class="mt-1">
              📅 Момент предоставления — дата и время отправки формы
            </p>
          </div>
          <p class="mt-3 text-xs text-gray-400">
            Документ «Согласие на обработку персональных данных» доступен для
            сохранения и печати.
          </p>
        </div>
      </div>

      <MainFooter />
    </div>
  </div>
</template>

<style scoped>
@media print {
  .print\:shadow-none {
    box-shadow: none !important;
  }
  .print\:p-0 {
    padding: 0 !important;
  }
  .print\:text-2xl {
    font-size: 1.5rem !important;
  }
  .print\:text-gray-800 {
    color: #1f2937 !important;
  }
  .print\:border {
    border-width: 1px !important;
  }
  .print\:border-gray-300 {
    border-color: #d1d5db !important;
  }
  .print\:bg-gray-100 {
    background-color: #f3f4f6 !important;
  }
  .print\:bg-gray-50 {
    background-color: #f9fafb !important;
  }
  .print\:bg-yellow-50 {
    background-color: #fefce8 !important;
  }
  .print\:bg-purple-50 {
    background-color: #faf5ff !important;
  }
  .print\:bg-green-50 {
    background-color: #f0fdf4 !important;
  }
  .print\:bg-blue-50 {
    background-color: #eff6ff !important;
  }
  .print\:bg-indigo-50 {
    background-color: #eef2ff !important;
  }
  .print\:hidden {
    display: none !important;
  }
}

/* Улучшенная типографика */
.container {
  font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue",
    sans-serif;
}

h1,
h2,
h3 {
  letter-spacing: -0.01em;
}

p,
li {
  line-height: 1.5;
}
</style>