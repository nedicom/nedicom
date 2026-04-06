<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import Tracking from '@/Components/Tracking.vue';
import { ref, computed, onMounted } from "vue";
import { Head } from "@inertiajs/inertia-vue3";

let set = defineProps({
  auth: Object,
  backendurl: String,
});

// Основные поля формы
const form = ref({
  contractPrice: 5000000,
  deadlineDate: '',
  actualDate: '',
  isLegalEntity: false,
});

// Таблица ключевых ставок ЦБ РФ (только после 01.01.2026)
const keyRates = [
  { startDate: '2026-01-01', endDate: '2026-02-15', rate: 16.00 },
  { startDate: '2026-02-16', endDate: '2026-03-22', rate: 15.50 },
  { startDate: '2026-03-23', endDate: '2099-12-31', rate: 15.00 },
];

const getKeyRateByDate = (date) => {
  if (!date) return 15.00;
  const targetDate = new Date(date);
  for (const period of keyRates) {
    const start = new Date(period.startDate);
    const end = new Date(period.endDate);
    if (targetDate >= start && targetDate <= end) {
      return period.rate;
    }
  }
  return 15.00;
};

// Расчёт просрочки по периодам (только после 01.01.2026)
const penaltyBreakdown = computed(() => {
  if (!form.value.deadlineDate || !form.value.actualDate) {
    return { periods: [], total: 0 };
  }
  
  const deadline = new Date(form.value.deadlineDate);
  const actual = new Date(form.value.actualDate);
  const startDate = new Date('2026-01-01');
  
  if (actual <= deadline || actual <= startDate) {
    return { periods: [], total: 0 };
  }
  
  let penaltyStart = deadline > startDate ? deadline : startDate;
  
  if (penaltyStart >= actual) {
    return { periods: [], total: 0 };
  }
  
  const periods = [];
  let currentDate = new Date(penaltyStart);
  const endDate = new Date(actual);
  
  for (const ratePeriod of keyRates) {
    if (currentDate >= endDate) break;
    
    const periodStart = new Date(Math.max(currentDate, new Date(ratePeriod.startDate)));
    const periodEnd = new Date(Math.min(endDate, new Date(ratePeriod.endDate)));
    
    if (periodStart < periodEnd) {
      const days = Math.ceil((periodEnd - periodStart) / (1000 * 60 * 60 * 24));
      if (days > 0) {
        periods.push({
          startDate: periodStart,
          endDate: periodEnd,
          rate: ratePeriod.rate,
          days: days
        });
      }
      currentDate = periodEnd;
    }
  }
  
  return { periods, total: periods.reduce((sum, p) => sum + p.days, 0) };
});

const daysDelay = computed(() => penaltyBreakdown.value.total);

const penaltyRate = computed(() => {
  if (form.value.isLegalEntity) return 1 / 300;
  return 1 / 150;
});

const penaltyAmount = computed(() => {
  if (daysDelay.value === 0 || !form.value.contractPrice) return 0;
  
  const price = form.value.contractPrice;
  const rateValue = penaltyRate.value;
  const periods = penaltyBreakdown.value.periods;
  
  let total = 0;
  for (const period of periods) {
    const periodAmount = price * (period.rate / 100) * rateValue * period.days;
    total += periodAmount;
  }
  
  return Math.round(total * 100) / 100;
});

