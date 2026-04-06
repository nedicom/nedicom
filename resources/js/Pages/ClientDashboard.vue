<script setup>
import { computed, ref } from "vue";
import { router } from "@inertiajs/vue3";
import { Link } from "@inertiajs/inertia-vue3";
import {
  ExclamationTriangleIcon,
  ArrowPathIcon,
  ChevronDownIcon,
  ChevronUpIcon,
} from "@heroicons/vue/24/outline";
import { Head } from "@inertiajs/inertia-vue3";
import MainHeader from "@/Layouts/MainHeader.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import Tracking from "@/Components/Tracking.vue";

const props = defineProps({
  clientData: Object,
  error: String,
  httpStatus: Number,
  userEmail: String,
  auth: Object,
  backendurl: String,
});

// Состояние для отображения всех задач и платежей
const showAllTasks = ref(false);
const showAllPayments = ref(false);

const reloadPage = () => {
  router.reload();
};

const toggleAllTasks = () => {
  showAllTasks.value = !showAllTasks.value;
};

const toggleAllPayments = () => {
  showAllPayments.value = !showAllPayments.value;
};

// Вычисляемые свойства для отображаемых задач
const displayedTasks = computed(() => {
  if (!props.clientData?.tasks_for_client) return [];
  if (showAllTasks.value) {
    return props.clientData.tasks_for_client;
  }
  return props.clientData.tasks_for_client.slice(0, 5);
});

// Вычисляемые свойства для отображаемых платежей
const displayedPayments = computed(() => {
  if (!props.clientData?.payments_for_client) return [];
  if (showAllPayments.value) {
    return props.clientData.payments_for_client;
  }
  return props.clientData.payments_for_client.slice(0, 5);
});

// Вспомогательные функции
const formatCurrency = (amount) => {
  if (!amount) return "0 ₽";
  return new Intl.NumberFormat("ru-RU", {
    style: "currency",
    currency: "RUB",
    minimumFractionDigits: 0,
  }).format(amount);
};

const formatDate = (dateString) => {
  if (!dateString) return "";
  const date = new Date(dateString);
  return date.toLocaleDateString("ru-RU", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
  });
};

