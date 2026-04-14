<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import Tracking from "@/Components/Tracking.vue";
import { ref, computed, onMounted, onUnmounted } from "vue";
import { Head } from "@inertiajs/inertia-vue3";

let set = defineProps({
  auth: Object,
  backendurl: String,
});

// Основные поля формы
const form = ref({
  contractPrice: 5000000,
  deadlineDate: "",
  actualDate: "",
  isLegalEntity: false,
});

// Состояние сворачивания оглавления на ПК  
const isTocCollapsed = ref(false);

// Для отображения примера расчёта
const exampleDays = ref(90);
const examplePrice = 5000000;

const examplePenalty = computed(() => {
  const days = exampleDays.value;
  const price = examplePrice;
  const rate = 16.0;
  const penaltyRateValue = 1 / 150;
  const amount = price * (rate / 100) * penaltyRateValue * days;
  return Math.round(amount * 100) / 100;
});

const formattedExamplePenalty = computed(() => {
  return new Intl.NumberFormat("ru-RU", {
    style: "currency",
    currency: "RUB",
    minimumFractionDigits: 2,
  }).format(examplePenalty.value);
});

// Таблица ключевых ставок ЦБ РФ (полная история)
const fullKeyRates = [
  { date: "01.01.2026", rate: "16.00%", change: "+1.00%" },
  { date: "16.02.2026", rate: "15.50%", change: "-0.50%" },
  { date: "23.03.2026", rate: "15.00%", change: "-0.50%" },
  { date: "28.10.2024", rate: "21.00%", change: "+2.00%" },
  { date: "16.09.2024", rate: "19.00%", change: "+1.00%" },
  { date: "26.07.2024", rate: "18.00%", change: "+2.00%" },
  { date: "17.06.2024", rate: "16.00%", change: "0.00%" },
  { date: "26.04.2024", rate: "16.00%", change: "0.00%" },
  { date: "15.03.2024", rate: "16.00%", change: "0.00%" },
  { date: "16.02.2024", rate: "16.00%", change: "0.00%" },
  { date: "15.12.2023", rate: "16.00%", change: "+1.00%" },
  { date: "27.10.2023", rate: "15.00%", change: "+2.00%" },
  { date: "18.09.2023", rate: "13.00%", change: "+1.00%" },
  { date: "15.08.2023", rate: "12.00%", change: "+3.50%" },
  { date: "24.07.2023", rate: "8.50%", change: "+1.00%" },
  { date: "01.05.2022", rate: "14.00%", change: "-3.00%" },
  { date: "11.04.2022", rate: "17.00%", change: "-3.00%" },
  { date: "28.02.2022", rate: "20.00%", change: "+10.50%" },
];

// Текущие ставки для расчёта (только после 01.01.2026)
const keyRates = [
  { startDate: "2026-01-01", endDate: "2026-02-15", rate: 16.0 },
  { startDate: "2026-02-16", endDate: "2026-03-22", rate: 15.5 },
  { startDate: "2026-03-23", endDate: "2099-12-31", rate: 15.0 },
];

// Функция для форматирования даты
const formatDate = (date) => {
  if (!date) return "—";
  const d = new Date(date);
  return d.toLocaleDateString("ru-RU");
};

