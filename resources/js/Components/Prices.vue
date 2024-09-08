<script setup>

defineProps({
  subheader: String,
  city: String,
  secondbannerimgmobile: String,
  reviewcoutnt: Number,
  rating: Number,
  metaimage: String
})

const header = "Средняя стоимость услуг";
const description =
  "рекомендуем стоимость и объем услуг определять на консультации. ";
const body =
  "Так Вы поймете насколько разрешимо Ваше дело и что нужно для достижения успеха.";
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
  <div itemscope itemtype="https://schema.org/Product" class="grid grid-cols-1 md:grid-cols-3 p-5 my-12">
    <div class="grid grid-cols-1 content-center p-5">
      <img itemprop="image" :src="metaimage" :alt="subheader" />
      <h2>
        <span class="text-3xl">{{ header }}</span> <span v-if="subheader"> в категории<br>
          <span itemprop="name">
            <span class="text-2xl">{{
          subheader }}</span>
            по городу <span itemprop="name" class="text-2xl">{{ city }}</span></span>
        </span>
      </h2>

      <p itemprop="description" class="font-medium mt-5">
        В категории {{
          subheader }} по городу {{ city }} {{ description }}
      </p>
      <p class="font-light mt-5">
        {{ body }}
      </p>

      <p itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating"
          class="text-xs mx-12 font-semibold text-grey text-center md:text-end py-5">
          общая оценка: <span itemprop="ratingValue">{{ rating }}</span>
          всего отзывов: <span itemprop="reviewCount">{{ reviewcoutnt }}</span>
        </p>

    </div>

    <div class="col-span-2">
      <div v-for="item in prices" :key="item" class="grid grid-cols-1 md:grid-cols-3">
        <div class="rounded-full flex items-center justify-center">
          <!--<img
            :src="'https://nedicom.ru/storage/images/services/' + item.img"
            width="120"
            class="rounded-full"
            :alt="item.name"
          />-->
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="size-6 w-5 md:visible invisible">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
          </svg>


        </div>

        <div itemprop="offers" itemscope itemtype="https://schema.org/Offer"
          class="col-span-2 grid grid-cols-1 content-center text-center md:text-start">
          <meta itemprop="priceValidUntil" content="2027-01-01">
          <div itemprop="name" class="text-2xl font-bold">
            {{ item.name }}
          </div>
          <div itemprop="description" class="text-sm text-gray-500">
            {{ item.value }}
          </div>
          <div class="mt-3">
            <span itemprop="price" class="text-sm" :content="item.price">стоимость: </span>
            <link itemprop="availability" href="https://schema.org/InStock" /> {{ item.price }} <span
              itemprop="priceCurrency" content="RUB">рублей </span>

          </div>

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

        </div>
      </div>
    </div>

  </div>
</template>