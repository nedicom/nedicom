<script setup>
import Chart from "@/Layouts/Chart.vue";

defineProps({
  subheader: String,
  city: Object,
  secondbannerimgmobile: String,
  reviewcoutnt: Number,
  rating: Number,
  metaimage: String
})

const prices = [
  {
    id: "one",
    name: "консультация",
    value: "Обычная консультация. Услугу оказывает специализированный юрист, который работает с проблемами похожими на Вашу",
    price: "1000",
    img: "simplecons.webp",
  },
  {
    id: "two",
    name: "профессиональная консультация",
    value: "Консультация по нестандартной ситуации. Например, когда не наработана судебная практика или затруднительно прогнозирование ситуации",
    price: "2000",
    img: "proficons.webp",
  },
  {
    id: "two",
    name: "оценка перспектив по делу",
    value: "Глубокая проработка проблемы с различными вариантами достижения конечной цели. Рекомендации по уменьшению рисков и сбору доказательств.",
    price: "2000",
    img: "objections.webp",
  },
  {
    id: "three",
    name: "исковое заявление",
    value: "Включает в себя подготовку документа, в том числе определение подсудности, способа защиты права, оснований иска и прочее.",
    price: "5000",
    img: "claim.webp",
  },
  {
    id: "four",
    name: "возражения на иск",
    value: "Документ, предназначенный для предоставления в суд, отображающий Вашу позицию по делу с приведением доводов и оснований.",
    price: "10000",
    img: "assessment.webp",
  },
  {
    id: "five",
    name: "апелляционная жалоба",
    value: "Документ, содержащий оценку решения суда первой инстанции с доводами для отмены или изменения указанного решения.",
    price: "10000",
    img: "appeal.webp",
  },
  {
    id: "five",
    name: "возражения на жалобу",
    value: "Документ, опровергающий позицию оппонента в апелляционной или кассационной жалобе.",
    price: "10000",
    img: "assessmentappeal.webp",
  },
  {
    id: "five",
    name: "кассационная жалоба",
    value: "Документ, содержащий оценку решений судов предидущих инстанций с доводами почему, указанные решения подлежат отмене.",
    price: "15000",
    img: "cassation.webp",
  },
  {
    id: "five",
    name: "участие в судебном заседении",
    value: "Включает предварительную консультацию по позиции с процессе, устный отчет о позиции суда и действиях сторон.",
    price: "10000",
    img: "meeting.webp",
  },
  {
    id: "five",
    name: "простое дело под ключ",
    value: "Сопровождение дела из расчета простое исковое заявление и участие в судебном заседании или обеспечение проведения заседания в отсутсвие представителя",
    price: "15000",
    img: "simplecase.webp",
  },
  {
    id: "five",
    name: "сложное дело под ключ",
    value: "Сопровождение дела в суде первой инстанции из расчета один документ (например, иск) и три судебных заседания.",
    price: "50000",
    img: "heavycase.webp",
  },
];
</script>

<template>
  <div id="prices" class="grid grid-cols-1 mt-12 justify-items-center">
    <h2 class="text-4xl mx-12 my-1 font-semibold text-grey text-center mb-10">Цена услуг юриста</h2>
    <div class="w-full md:w-2/3 mb-5 px-2">
      <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
              <th scope="col" class="px-6 py-3">
                Наименование услуги
              </th>
              <th scope="col" class="px-6 py-3">
                Описание услуги
              </th>
              <th scope="col" class="px-6 py-3">
                цена
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in prices" :key="item" itemprop="offers" itemscope itemtype="https://schema.org/Offer"
              class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
              <th scope="row" itemprop="name"
                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                {{ item.name }}
              </th>
              <td itemprop="description" class="px-6 py-4">
                {{ item.value }}
                <!-- Component Start -->
                <div class="relative flex flex-col items-center group">
                  <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                      clip-rule="evenodd" />
                  </svg>
                  <div class="absolute bottom-0 flex flex-col items-center hidden mb-5 group-hover:flex">
                    <span
                      class="relative rounded-md z-100 p-4 text-xs leading-none text-white whitespace-no-wrap bg-black shadow-lg">По статистике за 2024 год, диапазон
                      цены услуги {{ subheader }} по городу {{ city.title }} от {{ item.price / 2 }} р. до {{ item.price
                        * 2
                      }} р.</span>
                    <div class="w-3 h-3 -mt-2 rotate-45 bg-black"></div>
                  </div>
                </div>
                <!-- Component End  -->
              </td>
              <td itemprop="price" :content="item.price" class="px-6 py-4">
                {{ item.price }}<span itemprop="priceCurrency" class="inline" content="RUB">р.</span>
                <meta itemprop="priceValidUntil" content="2027-01-01">

                <meta itemprop="availability" content="https://schema.org/InStock" />

                <div itemprop="shippingDetails" itemtype="https://schema.org/OfferShippingDetails" itemscope>
                  <div itemprop="shippingRate" itemtype="https://schema.org/MonetaryAmount" itemscope>
                    <meta itemprop="value" content="0" />
                    <meta itemprop="currency" content="RUB" />
                  </div>
                  <div itemprop="shippingDestination" itemtype="https://schema.org/DefinedRegion" itemscope>
                    <meta itemprop="addressCountry" content="RU" />
                  </div>
                  <div itemprop="deliveryTime" itemtype="https://schema.org/ShippingDeliveryTime" itemscope>
                    <div itemprop="handlingTime" itemtype="https://schema.org/QuantitativeValue" itemscope>
                      <meta itemprop="minValue" content="0" />
                      <meta itemprop="maxValue" content="1" />
                      <meta itemprop="unitCode" content="DAY" />
                    </div>
                    <div itemprop="transitTime" itemtype="https://schema.org/QuantitativeValue" itemscope>
                      <meta itemprop="minValue" content="0" />
                      <meta itemprop="maxValue" content="1" />
                      <meta itemprop="unitCode" content="DAY" />
                    </div>
                  </div>
                </div>

                <div itemprop="hasMerchantReturnPolicy" itemtype="https://schema.org/MerchantReturnPolicy" itemscope>
                  <meta itemprop="applicableCountry" content="RU" />
                  <meta itemprop="returnMethod" content="https://schema.org/ReturnByMail" />
                  <meta itemprop="returnFees" content="https://schema.org/FreeReturn" />
                  <meta itemprop="returnPolicyCategory" content="https://schema.org/MerchantReturnFiniteReturnWindow" />
                  <meta itemprop="merchantReturnDays" content="3" />
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>

  </div>
</template>