// Расчёт просрочки по периодам с детализацией по дням и ставкам
const penaltyBreakdown = computed(() => {
  if (!form.value.deadlineDate || !form.value.actualDate) {
    return { periods: [], total: 0, dailyBreakdown: [] };
  }

  const deadline = new Date(form.value.deadlineDate);
  const actual = new Date(form.value.actualDate);
  const startDate = new Date("2026-01-01");

  if (actual <= deadline || actual <= startDate) {
    return { periods: [], total: 0, dailyBreakdown: [] };
  }

  let penaltyStart = deadline > startDate ? deadline : startDate;

  if (penaltyStart >= actual) {
    return { periods: [], total: 0, dailyBreakdown: [] };
  }

  const periods = [];
  const dailyBreakdown = [];
  let currentDate = new Date(penaltyStart);
  const endDate = new Date(actual);

  for (const ratePeriod of keyRates) {
    if (currentDate >= endDate) break;

    const periodStart = new Date(
      Math.max(currentDate, new Date(ratePeriod.startDate))
    );
    const periodEnd = new Date(Math.min(endDate, new Date(ratePeriod.endDate)));

    if (periodStart < periodEnd) {
      const days = Math.ceil((periodEnd - periodStart) / (1000 * 60 * 60 * 24));
      if (days > 0) {
        periods.push({
          startDate: periodStart,
          endDate: periodEnd,
          rate: ratePeriod.rate,
          days: days,
          startDateStr: formatDate(periodStart),
          endDateStr: formatDate(periodEnd),
        });

        dailyBreakdown.push({
          period: periods.length,
          startDate: periodStart,
          endDate: periodEnd,
          startDateStr: formatDate(periodStart),
          endDateStr: formatDate(periodEnd),
          rate: ratePeriod.rate,
          days: days,
        });
      }
      currentDate = periodEnd;
    }
  }

  return {
    periods,
    total: periods.reduce((sum, p) => sum + p.days, 0),
    dailyBreakdown,
  };
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
  return new Intl.NumberFormat("ru-RU", {
    style: "currency",
    currency: "RUB",
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(penaltyAmount.value);
});

const calculationDetails = computed(() => {
  if (penaltyBreakdown.value.periods.length === 0) return [];

  const price = form.value.contractPrice;
  const rateValue = penaltyRate.value;

  return penaltyBreakdown.value.periods.map((period) => {
    const amount = price * (period.rate / 100) * rateValue * period.days;
    return {
      ...period,
      amount: Math.round(amount * 100) / 100,
      formattedAmount: new Intl.NumberFormat("ru-RU", {
        style: "currency",
        currency: "RUB",
        minimumFractionDigits: 2,
      }).format(amount),
    };
  });
});

const dateErrors = computed(() => {
  if (!form.value.deadlineDate || !form.value.actualDate) return "";
  if (new Date(form.value.actualDate) < new Date(form.value.deadlineDate)) {
    return "✓ Просрочки нет (объект сдан вовремя или раньше)";
  }
  if (new Date(form.value.actualDate) <= new Date("2026-01-01")) {
    return "✓ Объект сдан до 01.01.2026, неустойка не начисляется";
  }
  return "";
});

const resetForm = () => {
  form.value = {
    contractPrice: 5000000,
    deadlineDate: "",
    actualDate: "",
    isLegalEntity: false,
  };
};

// Функция отправки целей в Яндекс Метрику
const sendYaGoal = (goalName) => {
  if (typeof ym !== "undefined") {
    ym("24900584", "reachGoal", goalName);
  }
};

// Функция расчёта с прокруткой к результату
const calculateAndScroll = () => {
  const resultElement = document.getElementById("calculation-result");
  if (resultElement && daysDelay.value > 0 && penaltyAmount.value > 0) {
    resultElement.scrollIntoView({
      behavior: "smooth",
      block: "center",
    });
    sendYaGoal("calculate_success");
  } else if (daysDelay.value > 0 && penaltyAmount.value > 0) {
    sendYaGoal("calculate_success");
  }
};

// Функция скролла к якорю
const scrollToSection = (sectionId) => {
  const element = document.getElementById(sectionId);
  if (element) {
    element.scrollIntoView({
      behavior: "smooth",
      block: "start",
    });
  }
};

const activeSection = ref("calculator");
const showMobileMenu = ref(false);

// Функция отслеживания активной секции при скролле
const handleScroll = () => {
  const sections = [
    "calculator",
    "how-it-works",
    "guide",
    "templates",
    "rates-table",
    "what-next",
  ];
  const scrollPosition = window.scrollY + 150; // небольшой офсет

  for (const section of sections) {
    const element = document.getElementById(section);
    if (element) {
      const offsetTop = element.offsetTop;
      const offsetBottom = offsetTop + element.offsetHeight;
      if (scrollPosition >= offsetTop && scrollPosition < offsetBottom) {
        activeSection.value = section;
        break;
      }
    }
  }
};

// Скачать расчёт в PDF
const downloadPDF = () => {
  const resultBlock = document.getElementById("calculation-result-print");
  if (!resultBlock) return;

  sendYaGoal("download_pdf");

  const printContent = resultBlock.cloneNode(true);

  const style = document.createElement("style");
  style.textContent = `
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      padding: 40px;
      margin: 0;
      background: white;
    }
    .print-container {
      max-width: 800px;
      margin: 0 auto;
      padding: 30px;
      border: 2px solid #3b82f6;
      border-radius: 16px;
      background: white;
    }
    .header {
      text-align: center;
      margin-bottom: 30px;
      padding-bottom: 20px;
      border-bottom: 2px solid #e5e7eb;
    }
    .title {
      font-size: 24px;
      font-weight: bold;
      color: #1e3a8a;
      margin-bottom: 8px;
    }
    .subtitle {
      color: #6b7280;
      font-size: 14px;
    }
    .amount {
      text-align: center;
      background: #eff6ff;
      padding: 20px;
      border-radius: 12px;
      margin: 20px 0;
    }
    .amount-value {
      font-size: 36px;
      font-weight: bold;
      color: #2563eb;
    }
    .info-row {
      display: flex;
      justify-content: space-between;
      padding: 10px 0;
      border-bottom: 1px solid #f3f4f6;
    }
    .details-section {
      margin-top: 20px;
    }
    .period-item {
      margin-bottom: 15px;
      padding-bottom: 15px;
      border-bottom: 1px solid #e5e7eb;
    }
    .period-title {
      font-weight: bold;
      margin-bottom: 8px;
      color: #1e3a8a;
    }
    .period-details {
      font-size: 14px;
      color: #4b5563;
      margin-bottom: 5px;
    }
    .period-amount {
      color: #2563eb;
      font-weight: bold;
      margin-top: 5px;
    }
    .footer {
      margin-top: 30px;
      text-align: center;
      font-size: 12px;
      color: #9ca3af;
      border-top: 1px solid #e5e7eb;
      padding-top: 20px;
    }
  `;

  const penaltyAmountText =
    document.querySelector("#calculation-result .font-bold")?.innerText ||
    formattedPenalty.value;
  const daysDelayText = daysDelay.value;
  const deadlineDateText = form.value.deadlineDate || "—";
  const actualDateText = form.value.actualDate || "—";
  const contractPriceText = new Intl.NumberFormat("ru-RU").format(
    form.value.contractPrice
  );
  const penaltyRateText = `1/${form.value.isLegalEntity ? "300" : "150"}`;

  let periodsHtml = "";
  if (calculationDetails.value.length > 0) {
    periodsHtml = `
      <div class="details-section">
        <div style="font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #1e3a8a;">📊 Детализация расчёта по периодам действия ключевой ставки:</div>
        ${calculationDetails.value
          .map(
            (period) => `
          <div class="period-item">
            <div class="period-title">Период ${period.startDateStr} — ${period.endDateStr}</div>
            <div class="period-details">🏦 Ключевая ставка ЦБ: ${period.rate}%</div>
            <div class="period-details">📅 Количество дней в периоде: ${period.days}</div>
            <div class="period-details">⚖️ Расчётная ставка: ${penaltyRateText}</div>
            <div class="period-amount">💰 Сумма за период: ${period.formattedAmount}</div>
          </div>
        `
          )
          .join("")}
      </div>
    `;
  }

  const printHtml = `
    <!DOCTYPE html>
    <html>
      <head>
        <meta charset="UTF-8">
        <title>Расчёт неустойки по ДДУ от ${new Date().toLocaleDateString(
          "ru-RU"
        )}</title>
        ${style.outerHTML}
      </head>
      <body>
        <div class="print-container">
          <div class="header">
            <div class="title">Расчёт неустойки по ДДУ</div>
            <div class="subtitle">Согласно Федеральному закону № 214-ФЗ</div>
            <div class="subtitle">Расчёт с учётом ключевой ставки ЦБ РФ после 01.01.2026</div>
          </div>
          <div class="amount">
            <div style="font-size: 14px; color: #6b7280; margin-bottom: 8px;">Общая сумма неустойки по ДДУ</div>
            <div class="amount-value">${penaltyAmountText}</div>
          </div>
          <div class="info-row">
            <span>📅 Общее количество дней просрочки:</span>
            <strong>${daysDelayText}</strong>
          </div>
          <div class="info-row">
            <span>📋 Плановая дата сдачи (по договору):</span>
            <strong>${deadlineDateText}</strong>
          </div>
          <div class="info-row">
            <span>✅ Фактическая дата передачи (акт приёма-передачи):</span>
            <strong>${actualDateText}</strong>
          </div>
          <div class="info-row">
            <span>💰 Цена квартиры по ДДУ:</span>
            <strong>${contractPriceText} ₽</strong>
          </div>
          <div class="info-row">
            <span>⚖️ Расчётная ставка:</span>
            <strong>${penaltyRateText} (для ${
    form.value.isLegalEntity ? "юридических лиц" : "физических лиц"
  })</strong>
          </div>
          ${periodsHtml}
          <div class="footer">
            <div>Расчёт произведён на калькуляторе nedicom.ru</div>
            <div>Дата расчёта: ${new Date().toLocaleDateString("ru-RU")}</div>
            <div>Телефон для консультации: +7 978 8838 978</div>
            <div style="margin-top: 10px;">* Расчёт учитывает только период просрочки после 01.01.2026</div>
          </div>
        </div>
      </body>
    </html>
  `;

  const printWindow = window.open("", "_blank");
  printWindow.document.write(printHtml);
  printWindow.document.close();
  printWindow.print();
};

// Функция для клика по кнопке "Написать юристу"
const contactLawyer = () => {
  sendYaGoal("contact_lawyer");
};

const downloadTemplate = (filename) => {
  // Отправляем цель в Яндекс Метрику
  sendYaGoal("download_template");
};

onMounted(() => {
  // Добавляем Schema.org разметку
  window.addEventListener("scroll", handleScroll);
  handleScroll(); // Запускаем сразу

  if (typeof ym === "undefined") {
    const ymScript = document.createElement("script");
    ymScript.innerHTML = `
      (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
      m[i].l=1*new Date();
      for(var j=0;j<document.scripts.length;j++){if(document.scripts[j].src===r){return;}}
      k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
      (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
      ym(93864388, "init", {
        clickmap: true,
        trackLinks: true,
        accurateTrackBounce: true,
        webvisor: true
      });
    `;
    document.head.appendChild(ymScript);
  }
});

onUnmounted(() => {
  window.removeEventListener("scroll", handleScroll);
});
</script>

<template>
  <Head
    title="Калькулятор неустойки по ДДУ онлайн - бесплатный расчёт просрочки застройщика | Мина и партнеры"
  >
    <meta
      name="description"
      content="🚀 Калькулятор неустойки по ДДУ 2026. Бесплатный расчёт просрочки сдачи квартиры застройщиком с учётом моратория. Калькулятор ДДУ онлайн - точный расчёт неустойки по 214-ФЗ."
    />
    <meta
      name="keywords"
      content="калькулятор ДДУ, калькулятор ДДУ онлайн, калькулятор неустойки по ДДУ, расчет неустойки по ДДУ"
    />
    <meta name="robots" content="index, follow" />
    <meta
      property="og:title"
      content="Калькулятор неустойки по ДДУ - бесплатный онлайн расчёт | Мина и партнеры"
    />
    <meta
      property="og:description"
      content="Калькулятор ДДУ онлайн: точный расчёт неустойки за просрочку сдачи квартиры. Учитываем мораторий и актуальные ставки ЦБ РФ с 01.01.2026."
    />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://nedicom.ru/calculator-ddu" />
    <meta
      property="og:image"
      content="https://nedicom.ru/storage/usr/1/avatar/1739343105avatar.webp"
    />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes"
    />
    <link rel="canonical" href="https://nedicom.ru/calculator-ddu" />
  </Head>

  <MainHeader :auth="auth" />

  <Body>
    <div
      class="bg-white py-6 sm:py-12 min-h-screen max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
      itemscope
      itemtype="https://schema.org/SoftwareApplication"
    >
      <!-- Скрытые хлебные крошки для SEO -->
      <div
        itemscope
        itemtype="https://schema.org/BreadcrumbList"
        style="display: none"
      >
        <div
          itemprop="itemListElement"
          itemscope
          itemtype="https://schema.org/ListItem"
        >
          <meta itemprop="name" content="Главная" />
          <a itemprop="item" href="https://nedicom.ru/"></a>
          <meta itemprop="position" content="1" />
        </div>
        <div
          itemprop="itemListElement"
          itemscope
          itemtype="https://schema.org/ListItem"
        >
          <meta itemprop="name" content="Калькулятор неустойки по ДДУ" />
          <meta itemprop="position" content="2" />
        </div>
      </div>

      <meta itemprop="name" content="Калькулятор неустойки по ДДУ" />
      <meta
        itemprop="description"
        content="Бесплатный онлайн калькулятор неустойки по ДДУ. Расчёт просрочки сдачи квартиры застройщиком с учётом моратория и актуальных ставок ЦБ РФ."
      />
      <meta itemprop="applicationCategory" content="LegalApplication" />
      <meta itemprop="operatingSystem" content="Web" />
      <meta itemprop="url" content="https://nedicom.ru/calculator-ddu" />
      <!-- ↓↓↓ НОВЫЙ БЛОК ДЛЯ offers ↓↓↓ -->
      <div itemprop="offers" itemscope itemtype="https://schema.org/Offer">
        <meta itemprop="price" content="0" />
        <meta itemprop="priceCurrency" content="RUB" />
        <meta itemprop="availability" content="https://schema.org/OnlineOnly" />
      </div>
      <!-- ↑↑↑ НОВЫЙ БЛОК ДЛЯ offers ↑↑↑ -->

      <!-- ↓↓↓ НОВЫЙ БЛОК ДЛЯ aggregateRating ↓↓↓ -->
      <div
        itemprop="aggregateRating"
        itemscope
        itemtype="https://schema.org/AggregateRating"
      >
        <meta itemprop="ratingValue" content="4.9" />
        <meta itemprop="bestRating" content="5" />
        <meta itemprop="ratingCount" content="156" />
      </div>
      <!-- ↑↑↑ НОВЫЙ БЛОК ДЛЯ aggregateRating ↑↑↑ -->

      <!-- Десктопное фиксированное оглавление (справа) -->
      <!-- Десктопное фиксированное оглавление (справа) -->
<div class="fixed-toc hidden lg:block">
  <div class="p-4">
    <button 
      @click="isTocCollapsed = !isTocCollapsed" 
      class="w-full flex justify-between items-center font-semibold text-gray-800 mb-3 text-sm hover:text-blue-600 transition"
    >
      <span>📑 Содержание</span>
      <svg 
        class="w-4 h-4 transition-transform" 
        :class="{ 'rotate-180': !isTocCollapsed }"
        fill="none" 
        stroke="currentColor" 
        viewBox="0 0 24 24"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </button>
    <nav v-show="!isTocCollapsed" class="space-y-2 transition-all duration-300">
      <button
        @click="scrollToSection('calculator')"
        :class="[
          'toc-item w-full text-left px-3 py-2 text-sm rounded-lg transition',
          activeSection === 'calculator'
            ? 'active bg-blue-50 text-blue-600 font-medium'
            : 'text-gray-600 hover:bg-gray-50',
        ]"
      >
        🧮 Калькулятор
      </button>
      <button
        @click="scrollToSection('how-it-works')"
        :class="[
          'toc-item w-full text-left px-3 py-2 text-sm rounded-lg transition',
          activeSection === 'how-it-works'
            ? 'active bg-blue-50 text-blue-600 font-medium'
            : 'text-gray-600 hover:bg-gray-50',
        ]"
      >
        🔧 Как работает
      </button>
      <button
        @click="scrollToSection('what-next')"
        :class="[
          'toc-item w-full text-left px-3 py-2 text-sm rounded-lg transition',
          activeSection === 'what-next'
            ? 'active bg-blue-50 text-blue-600 font-medium'
            : 'text-gray-600 hover:bg-gray-50',
        ]"
      >
        ⚡ Что дальше
      </button>
      <button
        @click="scrollToSection('templates')"
        :class="[
          'toc-item w-full text-left px-3 py-2 text-sm rounded-lg transition',
          activeSection === 'templates'
            ? 'active bg-blue-50 text-blue-600 font-medium'
            : 'text-gray-600 hover:bg-gray-50',
        ]"
      >
        📋 Шаблоны
      </button>
      <button
        @click="scrollToSection('guide')"
        :class="[
          'toc-item w-full text-left px-3 py-2 text-sm rounded-lg transition',
          activeSection === 'guide'
            ? 'active bg-blue-50 text-blue-600 font-medium'
            : 'text-gray-600 hover:bg-gray-50',
        ]"
      >
        📖 Пошаговый гайд
      </button>
      <button
        @click="scrollToSection('rates-table')"
        :class="[
          'toc-item w-full text-left px-3 py-2 text-sm rounded-lg transition',
          activeSection === 'rates-table'
            ? 'active bg-blue-50 text-blue-600 font-medium'
            : 'text-gray-600 hover:bg-gray-50',
        ]"
      >
        📊 Ставки ЦБ
      </button>
    </nav>
  </div>