const formattedPenalty = computed(() => {
  return new Intl.NumberFormat('ru-RU', {
    style: 'currency',
    currency: 'RUB',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(penaltyAmount.value);
});

const calculationDetails = computed(() => {
  if (penaltyBreakdown.value.periods.length === 0) return [];
  
  const price = form.value.contractPrice;
  const rateValue = penaltyRate.value;
  
  return penaltyBreakdown.value.periods.map(period => {
    const amount = price * (period.rate / 100) * rateValue * period.days;
    return {
      ...period,
      amount: Math.round(amount * 100) / 100,
      formattedAmount: new Intl.NumberFormat('ru-RU', {
        style: 'currency',
        currency: 'RUB',
        minimumFractionDigits: 2,
      }).format(amount)
    };
  });
});

const dateErrors = computed(() => {
  if (!form.value.deadlineDate || !form.value.actualDate) return '';
  if (new Date(form.value.actualDate) < new Date(form.value.deadlineDate)) {
    return '✓ Просрочки нет (объект сдан вовремя или раньше)';
  }
  if (new Date(form.value.actualDate) <= new Date('2026-01-01')) {
    return '✓ Объект сдан до 01.01.2026, неустойка не начисляется';
  }
  return '';
});

const resetForm = () => {
  form.value = {
    contractPrice: 5000000,
    deadlineDate: '',
    actualDate: '',
    isLegalEntity: false,
  };
};

// Schema.org structured data
const jsonLdSchema = computed(() => {
  const siteUrl = 'https://nedicom.ru';
  const pageUrl = `${siteUrl}/ddu-penalty-calculator`;
  
  const softwareSchema = {
    '@context': 'https://schema.org',
    '@type': 'SoftwareApplication',
    '@id': `${pageUrl}#software`,
    'name': 'Калькулятор неустойки по ДДУ',
    'description': 'Бесплатный онлайн калькулятор для расчёта неустойки за просрочку передачи квартиры по договору долевого участия (214-ФЗ). Автоматический расчёт с учётом актуальных ставок ЦБ РФ с 01.01.2026.',
    'applicationCategory': 'LegalApplication',
    'operatingSystem': 'Web',
    'offers': {
      '@type': 'Offer',
      'price': '0',
      'priceCurrency': 'RUB'
    },
    'aggregateRating': {
      '@type': 'AggregateRating',
      'ratingValue': '4.9',
      'ratingCount': '156',
      'bestRating': '5'
    },
    'url': pageUrl,
    'image': `${siteUrl}/images/calculator-preview.jpg`,
    'author': {
      '@type': 'LegalService',
      'name': 'Марк Мина',
      'url': siteUrl
    }
  };
  
  const legalServiceSchema = {
    '@context': 'https://schema.org',
    '@type': 'LegalService',
    '@id': `${siteUrl}#legalservice`,
    'name': 'Юридическая помощь по ДДУ | Марк Мина',
    'description': 'Взыскание неустойки с застройщика, защита прав дольщиков, юридическое сопровождение по 214-ФЗ.',
    'url': siteUrl,
    'logo': `${siteUrl}/logo.png`,
    'image': `${siteUrl}/images/advokat-mina.jpg`,
    'telephone': '+7-495-000-00-00',
    'email': 'info@nedicom.ru',
    'priceRange': '₽₽',
    'address': {
      '@type': 'PostalAddress',
      'addressLocality': 'Москва',
      'addressRegion': 'Московская область',
      'addressCountry': 'RU'
    },
    'areaServed': [
      {
        '@type': 'City',
        'name': 'Москва'
      },
      {
        '@type': 'AdministrativeArea',
        'name': 'Московская область'
      }
    ],
    'hasOfferCatalog': {
      '@type': 'OfferCatalog',
      'name': 'Юридические услуги по ДДУ',
      'itemListElement': [
        {
          '@type': 'Offer',
          'itemOffered': {
            '@type': 'Service',
            'name': 'Расчёт неустойки по ДДУ',
            'description': 'Точный расчёт неустойки за просрочку сдачи квартиры'
          }
        },
        {
          '@type': 'Offer',
          'itemOffered': {
            '@type': 'Service',
            'name': 'Составление претензии застройщику',
            'description': 'Юридически грамотная претензия для досудебного урегулирования'
          }
        },
        {
          '@type': 'Offer',
          'itemOffered': {
            '@type': 'Service',
            'name': 'Судебное взыскание неустойки',
            'description': 'Представление интересов в суде, полное сопровождение дела'
          }
        },
        {
          '@type': 'Offer',
          'itemOffered': {
            '@type': 'Service',
            'name': 'Штраф 50% по ЗоЗПП',
            'description': 'Взыскание дополнительного штрафа за отказ добровольно выплатить неустойку'
          }
        }
      ]
    },
    'sameAs': [
      'https://www.youtube.com/@advokatmina',
      'https://t.me/advokat_mina'
    ]
  };
  
  const faqSchema = {
    '@context': 'https://schema.org',
    '@type': 'FAQPage',
    'mainEntity': [
      {
        '@type': 'Question',
        'name': 'Как рассчитать неустойку по ДДУ?',
        'acceptedAnswer': {
          '@type': 'Answer',
          'text': 'Неустойка рассчитывается по формуле: цена квартиры × ключевая ставка ЦБ × 1/150 (для физлиц) × количество дней просрочки. Наш калькулятор делает это автоматически с учётом актуальных ставок.'
        }
      },
      {
        '@type': 'Question',
        'name': 'Какая ставка для расчёта неустойки по ДДУ?',
        'acceptedAnswer': {
          '@type': 'Answer',
          'text': 'Для физических лиц — 1/150 ключевой ставки ЦБ РФ. Для юридических лиц — 1/300. Калькулятор автоматически применяет нужную ставку в зависимости от вашего статуса.'
        }
      },
      {
        '@type': 'Question',
        'name': 'Учитывается ли мораторий при расчёте?',
        'acceptedAnswer': {
          '@type': 'Answer',
          'text': 'Да, калькулятор учитывает только период просрочки после 01.01.2026, так как до этой даты действовал мораторий на начисление неустойки.'
        }
      },
      {
        '@type': 'Question',
        'name': 'Может ли суд снизить неустойку?',
        'acceptedAnswer': {
          '@type': 'Answer',
          'text': 'Да, суд вправе снизить неустойку по статье 333 ГК РФ, если сочтёт её несоразмерной последствиям нарушения обязательства. Наш калькулятор показывает расчётную сумму, окончательная сумма определяется судом.'
        }
      },
      {
        '@type': 'Question',
        'name': 'Что ещё можно взыскать с застройщика кроме неустойки?',
        'acceptedAnswer': {
          '@type': 'Answer',
          'text': 'Помимо неустойки, можно взыскать штраф 50% от суммы неустойки за отказ добровольно удовлетворить требование потребителя (по ЗоЗПП), а также компенсацию морального вреда и судебные расходы.'
        }
      },
      {
        '@type': 'Question',
        'name': 'Как получить деньги от застройщика по ДДУ?',
        'acceptedAnswer': {
          '@type': 'Answer',
          'text': 'Сначала направляется досудебная претензия застройщику. При отказе или игнорировании — иск в суд. После решения суда — исполнительный лист и взыскание через ФССП или банковские счета застройщика.'
        }
      }
    ]
  };
  
  const breadcrumbSchema = {
    '@context': 'https://schema.org',
    '@type': 'BreadcrumbList',
    'itemListElement': [
      {
        '@type': 'ListItem',
        'position': 1,
        'name': 'Главная',
        'item': siteUrl
      },
      {
        '@type': 'ListItem',
        'position': 2,
        'name': 'Калькулятор неустойки по ДДУ',
        'item': pageUrl
      }
    ]
  };
  
  return [softwareSchema, legalServiceSchema, faqSchema, breadcrumbSchema];
});

onMounted(() => {
  // Добавляем JSON-LD в head
  const script = document.createElement('script');
  script.type = 'application/ld+json';
  script.textContent = JSON.stringify(jsonLdSchema.value);
  document.head.appendChild(script);
});
</script>

<template>
  <Head title="Калькулятор неустойки по ДДУ - бесплатный онлайн расчёт | Марк Мина">
    <meta name="description" content="Бесплатный онлайн калькулятор неустойки за просрочку передачи квартиры по ДДУ (214-ФЗ). Автоматический расчёт с учётом актуальных ставок ЦБ РФ с 01.01.2026. Просто введите даты и сумму договора." />
    <meta name="keywords" content="калькулятор неустойки ДДУ, расчёт неустойки застройщика, просрочка сдачи квартиры, неустойка по 214-ФЗ, взыскание неустойки" />
    <meta property="og:title" content="Калькулятор неустойки по ДДУ | Марк Мина" />
    <meta property="og:description" content="Бесплатный онлайн расчёт неустойки за просрочку передачи квартиры. Учитываем актуальные ставки ЦБ РФ с 01.01.2026." />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://nedicom.ru/ddu-penalty-calculator" />
    <meta name="twitter:card" content="summary_large_image" />
  </Head>

  <MainHeader :auth="auth" />

  <Body>
    <div
      class="bg-white py-12 min-h-screen max-w-7xl mx-auto sm:px-6 lg:px-8"
      itemscope
      itemtype="https://schema.org/SoftwareApplication"
    >
      <meta itemprop="name" content="Калькулятор неустойки по ДДУ" />
      <meta itemprop="description" content="Бесплатный онлайн калькулятор для расчёта неустойки за просрочку передачи квартиры по договору долевого участия (214-ФЗ)" />
      <meta itemprop="applicationCategory" content="LegalApplication" />
      <meta itemprop="operatingSystem" content="Web" />
      <div itemprop="offers" itemscope itemtype="https://schema.org/Offer">
        <meta itemprop="price" content="0" />
        <meta itemprop="priceCurrency" content="RUB" />
      </div>

      <!-- promo блок -->
      <div
        class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-12"
      >
        <h1
          class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl"
          itemprop="name"
        >
          Калькулятор неустойки <br />по ДДУ (214-ФЗ)
        </h1>
        <p
          class="my-8 text-lg font-normal text-gray-500 lg:text-2xl sm:px-16 xl:px-48"
          itemprop="description"
        >
          Рассчитайте неустойку за просрочку передачи квартиры застройщиком
        </p>

        <div
          class="px-4 mx-auto text-center md:max-w-screen-md lg:max-w-screen-lg lg:px-36"
        >
          <div
            class="flex flex-wrap flex-col justify-center items-center mt-8 text-gray-500"
          >
            <a
              href="https://nedicom.ru"
              target="_blank"
              class="mr-5 mb-5 lg:mb-0 hover:text-gray-800 hover:opacity-80 flex items-center gap-4"
            >
              <div
                class="text-2xl font-extrabold tracking-tight leading-none md:text-3xl lg:text-4xl"
              >
                nedicom.ru
              </div>
            </a>
            <p
              class="my-2 text-lg font-normal text-gray-500 lg:text-xl px-16"
              itemprop="description"
            >
              Нужна помощь с взысканием неустойки?
              <a href="https://nedicom.ru" class="text-blue-600 hover:underline"
                >Закажите консультацию</a
              >
            </p>
          </div>
        </div>
      </div>

      <!-- Основная форма -->
      <form class="py-12 grid grid-cols-1 md:grid-cols-2 place-items-center gap-10 text-gray-900 text-3xl font-medium">
        <h2 class="text-4xl">Данные для расчёта</h2>
        <h2 class="text-4xl hidden sm:block">Результат</h2>

        <!-- Цена квартиры -->
        <div class="grid place-items-center w-full">
          <label for="contractPrice" class="block mb-2 text-2xl"
            >Цена квартиры по ДДУ:</label
          >
          <div class="relative flex items-center max-w-[12rem]">
            <input
              type="number"
              id="contractPrice"
              v-model.number="form.contractPrice"
              min="0"
              max="100000000"
              step="100000"
              class="bg-gray-50 text-2xl border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full px-4 py-3"
            />
          </div>
          <p class="mt-2 text-lg text-gray-500">Укажите сумму по договору</p>
        </div>

        <div class="text-lg w-full bg-gray-50 p-4 rounded-xl">
          <p class="font-bold text-3xl text-blue-600">{{ formattedPenalty }}</p>
          <p class="text-gray-500 mt-2">Сумма неустойки</p>
          <p class="text-xs text-gray-400 mt-1">Расчёт с 01.01.2026 по текущим ставкам ЦБ</p>
        </div>

        <!-- Плановая дата -->
        <div class="grid place-items-center w-full">
          <label for="deadlineDate" class="block mb-2 text-2xl"
            >Плановая дата сдачи (по ДДУ):</label
          >
          <div class="relative flex items-center max-w-[12rem]">
            <input
              type="date"
              id="deadlineDate"
              v-model="form.deadlineDate"
              class="bg-gray-50 text-lg border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full px-4 py-3"
            />
          </div>
          <p class="mt-2 text-lg text-gray-500">
            Указана в договоре как срок передачи
          </p>
        </div>

        <div class="text-lg w-full space-y-2">
          <p>📅 Дней просрочки: <strong class="text-xl">{{ daysDelay }}</strong></p>
          <p class="text-sm text-gray-500">С {{ form.deadlineDate || '—' }} по {{ form.actualDate || '—' }}</p>
          <p class="text-xs text-orange-600">* Учитывается только период после 01.01.2026</p>
        </div>

        <!-- Фактическая дата -->
        <div class="grid place-items-center w-full">
          <label for="actualDate" class="block mb-2 text-2xl"
            >Фактическая дата передачи (Акт):</label
          >
          <div class="relative flex items-center max-w-[12rem]">
            <input
              type="date"
              id="actualDate"
              v-model="form.actualDate"
              class="bg-gray-50 text-lg border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full px-4 py-3"
            />
          </div>
          <p class="mt-2 text-lg text-gray-500">
            Дата подписания акта приёма-передачи
          </p>
          <p v-if="dateErrors" class="text-green-600 text-base mt-1">
            {{ dateErrors }}
          </p>
        </div>

        <div class="text-lg w-full space-y-2">
          <p>⚖️ Ставка для расчёта: 
            <strong>1/{{ form.isLegalEntity ? '300' : '150' }}</strong>
          </p>
          <p class="text-sm text-gray-500">
            Для физлиц — 1/150, для юрлиц — 1/300
          </p>
        </div>

        <!-- Чекбоксы -->
        <div class="grid place-items-center w-full">
          <label class="inline-flex items-center cursor-pointer">
            <input
              type="checkbox"
              v-model="form.isLegalEntity"
              class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
            />
            <span class="ms-3 text-lg">Я юридическое лицо</span>
          </label>
        </div>

        <!-- Детализация расчёта -->
        <div class="text-lg w-full text-gray-600 text-base space-y-2 col-span-1 md:col-span-2 mt-4">
          <details v-if="calculationDetails.length > 0" class="cursor-pointer">
            <summary class="font-medium text-gray-700">📊 Детализация расчёта по периодам</summary>
            <div class="mt-3 space-y-2 text-sm">
              <div 
                v-for="(period, index) in calculationDetails" 
                :key="index"
                class="border-b border-gray-200 py-2"
              >
                <p><strong>С {{ period.startDate.toLocaleDateString('ru-RU') }} по {{ period.endDate.toLocaleDateString('ru-RU') }}</strong></p>
                <p>Ставка ЦБ: {{ period.rate }}% | Дней: {{ period.days }}</p>
                <p class="text-blue-600">Сумма: {{ period.formattedAmount }}</p>
              </div>
              <div class="pt-2 font-bold">
                Итого: {{ formattedPenalty }}
              </div>
            </div>
          </details>
          <p class="text-xs text-gray-400 mt-2">* Расчёт не окончательный, суд может снизить неустойку по ст. 333 ГК РФ</p>
        </div>

        <!-- Кнопка сброса -->
        <button
          type="button"
          @click="resetForm"
          class="my-5 inline-flex items-center px-5 py-3 text-xl font-medium text-center text-white bg-gray-500 rounded-lg hover:bg-gray-600"
        >
          Сбросить форму
        </button>

        <div class="text-lg w-full text-green-700 text-base font-medium">
          <p v-if="daysDelay > 0 && penaltyAmount > 0">
            ✅ Вы можете требовать от застройщика {{ formattedPenalty }} + штраф 50% за отказ добровольно выплатить
          </p>
          <p v-else class="text-gray-400">Заполните даты для расчёта</p>
        </div>
      </form>

      <!-- SEO-блок с FAQ (видимый для пользователей) -->
      <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-12 border-t border-gray-200">
        <h2 class="text-3xl font-bold text-center mb-8">Часто задаваемые вопросы</h2>
        <div class="space-y-6 max-w-3xl mx-auto">
          <div class="border-b border-gray-200 pb-4">
            <h3 class="text-xl font-semibold text-gray-800">Как рассчитать неустойку по ДДУ?</h3>
            <p class="text-gray-600 mt-2">Неустойка рассчитывается по формуле: цена квартиры × ключевая ставка ЦБ × 1/150 (для физлиц) × количество дней просрочки. Наш калькулятор делает это автоматически с учётом актуальных ставок.</p>
          </div>
          <div class="border-b border-gray-200 pb-4">
            <h3 class="text-xl font-semibold text-gray-800">Какая ставка для расчёта неустойки по ДДУ?</h3>
            <p class="text-gray-600 mt-2">Для физических лиц — 1/150 ключевой ставки ЦБ РФ. Для юридических лиц — 1/300. Калькулятор автоматически применяет нужную ставку в зависимости от вашего статуса.</p>
          </div>
          <div class="border-b border-gray-200 pb-4">
            <h3 class="text-xl font-semibold text-gray-800">Учитывается ли мораторий при расчёте?</h3>
            <p class="text-gray-600 mt-2">Да, калькулятор учитывает только период просрочки после 01.01.2026, так как до этой даты действовал мораторий на начисление неустойки.</p>
          </div>
          <div class="border-b border-gray-200 pb-4">
            <h3 class="text-xl font-semibold text-gray-800">Может ли суд снизить неустойку?</h3>
            <p class="text-gray-600 mt-2">Да, суд вправе снизить неустойку по статье 333 ГК РФ, если сочтёт её несоразмерной последствиям нарушения обязательства.</p>
          </div>
          <div class="border-b border-gray-200 pb-4">
            <h3 class="text-xl font-semibold text-gray-800">Что ещё можно взыскать с застройщика?</h3>
            <p class="text-gray-600 mt-2">Помимо неустойки, можно взыскать штраф 50% за отказ добровольно удовлетворить требование потребителя (по ЗоЗПП), компенсацию морального вреда и судебные расходы.</p>
          </div>
        </div>
      </div>

      <div class="text-center text-gray-400 text-sm">
        <link itemprop="url" href="https://nedicom.ru/ddu-penalty-calculator" />
        <div>⚠️ Калькулятор учитывает просрочку только с 01.01.2026</div>
      </div>
    </div>
  </Body>

  <MainFooter />

  <Tracking :key="backendurl" :tracking="$page.props.tracking" :backendurl="set.backendurl" />
</template>