const formatDateTime = (dateTimeString) => {
  if (!dateTimeString) return "";
  const date = new Date(dateTimeString);
  return date.toLocaleDateString("ru-RU", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
};

const getStatusBadgeClass = (status) => {
  const classes = {
    выполнена: "bg-green-100 text-green-800",
    просрочена: "bg-red-100 text-red-800",
    "в работе": "bg-yellow-100 text-yellow-800",
    новая: "bg-blue-100 text-blue-800",
  };
  return classes[status] || "bg-gray-100 text-gray-800";
};

// Вычисляемые значения
const calculateTotalPayments = () => {
  if (!props.clientData?.payments_for_client) return 0;
  return props.clientData.payments_for_client.reduce(
    (sum, payment) => sum + payment.summ,
    0
  );
};

const calculateCompletedTasks = () => {
  if (!props.clientData?.tasks_for_client) return 0;
  return props.clientData.tasks_for_client.filter(
    (task) => task.status === "выполнена"
  ).length;
};

// Schema.org данные
const schemaData = computed(() => ({
  "@context": "https://schema.org",
  "@type": "WebApplication",
  name: "Личный кабинет клиента nedicom.ru",
  description: "Личный кабинет для клиентов юридической компании nedicom.ru",
  applicationCategory: "BusinessApplication",
  operatingSystem: "Any",
  browserRequirements: "Requires JavaScript",
  url: "https://nedicom.ru/clientdashboard",
  offers: {
    "@type": "Offer",
    category: "LegalServices",
    areaServed: "RU",
    availableChannel: {
      "@type": "ServiceChannel",
      serviceUrl: "https://nedicom.ru",
    },
  },
}));
</script>

<template>
  <Head>
    <title>Личный кабинет клиента nedicom.ru</title>
    <meta name="description" content="Личный кабинет клиента nedicom.ru" />
    <meta property="og:title" content="Личный кабинет клиента nedicom.ru" />
    <meta
      property="og:description"
      content="Личный кабинет клиента nedicom.ru"
    />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://nedicom.ru/clientdashboard" />
    <meta property="og:site_name" content="nedicom.ru" />
    <meta property="og:locale" content="ru_RU" />
    <link rel="canonical" href="https://nedicom.ru/clientdashboard" />
  </Head>

  <!-- Schema.org inline разметка -->
  <div
    itemprop="mainEntity"
    itemscope
    itemtype="https://schema.org/WebApplication"
    style="display: none"
  >
    <meta itemprop="name" content="Личный кабинет клиента nedicom.ru" />
    <meta
      itemprop="description"
      content="Личный кабинет для клиентов юридической компании nedicom.ru"
    />
    <meta itemprop="applicationCategory" content="BusinessApplication" />
    <meta itemprop="operatingSystem" content="Any" />
    <meta itemprop="browserRequirements" content="Requires JavaScript" />
    <link itemprop="url" href="https://nedicom.ru/clientdashboard" />
    <div itemprop="offers" itemscope itemtype="https://schema.org/Offer">
      <meta itemprop="category" content="LegalServices" />
      <meta itemprop="areaServed" content="RU" />
      <div
        itemprop="availableChannel"
        itemscope
        itemtype="https://schema.org/ServiceChannel"
      >
        <link itemprop="serviceUrl" href="https://nedicom.ru" />
      </div>
    </div>

    <!-- Информация о задачах -->
    <div v-if="clientData?.tasks_for_client" itemprop="featureList">
      <div
        v-for="task in clientData.tasks_for_client.slice(0, 3)"
        :key="task.id"
        itemprop="itemListElement"
        itemscope
        itemtype="https://schema.org/Thing"
      >
        <meta itemprop="name" :content="`Задача: ${task.name}`" />
        <meta itemprop="description" :content="`Статус: ${task.status}`" />
      </div>
    </div>

    <!-- Информация о платежах -->
    <div
      v-if="clientData?.payments_for_client"
      itemprop="paymentAccepted"
      content="Банковский перевод"
    />

    <!-- Контактная информация -->
    <div
      itemprop="provider"
      itemscope
      itemtype="https://schema.org/LegalService"
    >
      <meta itemprop="name" content="nedicom.ru" />
      <meta itemprop="description" content="Юридические услуги" />
      <div
        itemprop="areaServed"
        itemscope
        itemtype="https://schema.org/Country"
      >
        <meta itemprop="name" content="Россия" />
      </div>
    </div>
  </div>

  <div class="min-h-screen">
    <MainHeader :auth="auth" />

    <div class="py-6">
      <!-- Блок ошибок -->
      <div v-if="error" class="mb-6 mx-10">
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <ExclamationTriangleIcon class="h-5 w-5 text-red-400" />
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-red-800">
                Ошибка загрузки данных
              </h3>
              <div class="mt-2 text-sm text-red-700">
                <p>{{ error }}</p>

                <!-- Специальное сообщение для ошибки токена -->
                <div
                  v-if="httpStatus === 401"
                  class="mt-2 p-2 bg-red-100 rounded"
                >
                  <p class="font-semibold">Проблема с подключением к CRM:</p>
                  <ul class="list-disc ml-4 mt-1">
                    <li>Проверьте настройки API токена</li>
                    <li>Убедитесь что CRM система доступна</li>
                    <li>Обратитесь к администратору</li>
                  </ul>
                </div>

                <!-- Кнопка повторной попытки -->
                <button
                  @click="reloadPage"
                  class="mt-3 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                >
                  <ArrowPathIcon class="mr-2 h-4 w-4" />
                  Попробовать снова
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Основной контент -->
      <div
        v-if="!error && clientData"
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
      >
        <!-- Заголовок -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900">
            Личный кабинет клиента
          </h1>
          <p v-if="auth" class="mt-2 text-gray-600">
            Здесь вы можете отслеживать свои задачи и платежи 
          </p>
          <p v-else class="mt-2 text-gray-600">
            Сейчас Вы видите образец кабинета клиента. Чтобы увидеть личные платежи и задачи войдите на сайт и
            запросите доступ у Вашего текущего юриста.
          </p>

          <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <details class="group" :open="!props.auth">
              <summary
                class="flex items-center justify-between cursor-pointer text-blue-900 font-medium"
              >
                <span>📋 Правила успешной работы с нами (развернуть/свернуть)</span>
                <svg
                  class="w-5 h-5 text-blue-600 group-open:rotate-180 transition-transform"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                  ></path>
                </svg>
              </summary>
              <div
                class="mt-3 pt-3 border-t border-blue-200 space-y-2 text-sm text-blue-800"
              >
                <p>
                  <strong>📱 Связь:</strong> Обычно мы создаем чат
                  в Макс, где управляющий партнер, начальник юр. отдела, юрист и консультант. Людей может быть
                  больше, мы используем коллективную работу. Если Вы против создания чата, сообщите об этом.
                </p>
                <p>
                  <strong>⏰ Время:</strong> Пн-Пт 9:00-18:00. Старайтесь не
                  беспокоить юристов в нерабочее время. Если по другому никак -
                  можно оставить сообщение в чате, учтите что прочитано оно
                  может быть в рабочее время.
                </p>
                <p>
                  <strong>🎤 Сообщения:</strong> Голосовые приветствуются, текст
                  тоже. Старайтесь излагать пожелания или вопросы максимально
                  четко.
                </p>
                <p>
                  <strong>📄 Документы:</strong> Присылайте качественные сканы,
                  размытые 2 тома читать невозможно. Пользуйтесь приложениями
                  для pdf. Мы расскажем как, если потребуется.
                </p>
                <p>
                  <strong>⏳ Сроки:</strong> Ожидайте реальные дедлайны. Если
                  нужно сделать все в один день - мы это обсудим при заключении
                  соглашения.
                </p>
                <p>
                  <strong>🔒 Конфиденциальность:</strong> Вся информация
                  защищена, старайтесь сами не распространять лишнее. 
                  Помните, что при регистрации Вы даете согласие на обработку персональных данных.
                </p>
                <p>
                  <strong>📝 Соглашение:</strong> Мы работаем только по
                  договору.
                </p>
                <p>
                  <strong>💰 Оплата:</strong> Сохраняйте наши платежные
                  квитанции. Для компенсации расходов на будущее
                </p>
              </div>
            </details>
          </div>
        </div>

        <div
          v-if="!auth"
          class="flex flex-col sm:flex-row gap-4 justify-center mb-8"
        >
          <Link
            :href="route('login')"
            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Войти в кабинет
          </Link>
          <Link
            :href="route('Welcome')"
            class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Стать клиентом
          </Link>
        </div>

        <!-- Информация о клиенте -->
        <div class="bg-white shadow rounded-lg p-6 mb-8">
          <h2 class="text-xl font-semibold text-gray-900 mb-4">Ваши данные</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
              <p class="text-sm font-medium text-gray-500">Клиент</p>
              <p class="mt-1 text-lg font-semibold text-gray-900">Скрыто</p>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-500">Email</p>
              <p class="mt-1 text-lg text-gray-900">Скрыто</p>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-500">ID в системе</p>
              <p class="mt-1 text-lg font-mono text-gray-900">
                {{ clientData.id }}
              </p>
            </div>
          </div>
        </div>

        <!-- Статистика -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
          <!-- Платежи -->
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-blue-700">Платежи</p>
                <p class="mt-2 text-3xl font-bold text-blue-900">
                  {{ clientData.payments_count }}
                </p>
                <p class="mt-1 text-sm text-blue-600">
                  Общая сумма: {{ formatCurrency(calculateTotalPayments()) }}
                </p>
              </div>
              <div class="text-blue-500">
                <svg
                  class="w-12 h-12"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                  ></path>
                </svg>
              </div>
            </div>
          </div>

          <!-- Задачи -->
          <div class="bg-green-50 border border-green-200 rounded-lg p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-green-700">Задачи</p>
                <p class="mt-2 text-3xl font-bold text-green-900">
                  {{ clientData.tasks_count }}
                </p>
                <p class="mt-1 text-sm text-green-600">
                  Выполнено: {{ calculateCompletedTasks() }}
                </p>
              </div>
              <div class="text-green-500">
                <svg
                  class="w-12 h-12"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                  ></path>
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Платежи -->
        <div class="bg-white shadow rounded-lg p-6 mb-8">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Платежи</h2>
            <div class="flex items-center gap-4">
              <span class="text-sm text-gray-500"
                >Всего: {{ clientData.payments_count }}</span
              >
              <button
                @click="toggleAllPayments"
                v-if="
                  clientData.payments_for_client &&
                  clientData.payments_for_client.length > 5
                "
                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-md transition-colors"
              >
                <span v-if="showAllPayments">Свернуть</span>
                <span v-else>Все платежи</span>
                <ChevronDownIcon v-if="!showAllPayments" class="ml-1 w-4 h-4" />
                <ChevronUpIcon v-else class="ml-1 w-4 h-4" />
              </button>
            </div>
          </div>

          <div
            v-if="
              clientData.payments_for_client &&
              clientData.payments_for_client.length > 0
            "
            class="space-y-4"
          >
            <div
              v-for="payment in displayedPayments"
              :key="payment.id"
              class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors"
            >
              <div class="flex justify-between items-start">
                <div>
                  <p class="font-medium text-gray-900">
                    Платеж #{{ payment.id }}
                  </p>
                  <p class="text-sm text-gray-500 mt-1">
                    {{ formatDate(payment.created_at) }}
                  </p>
                </div>
                <div class="text-right">
                  <p class="text-lg font-semibold text-blue-600">
                    {{ formatCurrency(payment.summ) }}
                  </p>
                  <p class="text-xs text-gray-500 mt-1">
                    ID клиента: {{ payment.clientid }}
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="text-center py-8 text-gray-500">
            <p>Платежей пока нет</p>
          </div>

          <!-- Кнопка внизу для мобильных -->
          <div
            v-if="
              clientData.payments_for_client &&
              clientData.payments_for_client.length > 5
            "
            class="mt-6 pt-6 border-t border-gray-200 md:hidden"
          >
            <button
              @click="toggleAllPayments"
              class="w-full flex items-center justify-center px-4 py-2.5 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-md transition-colors"
            >
              <span v-if="showAllPayments">Свернуть платежи</span>
              <span v-else
                >Показать все платежи ({{ clientData.payments_count }})</span
              >
              <ChevronDownIcon v-if="!showAllPayments" class="ml-2 w-4 h-4" />
              <ChevronUpIcon v-else class="ml-2 w-4 h-4" />
            </button>
          </div>
        </div>

        <!-- Задачи -->
        <div class="bg-white shadow rounded-lg p-6 mb-8">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Задачи</h2>
            <div class="flex items-center gap-4">
              <span class="text-sm text-gray-500"
                >Всего: {{ clientData.tasks_count }}</span
              >
              <button
                @click="toggleAllTasks"
                v-if="
                  clientData.tasks_for_client &&
                  clientData.tasks_for_client.length > 5
                "
                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-md transition-colors"
              >
                <span v-if="showAllTasks">Свернуть</span>
                <span v-else>Все задачи</span>
                <ChevronDownIcon v-if="!showAllTasks" class="ml-1 w-4 h-4" />
                <ChevronUpIcon v-else class="ml-1 w-4 h-4" />
              </button>
            </div>
          </div>

          <div
            v-if="
              clientData.tasks_for_client &&
              clientData.tasks_for_client.length > 0
            "
            class="space-y-4"
          >
            <div
              v-for="task in displayedTasks"
              :key="task.id"
              class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors"
            >
              <div
                class="flex flex-col sm:flex-row sm:justify-between sm:items-start"
              >
                <div class="flex-1">
                  <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                    <h3 class="font-medium text-gray-900">{{ task.name }}</h3>
                    <span
                      :class="getStatusBadgeClass(task.status)"
                      class="px-2 py-1 text-xs font-medium rounded-full w-fit self-start"
                    >
                      {{ task.status }}
                    </span>
                  </div>
                  <div class="mt-2 space-y-1">
                    <p class="text-sm text-gray-500">
                      Создана: {{ formatDate(task.created_at) }}
                    </p>
                    <p v-if="task.donetime" class="text-sm text-gray-500">
                      Выполнена: {{ formatDateTime(task.donetime) }}
                    </p>
                  </div>
                </div>
                <div class="mt-2 sm:mt-0 sm:ml-4 sm:text-right">
                  <span
                    class="text-xs font-medium px-2 py-1 bg-gray-100 text-gray-800 rounded"
                  >
                    #{{ task.id }}
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="text-center py-8 text-gray-500">
            <p>Задач пока нет</p>
          </div>

          <!-- Кнопка внизу для мобильных -->
          <div
            v-if="
              clientData.tasks_for_client &&
              clientData.tasks_for_client.length > 5
            "
            class="mt-6 pt-6 border-t border-gray-200 md:hidden"
          >
            <button
              @click="toggleAllTasks"
              class="w-full flex items-center justify-center px-4 py-2.5 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-md transition-colors"
            >
              <span v-if="showAllTasks">Свернуть задачи</span>
              <span v-else
                >Показать все задачи ({{ clientData.tasks_count }})</span
              >
              <ChevronDownIcon v-if="!showAllTasks" class="ml-2 w-4 h-4" />
              <ChevronUpIcon v-else class="ml-2 w-4 h-4" />
            </button>
          </div>
        </div>

        <!-- Быстрые ссылки -->
        <div class="mt-8 grid grid-cols-3 md:grid-cols-3 gap-4">
          <button
            @click="toggleAllPayments"
            class="bg-white border border-gray-300 rounded-lg p-4 text-center hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <p class="font-medium text-gray-900">Все платежи</p>
            <p class="text-sm text-gray-500 mt-1">
              {{ clientData.payments_count }} записей
            </p>
          </button>
          <button
            @click="toggleAllTasks"
            class="bg-white border border-gray-300 rounded-lg p-4 text-center hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <p class="font-medium text-gray-900">Все задачи</p>
            <p class="text-sm text-gray-500 mt-1">
              {{ clientData.tasks_count }} записей
            </p>
          </button>
          <button
            @click="reloadPage"
            class="bg-white border border-gray-300 rounded-lg p-4 text-center hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <p class="font-medium text-gray-900">Обновить</p>
            <p class="text-sm text-gray-500 mt-1">Актуальные данные</p>
          </button>
        </div>
      </div>

      <!-- Загрузка -->
      <div v-if="!error && !clientData" class="text-center py-12">
        <div
          class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"
        ></div>
        <p class="mt-4 text-gray-600">Загрузка данных из CRM...</p>
      </div>
    </div>

    <MainFooter />

    <Tracking
      :key="backendurl"
      :tracking="$page.props.tracking"
      :backendurl="props.backendurl"
    />
  </div>
</template>

<style scoped>
/* Дополнительные стили если нужно */
</style>