</div>

      <!-- Мобильная плавающая кнопка -->
      <div
        class="mobile-toc-button lg:hidden"
        @click="showMobileMenu = !showMobileMenu"
      >
        <svg
          class="w-6 h-6 text-white"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M4 6h16M4 12h16M4 18h16"
          ></path>
        </svg>
      </div>

      <!-- Мобильное меню (появляется при клике) -->
      <div v-if="showMobileMenu" class="mobile-menu lg:hidden">
        <div class="p-3">
          <div class="flex justify-between items-center mb-2 pb-2 border-b">
            <span class="font-semibold text-gray-800 text-sm"
              >📑 Содержание</span
            >
            <button
              @click="showMobileMenu = false"
              class="text-gray-400 hover:text-gray-600"
            >
              ✕
            </button>
          </div>
          <nav class="space-y-1">
            <button
              @click="
                scrollToSection('calculator');
                showMobileMenu = false;
              "
              class="w-full text-left px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition"
            >
              🧮 Калькулятор
            </button>
            <button
              @click="
                scrollToSection('how-it-works');
                showMobileMenu = false;
              "
              class="w-full text-left px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition"
            >
              🔧 Как работает
            </button>
            <button
              @click="
                scrollToSection('what-next');
                showMobileMenu = false;
              "
              class="w-full text-left px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition"
            >
              ⚡ Что дальше
            </button>

            <button
              @click="
                scrollToSection('templates');
                showMobileMenu = false;
              "
              class="w-full text-left px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition"
            >
              📋 Шаблоны документов
            </button>
            <button
              @click="
                scrollToSection('guide');
                showMobileMenu = false;
              "
              class="w-full text-left px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition"
            >
              📖 Пошаговый гайд
            </button>
            <button
              @click="
                scrollToSection('rates-table');
                showMobileMenu = false;
              "
              class="w-full text-left px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition"
            >
              📊 Ставки ЦБ
            </button>
          </nav>
        </div>
      </div>

      <!-- promo блок -->
      <div
        class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-12"
      >
        <h1
          class="mb-4 text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight leading-none text-gray-900"
        >
          Калькулятор неустойки <br />по ДДУ (214-ФЗ)
        </h1>

        <!-- БЛОК ЭКСПЕРТНОСТИ ДЛЯ E-E-A-T -->
        <!-- БЛОК ЭКСПЕРТНОСТИ ДЛЯ E-E-A-T -->
        <div
          class="mb-10 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border border-blue-100 shadow-sm"
          itemscope
          itemtype="https://schema.org/LegalService"
          itemid="https://nedicom.ru/#legalservice"
        >
          <meta itemprop="name" content="Мина и Партнеры" />
          <meta itemprop="url" content="https://nedicom.ru/" />
          <meta itemprop="telephone" content="+7 978 8838 978" />
          <meta itemprop="priceRange" content="₽₽" />
          <meta
            itemprop="image"
            content="https://nedicom.ru/storage/usr/1/avatar/1739343105avatar.webp"
          />

          <div
            itemprop="address"
            itemscope
            itemtype="https://schema.org/PostalAddress"
          >
            <meta itemprop="addressCountry" content="RU" />
            <meta itemprop="addressLocality" content="Москва" />
            <meta itemprop="addressRegion" content="Москва" />
            <meta itemprop="postalCode" content="107140" />
            <meta
              itemprop="streetAddress"
              content="улица Каланчевская 16 строение 1"
            />
          </div>

          <div class="flex flex-col md:flex-row items-center gap-6">
            <div class="flex-shrink-0">
              <div class="flex -space-x-2 overflow-hidden">
                <a
                  href="https://nedicom.ru/uslugi/moscow/yurist-po-nedvizhimosti/yurist-po-ddu/yurist-po-ddu-1"
                  target="_blank"
                  rel="author nofollow noopener"
                >
                  <img
                    class="inline-block h-12 w-12 md:h-16 md:w-16 rounded-full ring-2 ring-white object-cover transition-transform duration-300 ease-in-out hover:scale-110 hover:z-10 hover:shadow-lg"
                    src="https://nedicom.ru/storage/usr/1/avatar/1739343105avatar.webp"
                    alt="юрист по ДДУ Мина Марк"
                    width="64"
                    height="64"
                    loading="lazy"
                  />
                </a>
                <a
                  href="https://nedicom.ru/uslugi/moscow/yurist-po-nedvizhimosti/yurist-po-ddu/yurist-po-vzyskaniyu-neustoyki-po-ddu"
                  target="_blank"
                  rel="author nofollow noopener"
                >
                  <img
                    class="inline-block h-12 w-12 md:h-16 md:w-16 rounded-full ring-2 ring-white object-cover transition-transform duration-300 ease-in-out hover:scale-110 hover:z-10 hover:shadow-lg"
                    src="https://nedicom.ru/storage/usr/95/avatar/avatar_1760706642.webp"
                    alt="Юрист Мина Ольга Викторовна"
                    width="64"
                    height="64"
                    loading="lazy"
                  />
                </a>
                <a
                  href="https://nedicom.ru/uslugi/moscow/yurist-po-nedvizhimosti/yurist-po-ddu/yurist-po-ddu-3"
                  target="_blank"
                  rel="author nofollow noopener"
                >
                  <img
                    class="inline-block h-12 w-12 md:h-16 md:w-16 rounded-full ring-2 ring-white object-cover transition-transform duration-300 ease-in-out hover:scale-110 hover:z-10 hover:shadow-lg"
                    src="https://nedicom.ru/storage/usr/68/avatar/1728933924avatar.webp"
                    alt="Юрист Анастасия Кравцова"
                    width="64"
                    height="64"
                    loading="lazy"
                  />
                </a>
                <a
                  href="https://nedicom.ru/uslugi/moscow/yurist-po-nedvizhimosti/yurist-po-ddu/yurist-po-ddu-2"
                  target="_blank"
                  rel="author nofollow noopener"
                >
                  <img
                    class="inline-block h-12 w-12 md:h-16 md:w-16 rounded-full ring-2 ring-white object-cover transition-transform duration-300 ease-in-out hover:scale-110 hover:z-100 hover:shadow-lg"
                    src="https://nedicom.ru/storage/usr/94/avatar/1728156724avatar.webp"
                    alt="Юрист Добренький Андрей"
                    width="64"
                    height="64"
                    loading="lazy"
                  />
                </a>
              </div>
            </div>
            <div class="flex-1 text-center md:text-left">
              <div
                class="inline-block bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded-full mb-2"
              >
                ⭐ Разработано практикующими юристами по ДДУ
              </div>
              <p class="text-gray-700 text-sm md:text-base leading-relaxed">
                <strong class="text-gray-900"
                  >Калькулятор создан командой юристов</strong
                >
                под руководством
                <a
                  href="https://nedicom.ru/lawyers/1"
                  target="_blank"
                  rel="author nofollow noopener"
                  class="text-blue-700 hover:underline font-semibold"
                  >Марка Мины</a
                >
                — юриста с опытом с 2008 года и более
                <strong>516 консультаций</strong> по всей России.
              </p>

              <!-- НОВАЯ ССЫЛКА НА ГРУППУ ВК -->
              <a
                href="https://vk.com/calculator_ddu"
                target="_blank"
                rel="noopener noreferrer"
                @click="sendYaGoal('click_vk_group')"
                class="inline-flex items-center gap-2 mt-3 text-sm text-gray-600 hover:text-blue-600 transition group"
              >
                <svg
                  class="w-10 h-10 fill-current text-[#4c75a3] group-hover:text-blue-600"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="-20 0 190 190"
                  fill="none"
                >
                  <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M90.62 147.24C80.3 147.24 65.62 146.72 55.83 146.72C37.52 146.72 32.55 135.89 32.55 122.12C32.55 108.35 32.82 93.1198 32.82 87.8998C32.82 72.4298 39.67 63.8398 56.65 63.8398C62.93 63.8398 86.55 63.7798 94.59 63.7798C110.04 63.7798 117.45 70.1598 117.45 88.8398C117.45 93.4498 117.11 102.08 117.11 126.01C117.11 141.06 110 147.24 90.62 147.24ZM106.62 93.7298L96 86.1698L80.38 103.39L80.86 88.0898L66.3 87.8498L68 93.4398L68.34 110.44C63.04 109.52 56.58 96.2298 56.99 88.4398L43.12 88.6298C46.5 116.04 66.76 129.35 79.69 127.47L80 117.35C81 116.87 82.36 116.13 84 115.15L93.35 127.42L108.73 126.59L94 108C98.9452 103.948 103.209 99.1306 106.63 93.7298H106.62Z"
                    fill="#000000"
                  />
                </svg>

                <span
                  class="border-b border-dashed border-gray-400 group-hover:border-blue-600"
                  >Больше информации в группе ВКонтакте →</span
                >
              </a>
            </div>
            <div class="flex-shrink-0">
              <a
                href="https://nedicom.ru/?uslugaurl=yurist-po-ddu"
                target="_blank"
                @click="sendYaGoal('consultation_click')"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 md:py-2.5 md:px-5 rounded-lg transition text-sm md:text-base shadow-md"
              >
                💼 Бесплатная консультация
              </a>
            </div>
          </div>
        </div>

        <p
          class="my-8 text-base sm:text-lg md:text-2xl font-normal text-gray-500 lg:text-2xl sm:px-16 xl:px-48"
        >
          🏗️ Калькулятор ДДУ онлайн — рассчитайте неустойку за просрочку сдачи
          квартиры
        </p>
      </div>

      <!-- ============================================ -->
      <!-- ОСНОВНАЯ ФОРМА КАЛЬКУЛЯТОРА -->
      <!-- ============================================ -->
      <div id="calculator" class="scroll-mt-20">
        <form
          @submit.prevent="calculateAndScroll"
          class="py-6 sm:py-12 grid grid-cols-1 md:grid-cols-2 place-items-center gap-6 sm:gap-10 text-gray-900 text-xl sm:text-3xl font-medium"
        >
          <h2 class="text-2xl sm:text-4xl">📋 Данные для расчёта</h2>
          <h2 class="text-2xl sm:text-4xl hidden sm:block">💰 Результат</h2>

          <!-- Цена квартиры -->
          <div class="grid place-items-center w-full px-2 sm:px-0">
            <label for="contractPrice" class="block mb-2 text-xl sm:text-2xl">
              Цена квартиры по ДДУ:
              <span
                class="tooltip cursor-help ml-1 text-gray-400"
                title="Сумма, указанная в договоре долевого участия"
                >ⓘ</span
              >
            </label>
            <div class="relative flex items-center max-w-[12rem] w-full">
              <input
                type="number"
                id="contractPrice"
                v-model.number="form.contractPrice"
                min="0"
                max="100000000"
                step="100000"
                placeholder="Например: 4 500 000"
                class="bg-gray-50 text-xl sm:text-2xl border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full px-3 sm:px-4 py-2 sm:py-3"
              />
            </div>
            <p class="mt-2 text-sm sm:text-lg text-gray-500">
              Укажите сумму по договору
            </p>
          </div>

          <!-- Результат расчёта -->
          <div
            id="calculation-result"
            class="text-lg w-full bg-gray-50 p-3 sm:p-4 rounded-xl mx-2 sm:mx-0 text-center"
          >
            <p class="font-bold text-2xl sm:text-3xl text-blue-600 break-all">
              {{ formattedPenalty }}
            </p>
            <p class="text-gray-500 mt-2 text-sm sm:text-base">
              Сумма неустойки по ДДУ
            </p>
            <p class="text-xs text-gray-400 mt-1">
              Расчёт с 01.01.2026 по текущим ставкам ЦБ
            </p>

            <div class="flex flex-col sm:flex-row gap-3 justify-center mt-3">
              <button
                v-if="daysDelay > 0 && penaltyAmount > 0"
                type="button"
                @click="downloadPDF"
                class="text-sm bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-2 rounded-lg transition cursor-pointer"
              >
                📄 Скачать расчёт в PDF
              </button>

              <a
                v-if="daysDelay > 0 && penaltyAmount > 0"
                href="https://max.ru/u/f9LHodD0cOIOko3QyW7xJP0XAAxMC6z-ONhYlor_e_y57zDNO5kSBtbgv8I"
                target="_blank"
                @click="contactLawyer"
                class="inline-flex items-center justify-center gap-2 text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition"
              >
                ✉️ Написать юристу
              </a>
            </div>
          </div>

          <!-- Плановая дата -->
          <div class="grid place-items-center w-full px-2 sm:px-0">
            <label for="deadlineDate" class="block mb-2 text-xl sm:text-2xl">
              Плановая дата сдачи:
              <span
                class="tooltip cursor-help ml-1 text-gray-400"
                title="Дата, до которой застройщик обязался сдать дом по договору"
                >ⓘ</span
              >
            </label>
            <div class="relative flex items-center max-w-[12rem] w-full">
              <input
                type="date"
                id="deadlineDate"
                v-model="form.deadlineDate"
                class="bg-gray-50 text-base sm:text-lg border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full px-3 sm:px-4 py-2 sm:py-3"
              />
            </div>
            <p class="mt-2 text-sm sm:text-lg text-gray-500">
              Указана в договоре как срок передачи
            </p>
          </div>

          <div class="text-lg w-full space-y-2 px-2 sm:px-0 text-center">
            <p>
              📅 Дней просрочки:
              <strong class="text-xl sm:text-2xl">{{ daysDelay }}</strong>
            </p>
            <p class="text-xs sm:text-sm text-gray-500 break-all">
              С {{ form.deadlineDate || "—" }} по {{ form.actualDate || "—" }}
            </p>
            <p class="text-xs text-orange-600">
              * Учитывается только период после 01.01.2026
            </p>
          </div>

          <!-- Фактическая дата -->
          <div class="grid place-items-center w-full px-2 sm:px-0">
            <label for="actualDate" class="block mb-2 text-xl sm:text-2xl">
              Фактическая дата передачи:
              <span
                class="tooltip cursor-help ml-1 text-gray-400"
                title="Дата подписания акта приёма-передачи квартиры"
                >ⓘ</span
              >
            </label>
            <div class="relative flex items-center max-w-[12rem] w-full">
              <input
                type="date"
                id="actualDate"
                v-model="form.actualDate"
                class="bg-gray-50 text-base sm:text-lg border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full px-3 sm:px-4 py-2 sm:py-3"
              />
            </div>
            <p class="mt-2 text-sm sm:text-lg text-gray-500">
              Дата подписания акта приёма-передачи
            </p>
            <p
              v-if="dateErrors"
              class="text-green-600 text-xs sm:text-base mt-1"
            >
              {{ dateErrors }}
            </p>
          </div>

          <div class="text-lg w-full space-y-2 px-2 sm:px-0 text-center">
            <p>
              ⚖️ Ставка для расчёта:
              <strong>1/{{ form.isLegalEntity ? "300" : "150" }}</strong>
            </p>
            <p class="text-xs sm:text-sm text-gray-500">
              Для физлиц — 1/150, для юрлиц — 1/300
            </p>
          </div>

          <!-- Чекбокс -->
          <div class="grid place-items-center w-full px-2 sm:px-0">
            <label class="inline-flex items-center cursor-pointer">
              <input
                type="checkbox"
                v-model="form.isLegalEntity"
                class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
              />
              <span class="ms-2 text-base sm:text-lg"
                >🏢 Я юридическое лицо</span
              >
            </label>
          </div>

          <!-- Кнопка РАССЧИТАТЬ -->
          <div
            class="col-span-1 md:col-span-2 flex flex-col items-center gap-4 w-full"
          >
            <button
              type="submit"
              class="w-full md:w-auto px-8 py-4 text-xl font-bold text-white bg-green-600 hover:bg-green-700 rounded-lg transition shadow-lg hover:shadow-xl"
            >
              🚀 Рассчитать неустойку →
            </button>
            <button
              type="button"
              @click="resetForm"
              class="inline-flex items-center px-5 py-2 text-base font-medium text-center text-white bg-gray-500 rounded-lg hover:bg-gray-600"
            >
              🔄 Сбросить форму
            </button>
          </div>

          <!-- Сообщение после расчёта -->
          <div
            class="col-span-1 md:col-span-2 text-lg text-green-700 text-sm sm:text-base font-medium px-2 sm:px-0 text-center"
          >
            <p v-if="daysDelay > 0 && penaltyAmount > 0">
              ✅ Вы можете требовать от застройщика {{ formattedPenalty }}
              <br />
              + штраф 5% за отказ добровольно выплатить (штраф 5% действует
              вместо 50% с 01.01.2026 по ч. 3 ст. 10 ФЗ № 214-ФЗ)
            </p>
            <p v-else class="text-gray-400">
              Заполните даты и нажмите «Рассчитать неустойку»
            </p>
          </div>
        </form>
      </div>

      <!-- ДЕТАЛИЗАЦИЯ РАСЧЁТА ПО ПЕРИОДАМ -->
      <div
        v-if="calculationDetails.length > 0"
        class="mt-8 p-6 bg-blue-50 rounded-xl border border-blue-200"
      >
        <h3 class="text-xl font-bold text-blue-800 mb-4">
          📊 Детализация расчёта по периодам действия ключевой ставки:
        </h3>
        <div class="space-y-4">
          <div
            v-for="(period, index) in calculationDetails"
            :key="index"
            class="bg-white p-4 rounded-lg shadow-sm border border-blue-100"
          >
            <div
              class="flex flex-col md:flex-row md:justify-between md:items-center gap-3"
            >
              <div>
                <div class="font-semibold text-gray-800">
                  📅 Период: {{ period.startDateStr }} — {{ period.endDateStr }}
                </div>
                <div class="text-sm text-gray-600 mt-1">
                  🏦 Ключевая ставка ЦБ:
                  <strong class="text-blue-600">{{ period.rate }}%</strong>
                </div>
                <div class="text-sm text-gray-600">
                  📆 Дней в периоде: {{ period.days }}
                </div>
              </div>
              <div class="text-right">
                <div class="text-sm text-gray-500">Сумма за период:</div>
                <div class="text-xl font-bold text-green-600">
                  {{ period.formattedAmount }}
                </div>
              </div>
            </div>
          </div>
          <div class="pt-4 border-t border-blue-200">
            <div class="flex justify-between items-center">
              <div class="font-bold text-gray-800">
                📅 Всего дней просрочки:
              </div>
              <div class="font-bold text-xl text-blue-600">{{ daysDelay }}</div>
            </div>
            <div class="flex justify-between items-center mt-2">
              <div class="font-bold text-gray-800">
                💰 Общая сумма неустойки:
              </div>
              <div class="font-bold text-2xl text-green-600">
                {{ formattedPenalty }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Скрытый блок для печати PDF -->
      <div id="calculation-result-print" style="display: none">
        <div class="amount">
          <div style="font-size: 14px; color: #6b7280; margin-bottom: 8px">
            Сумма неустойки по ДДУ
          </div>
          <div class="amount-value">{{ formattedPenalty }}</div>
        </div>
        <div class="info-row">
          <span>📅 Дней просрочки:</span>
          <strong>{{ daysDelay }}</strong>
        </div>
        <div class="info-row">
          <span>📋 Плановая дата сдачи:</span>
          <strong>{{ form.deadlineDate || "—" }}</strong>
        </div>
        <div class="info-row">
          <span>✅ Фактическая дата передачи:</span>
          <strong>{{ form.actualDate || "—" }}</strong>
        </div>
        <div class="info-row">
          <span>💰 Цена квартиры по ДДУ:</span>
          <strong
            >{{
              new Intl.NumberFormat("ru-RU").format(form.contractPrice)
            }}
            ₽</strong
          >
        </div>
        <div class="info-row">
          <span>⚖️ Ставка для расчёта:</span>
          <strong>1/{{ form.isLegalEntity ? "300" : "150" }}</strong>
        </div>
      </div>

      <!-- ============================================ -->
      <!-- РЕАЛЬНЫЙ ПРИМЕР РАСЧЁТА -->
      <!-- ============================================ -->
      <div class="py-10 px-4 mx-auto max-w-screen-xl border-t border-gray-200">
        <h2
          class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-6 text-center"
        >
          📐 Реальный пример расчёта неустойки по ДДУ
        </h2>
        <div
          class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-2xl border border-green-200"
        >
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <p class="text-lg font-semibold mb-2">📋 Вводные данные:</p>
              <ul class="space-y-2 text-gray-700">
                <li>
                  🏢 <strong>Цена квартиры по ДДУ:</strong>
                  {{ new Intl.NumberFormat("ru-RU").format(examplePrice) }} ₽
                </li>
                <li>
                  📅 <strong>Количество дней просрочки:</strong>
                  {{ exampleDays }} дней
                </li>
                <li>
                  ⚖️ <strong>Статус дольщика:</strong> Физическое лицо (ставка
                  1/150)
                </li>
                <li>
                  🏦 <strong>Ключевая ставка ЦБ:</strong> 16.00% (актуальна на
                  период просрочки)
                </li>
              </ul>
            </div>
            <div class="text-center md:text-left">
              <p class="text-lg font-semibold mb-2">💰 Результат расчёта:</p>
              <p class="text-3xl md:text-4xl font-bold text-green-700">
                {{ formattedExamplePenalty }}
              </p>
              <p class="text-sm text-gray-500 mt-2">
                Формула: 5 000 000 × (16% / 100) × 1/150 × 90 дней
              </p>
              <p class="text-xs text-gray-400 mt-1">
                * Дополнительно можно взыскать штраф 5% + компенсацию морального
                вреда
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- БЛОК 1: Как работает калькулятор -->
      <div
        id="how-it-works"
        class="py-10 px-4 mx-auto max-w-screen-xl border-t border-gray-200 scroll-mt-20"
      >
        <h2
          class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-6"
        >
          🔧 Как работает калькулятор неустойки по ДДУ
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-blue-50 p-5 rounded-xl">
            <div class="text-3xl mb-3">1️⃣</div>
            <h3 class="text-lg sm:text-xl font-semibold mb-2">
              Введите данные договора
            </h3>
            <p class="text-gray-600 text-sm sm:text-base">
              Укажите цену квартиры по ДДУ, плановую дату сдачи из договора и
              фактическую дату подписания акта.
            </p>
          </div>
          <div class="bg-blue-50 p-5 rounded-xl">
            <div class="text-3xl mb-3">2️⃣</div>
            <h3 class="text-lg sm:text-xl font-semibold mb-2">
              Автоматический расчёт
            </h3>
            <p class="text-gray-600 text-sm sm:text-base">
              Калькулятор ДДУ онлайн самостоятельно определяет количество дней
              просрочки и применяет актуальную ключевую ставку ЦБ РФ.
            </p>
          </div>
          <div class="bg-blue-50 p-5 rounded-xl">
            <div class="text-3xl mb-3">3️⃣</div>
            <h3 class="text-lg sm:text-xl font-semibold mb-2">
              Получите сумму неустойки
            </h3>
            <p class="text-gray-600 text-sm sm:text-base">
              Вы увидите точную сумму неустойки по 214-ФЗ с детализацией по
              периодам действия разных ставок ЦБ.
            </p>
          </div>
        </div>
        <p
          class="text-gray-500 text-sm sm:text-base mt-6 p-4 bg-gray-50 rounded-lg"
        >
          💡 <strong>Формула расчёта:</strong> Сумма неустойки = Цена ДДУ ×
          Ключевая ставка ЦБ (%) × 1/150 (для физлиц) × Количество дней
          просрочки.
        </p>
      </div>

      <!-- ============================================ -->
      <!-- ЧТО ДЕЛАТЬ ПОСЛЕ РАСЧЁТА + CTA -->
      <!-- ============================================ -->
      <div
        id="what-next"
        class="py-10 px-4 mx-auto max-w-screen-xl border-t border-gray-200 scroll-mt-20"
      >
        <h2
          class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-6 text-center"
        >
          ⚡ Что делать после расчёта неустойки?
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div
            class="bg-white p-5 rounded-xl border border-gray-200 text-center"
          >
            <div class="text-3xl mb-2">📄</div>
            <h3 class="font-bold text-lg">Составить претензию</h3>
            <p class="text-gray-500 text-sm">
              Правильно оформленная претензия — 50% успеха
            </p>
          </div>
          <div
            class="bg-white p-5 rounded-xl border border-gray-200 text-center"
          >
            <div class="text-3xl mb-2">⚖️</div>
            <h3 class="font-bold text-lg">Подготовить иск</h3>
            <p class="text-gray-500 text-sm">
              Соберём доказательства и подадим в суд
            </p>
          </div>
          <div
            class="bg-white p-5 rounded-xl border border-gray-200 text-center"
          >
            <div class="text-3xl mb-2">🏦</div>
            <h3 class="font-bold text-lg">Получить деньги</h3>
            <p class="text-gray-500 text-sm">
              Контролируем исполнение решения суда
            </p>
          </div>
        </div>

        <div class="bg-blue-600 rounded-2xl p-8 text-center text-white">
          <h3 class="text-2xl font-bold mb-3">Не ждите — действуйте!</h3>
          <p class="mb-4 text-blue-100">
            Каждый день просрочки увеличивает вашу неустойку. Юристы нашей
            команды помогут взыскать её с застройщика.
          </p>
          <a
            href="https://nedicom.ru/?uslugaurl=yurist-po-ddu"
            target="_blank"
            @click="sendYaGoal('consultation_click')"
            class="inline-block bg-white text-blue-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition text-lg shadow-lg"
          >
            🚀 Записаться на бесплатную консультацию
          </a>
          <p class="text-xs text-blue-200 mt-3">
            Ответим за 15 минут. Более 500 выигранных дел по ДДУ
          </p>
        </div>
      </div>

      <!-- БЛОК С ШАБЛОНАМИ ДОКУМЕНТОВ -->
      <div
        id="templates"
        class="py-10 px-4 mx-auto max-w-screen-xl border-t border-gray-200 scroll-mt-20"
      >
        <h2
          class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-6 text-center"
        >
          📄 Шаблоны документов для скачивания
        </h2>
        <p class="text-gray-600 text-center mb-8 max-w-2xl mx-auto">
          Скачайте готовые шаблоны документов для взыскания неустойки с
          застройщика. Заполните данные и подайте в суд.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
          <!-- Шаблон 1: Досудебная претензия -->
          <div
            itemscope
            itemtype="https://schema.org/CreativeWork"
            class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition"
          >
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
              <div class="text-4xl mb-2">📋</div>
              <h3 itemprop="name" class="text-xl font-bold text-white">
                Досудебная претензия
              </h3>
              <meta
                itemprop="description"
                content="Готовый шаблон досудебной претензии к застройщику о выплате неустойки по ДДУ."
              />
            </div>
            <div class="p-5">
              <div class="space-y-2 mb-4 text-sm text-gray-600">
                <p>✅ Готовый бланк претензии</p>
                <p>✅ Заполните даты и сумму</p>
                <p>✅ Ссылка на закон 214-ФЗ</p>
              </div>
              <a
                href="/storage/templates/pretenziya-ddu.docx"
                download="pretenziya-ddu.docx"
                @click="downloadTemplate('pretenziya-ddu.docx')"
                class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition"
              >
                📥 Скачать шаблон (DOCX)
              </a>
              <p class="text-xs text-gray-400 text-center mt-3">
                Формат: Microsoft Word
              </p>
            </div>
          </div>

          <!-- Шаблон 2: Исковое заявление -->
          <div
            itemscope
            itemtype="https://schema.org/CreativeWork"
            class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition"
          >
            <div class="bg-gradient-to-r from-green-500 to-green-600 p-4">
              <div class="text-4xl mb-2">⚖️</div>
              <h3 itemprop="name" class="text-xl font-bold text-white">
                Исковое заявление
              </h3>
              <meta
                itemprop="description"
                content="Готовый шаблон искового заявления в суд о взыскании неустойки по ДДУ."
              />
            </div>
            <div class="p-5">
              <div class="space-y-2 mb-4 text-sm text-gray-600">
                <p>✅ Полностью готовый иск</p>
                <p>✅ Включает расчёт неустойки</p>
                <p>✅ Штраф 5% + моральный вред</p>
              </div>
              <a
                href="/storage/templates/iskovoe-zayavlenie-ddu.docx"
                download="iskovoe-zayavlenie-ddu.docx"
                @click="downloadTemplate('iskovoe-zayavlenie-ddu.docx')"
                class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition"
              >
                📥 Скачать шаблон (DOCX)
              </a>
              <p class="text-xs text-gray-400 text-center mt-3">
                Формат: Microsoft Word
              </p>
            </div>
          </div>
        </div>

        <div
          class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto mt-6"
        >
          <!-- Шаблон 3: Расчёт неустойки -->
          <div
            itemscope
            itemtype="https://schema.org/CreativeWork"
            class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition"
          >
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-4">
              <div class="text-4xl mb-2">💰</div>
              <h3 itemprop="name" class="text-xl font-bold text-white">
                Расчёт неустойки (таблица)
              </h3>
              <meta
                itemprop="description"
                content="Готовая таблица Excel для автоматического расчёта неустойки по ДДУ."
              />
            </div>
            <div class="p-5">
              <div class="space-y-2 mb-4 text-sm text-gray-600">
                <p>✅ Автоматический расчёт</p>
                <p>✅ Учитывает периоды просрочки</p>
                <p>✅ Таблица с детализацией</p>
              </div>
              <a
                href="/storage/templates/raschet-neustoyki.xlsx"
                download="raschet-neustoyki.xlsx"
                @click="downloadTemplate('raschet-neustoyki.xlsx')"
                class="block w-full text-center bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-4 rounded-lg transition"
              >
                📥 Скачать шаблон (XLSX)
              </a>
              <p class="text-xs text-gray-400 text-center mt-3">
                Формат: Microsoft Excel
              </p>
            </div>
          </div>

          <!-- Шаблон 4: Акт приёма-передачи -->
          <div
            itemscope
            itemtype="https://schema.org/CreativeWork"
            class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition"
          >
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-4">
              <div class="text-4xl mb-2">📝</div>
              <h3 itemprop="name" class="text-xl font-bold text-white">
                Акт приёма-передачи
              </h3>
              <meta
                itemprop="description"
                content="Шаблон акта приёма-передачи квартиры с указанием недостатков."
              />
            </div>
            <div class="p-5">
              <div class="space-y-2 mb-4 text-sm text-gray-600">
                <p>✅ Акт с перечнем недостатков</p>
                <p>✅ Защита прав дольщика</p>
                <p>✅ Основание для претензии</p>
              </div>
              <a
                href="/storage/templates/akt-priema-peredachi.docx"
                download="akt-priema-peredachi.docx"
                @click="downloadTemplate('akt-priema-peredachi.docx')"
                class="block w-full text-center bg-orange-600 hover:bg-orange-700 text-white font-semibold py-3 px-4 rounded-lg transition"
              >
                📥 Скачать шаблон (DOCX)
              </a>
              <p class="text-xs text-gray-400 text-center mt-3">
                Формат: Microsoft Word
              </p>
            </div>
          </div>
        </div>

        <div class="text-center text-gray-500 text-sm mt-6">
          ⚠️ Внимание: Шаблоны документов носят ознакомительный характер.
          Рекомендуем проконсультироваться с юристом перед подачей.
        </div>
      </div>

      <!-- ============================================ -->
      <!-- ПОШАГОВЫЙ ГАЙД: КАК ПОДАТЬ НА НЕУСТОЙКУ ПО ДДУ -->
      <!-- ============================================ -->
      <div
        id="guide"
        class="py-10 px-4 mx-auto max-w-screen-xl border-t border-gray-200 scroll-mt-20"
      >
        <h2
          class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-6"
        >
          📖 Пошаговый гайд: как подать на неустойку по ДДУ
        </h2>

        <div class="prose prose-lg max-w-none">
          <p class="text-gray-600 mb-6">
            Взыскание неустойки с застройщика — процесс, который при правильном
            подходе завершается успешно в 95% случаев. Ниже — подробная
            инструкция.
          </p>

          <div class="space-y-8">
            <div
              class="bg-white p-5 rounded-xl border-l-4 border-blue-500 shadow-sm"
            >
              <h3 class="text-xl font-bold text-gray-800 mb-3">
                Шаг 1. Соберите документы
              </h3>
              <p class="text-gray-600">
                Вам понадобятся: договор ДДУ, акт приёма-передачи (или
                односторонний акт), документы об оплате, переписка с
                застройщиком, досудебная претензия (если направляли).
              </p>
            </div>

            <div
              class="bg-white p-5 rounded-xl border-l-4 border-blue-500 shadow-sm"
            >
              <h3 class="text-xl font-bold text-gray-800 mb-3">
                Шаг 2. Рассчитайте неустойку
              </h3>
              <p class="text-gray-600">
                Используйте наш калькулятор выше. Он учитывает мораторий и
                актуальные ставки ЦБ. Зафиксируйте результат — он понадобится
                для претензии.
              </p>
            </div>

            <div
              class="bg-white p-5 rounded-xl border-l-4 border-blue-500 shadow-sm"
            >
              <h3 class="text-xl font-bold text-gray-800 mb-3">
                Шаг 3. Направьте досудебную претензию
              </h3>
              <p class="text-gray-600">
                Обратитесь к юристу для реализации этого шага. Неправильно
                составленная претензия ведет к уменьшению размера взыскиваемой
                суммы. Это обязательный этап перед судом. Отправьте заказным
                письмом с уведомлением или вручите под роспись. Застройщик
                обязан ответить в течение 30 дней.
              </p>
            </div>

            <div
              class="bg-white p-5 rounded-xl border-l-4 border-blue-500 shadow-sm"
            >
              <h3 class="text-xl font-bold text-gray-800 mb-3">
                Шаг 4. Обратитесь в суд
              </h3>
              <p class="text-gray-600">
                Обратитесь к юристу для реализации этого шага. Обратите
                внимание, что услуги юриста могут быть компенсированы судом в
                полном объеме. Если застройщик отказал или проигнорировал
                претензию — подавайте иск в районный суд по месту нахождения
                объекта. К иску приложите все документы + расчёт.
              </p>
            </div>

            <div
              class="bg-white p-5 rounded-xl border-l-4 border-blue-500 shadow-sm"
            >
              <h3 class="text-xl font-bold text-gray-800 mb-3">
                Шаг 5. Получите исполнительный лист
              </h3>
              <p class="text-gray-600">
                После решения суда в вашу пользу дождитесь вступления в силу,
                получите исполнительный лист и направьте его в службу судебных
                приставов или банк застройщика.
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- БЛОК 2: Что влияет на размер неустойки -->
      <div
        id="what-affects"
        class="py-10 px-4 mx-auto max-w-screen-xl border-t border-gray-200 scroll-mt-20"
      >
        <h2
          class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-6"
        >
          📊 Что влияет на размер неустойки застройщика
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="bg-gray-50 p-5 rounded-xl">
            <h3 class="text-lg sm:text-xl font-semibold mb-3">
              💰 Цена квартиры по ДДУ
            </h3>
            <p class="text-gray-600 text-sm sm:text-base">
              Неустойка рассчитывается как процент от цены договора.
            </p>
          </div>
          <div class="bg-gray-50 p-5 rounded-xl">
            <h3 class="text-lg sm:text-xl font-semibold mb-3">
              📅 Количество дней просрочки
            </h3>
            <p class="text-gray-600 text-sm sm:text-base">
              Каждый день просрочки увеличивает итоговую сумму.
            </p>
          </div>
          <div class="bg-gray-50 p-5 rounded-xl">
            <h3 class="text-lg sm:text-xl font-semibold mb-3">
              🏦 Ключевая ставка ЦБ РФ
            </h3>
            <p class="text-gray-600 text-sm sm:text-base">
              Неустойка по ДДУ привязана к ключевой ставке.
            </p>
          </div>
          <div class="bg-gray-50 p-5 rounded-xl">
            <h3 class="text-lg sm:text-xl font-semibold mb-3">
              👤 Статус дольщика (физлицо/юрлицо)
            </h3>
            <p class="text-gray-600 text-sm sm:text-base">
              Для физлиц — 1/150, для юрлиц — 1/300.
            </p>
          </div>
        </div>
      </div>

      <!-- БЛОК 3: Мораторий -->
      <div
        id="moratorium"
        class="py-10 px-4 mx-auto max-w-screen-xl border-t border-gray-200 scroll-mt-20"
      >
        <h2
          class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-6"
        >
          🛡️ Мораторий 2024–2026: что нужно знать дольщику
        </h2>
        <div class="bg-red-50 border-l-4 border-red-500 p-5 rounded-r-xl mb-6">
          <p class="text-red-800 font-semibold text-base sm:text-lg">
            ⚖️ Постановление Правительства РФ № 326 от 26.03.2022 — мораторий на
            начисление неустойки застройщикам
          </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="bg-white p-5 rounded-xl border border-gray-200">
            <h3 class="text-lg sm:text-xl font-semibold mb-3 text-red-700">
              📅 Период действия моратория
            </h3>
            <ul
              class="list-disc list-inside text-gray-600 text-sm sm:text-base space-y-2"
            >
              <li><strong>Начало:</strong> 29 марта 2022 года</li>
              <li>
                <strong>Окончание (официальное):</strong> 31 декабря 2023 года
              </li>
              <li>
                <strong>Фактическое продление:</strong> до 31 декабря 2025 года
              </li>
              <li>
                <strong>Возобновление начисления:</strong> с 1 января 2026 года
              </li>
            </ul>
          </div>
          <div class="bg-white p-5 rounded-xl border border-gray-200">
            <h3 class="text-lg sm:text-xl font-semibold mb-3 text-red-700">
              ⚠️ Что это значит для дольщиков?
            </h3>
            <ul
              class="list-disc list-inside text-gray-600 text-sm sm:text-base space-y-2"
            >
              <li>Неустойка не начисляется с 29.03.2022 по 31.12.2025</li>
              <li>Наш калькулятор автоматически исключает этот период</li>
              <li>Расчёт начинается с 1 января 2026 года</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- FAQ блок с Schema.org разметкой -->
      <div
        id="faq"
        class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-12 border-t border-gray-200 scroll-mt-20"
        itemscope
        itemtype="https://schema.org/FAQPage"
      >
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center mb-8">
          ❓ Часто задаваемые вопросы о неустойке по ДДУ
        </h2>
        <div class="space-y-6 max-w-3xl mx-auto">
          <div
            class="border-b border-gray-200 pb-4"
            itemscope
            itemprop="mainEntity"
            itemtype="https://schema.org/Question"
          >
            <h3
              itemprop="name"
              class="text-lg sm:text-xl font-semibold text-gray-800"
            >
              Как рассчитать неустойку по ДДУ?
            </h3>
            <div
              itemprop="acceptedAnswer"
              itemscope
              itemtype="https://schema.org/Answer"
            >
              <p
                class="text-sm sm:text-base text-gray-600 mt-2"
                itemprop="text"
              >
                Используйте наш калькулятор: цена квартиры × ключевая ставка ЦБ
                × 1/150 (для физлиц) × количество дней просрочки. Калькулятор
                автоматически учитывает мораторий и актуальные ставки ЦБ РФ.
              </p>
            </div>
          </div>
          <div
            class="border-b border-gray-200 pb-4"
            itemscope
            itemprop="mainEntity"
            itemtype="https://schema.org/Question"
          >
            <h3
              itemprop="name"
              class="text-lg sm:text-xl font-semibold text-gray-800"
            >
              Как рассчитать просрочку сдачи квартиры по ДДУ?
            </h3>
            <div
              itemprop="acceptedAnswer"
              itemscope
              itemtype="https://schema.org/Answer"
            >
              <p
                class="text-sm sm:text-base text-gray-600 mt-2"
                itemprop="text"
              >
                Калькулятор учитывает период с плановой даты сдачи по договору
                до фактической даты подписания акта приёма-передачи. При этом
                период с 29.03.2022 по 31.12.2025 исключается из-за моратория.
              </p>
            </div>
          </div>
          <div
            class="border-b border-gray-200 pb-4"
            itemscope
            itemprop="mainEntity"
            itemtype="https://schema.org/Question"
          >
            <h3
              itemprop="name"
              class="text-lg sm:text-xl font-semibold text-gray-800"
            >
              Учитывается ли мораторий в калькуляторе ДДУ?
            </h3>
            <div
              itemprop="acceptedAnswer"
              itemscope
              itemtype="https://schema.org/Answer"
            >
              <p
                class="text-sm sm:text-base text-gray-600 mt-2"
                itemprop="text"
              >
                Да, наш калькулятор полностью учитывает постановление
                Правительства РФ № 326 от 26.03.2022 и начисляет неустойку
                только за период просрочки после 01.01.2026.
              </p>
            </div>
          </div>
          <div
            class="border-b border-gray-200 pb-4"
            itemscope
            itemprop="mainEntity"
            itemtype="https://schema.org/Question"
          >
            <h3
              itemprop="name"
              class="text-lg sm:text-xl font-semibold text-gray-800"
            >
              Что можно взыскать с застройщика при просрочке?
            </h3>
            <div
              itemprop="acceptedAnswer"
              itemscope
              itemtype="https://schema.org/Answer"
            >
              <p
                class="text-sm sm:text-base text-gray-600 mt-2"
                itemprop="text"
              >
                При просрочке сдачи квартиры можно взыскать: неустойку по ДДУ
                (1/150 или 1/300 ставки ЦБ), штраф 5% за отказ добровольно
                выплатить неустойку, компенсацию морального вреда и судебные
                расходы.
              </p>
            </div>
          </div>
          <div
            class="border-b border-gray-200 pb-4"
            itemscope
            itemprop="mainEntity"
            itemtype="https://schema.org/Question"
          >
            <h3
              itemprop="name"
              class="text-lg sm:text-xl font-semibold text-gray-800"
            >
              Какая формула расчёта неустойки по ДДУ?
            </h3>
            <div
              itemprop="acceptedAnswer"
              itemscope
              itemtype="https://schema.org/Answer"
            >
              <p
                class="text-sm sm:text-base text-gray-600 mt-2"
                itemprop="text"
              >
                Формула расчёта: Сумма неустойки = Цена ДДУ × (Ключевая ставка
                ЦБ / 100) × (1/150 для физлиц или 1/300 для юрлиц) × Количество
                дней просрочки. Расчёт производится за каждый период действия
                ключевой ставки отдельно.
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- ============================================ -->
      <!-- ТАБЛИЦА С ИСТОРИЕЙ КЛЮЧЕВОЙ СТАВКИ ЦБ -->
      <!-- ============================================ -->
      <div
        id="rates-table"
        class="py-10 px-4 mx-auto max-w-screen-xl border-t border-gray-200 scroll-mt-20"
      >
        <h2
          class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-6"
        >
          📊 История ключевой ставки ЦБ РФ (2022–2026)
        </h2>
        <p class="text-gray-600 mb-4">
          Ключевая ставка напрямую влияет на размер неустойки по ДДУ. Чем выше
          ставка — тем больше выплата.
        </p>

        <div class="overflow-x-auto">
          <table class="w-full text-sm sm:text-base border-collapse">
            <thead>
              <tr class="bg-gray-100">
                <th class="border p-3 text-left">Дата изменения</th>
                <th class="border p-3 text-left">Ключевая ставка</th>
                <th class="border p-3 text-left">Изменение</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(rate, index) in fullKeyRates"
                :key="index"
                class="hover:bg-gray-50"
              >
                <td class="border p-3">{{ rate.date }}</td>
                <td
                  class="border p-3 font-semibold"
                  :class="
                    rate.rate.includes('21') ? 'text-red-600' : 'text-gray-800'
                  "
                >
                  {{ rate.rate }}
                </td>
                <td
                  class="border p-3"
                  :class="
                    rate.change.includes('+')
                      ? 'text-red-500'
                      : rate.change.includes('-')
                      ? 'text-green-500'
                      : 'text-gray-500'
                  "
                >
                  {{ rate.change }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <p class="text-xs text-gray-400 mt-4">
          * Данные актуальны на 2026 год. Полная история изменений с 2022 года.
        </p>
      </div>

      <div
        class="text-center text-gray-400 text-xs sm:text-sm pt-8 border-t border-gray-200"
      >
        <div>
          ⚠️ Калькулятор ДДУ учитывает просрочку только с учётом моратория с
          01.01.2026
        </div>
        <div class="mt-2">
          © 2026 — Юридическая помощь по ДДУ | Мина и Партнеры
        </div>
      </div>
    </div>
  </Body>

  <MainFooter />
  <Tracking
    :key="backendurl"
    :tracking="$page.props.tracking"
    :backendurl="set.backendurl"
  />
</template>

<style scoped>
.tooltip {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 18px;
  height: 18px;
  font-size: 12px;
  cursor: help;
}

/* Фиксированное оглавление для ПК */
.fixed-toc {
  position: fixed;
  top: 100px;
  right: 20px;
  width: 240px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  z-index: 40;
  transition: all 0.3s ease;
}

.fixed-toc .toc-item {
  transition: all 0.2s ease;
}

.fixed-toc .toc-item.active {
  background: #eff6ff;
  color: #2563eb;
  font-weight: 600;
  border-left: 3px solid #2563eb;
}

/* Плавающая кнопка для мобильных */
.mobile-toc-button {
  position: fixed;
  bottom: 80px;
  right: 16px;
  width: 56px;
  height: 56px;
  background: #2563eb;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
  z-index: 50;
  cursor: pointer;
  transition: all 0.3s ease;
}

.mobile-toc-button:hover {
  transform: scale(1.05);
  background: #1d4ed8;
}

.mobile-menu {
  position: fixed;
  bottom: 150px;
  right: 16px;
  background: white;
  border-radius: 16px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
  width: 220px;
  z-index: 49;
  overflow: hidden;
  animation: slideIn 0.2s ease;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Скрываем фиксированное оглавление на мобильных */
@media (max-width: 1024px) {
  .fixed-toc {
    display: none;
  }
}

/* Показываем кнопку только на мобильных */
@media (min-width: 1025px) {
  .mobile-toc-button,
  .mobile-menu {
    display: none;
  }
}

/* Мобильные оптимизации */
@media (max-width: 640px) {
  input,
  button {
    min-height: 48px;
  }
}

@media print {
  .no-print,
  .tooltip,
  button:not(.print-only) {
    display: none !important;
  }
}
</style>