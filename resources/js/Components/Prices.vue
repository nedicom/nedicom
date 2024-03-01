<script setup>

defineProps({
  subheader:String,
})
const header = "Стоимость услуг";
const description =
  "Рекомендуем стоимость и объем услуг определять на консультации, так Вы поймете насколько разрешимо Ваше дело и что нужно для достижения успеха. ";
const body =
  " Чтобы заплатить ровно столько, сколько нужно для решения Вашей проблемы, очень важно тщательно подготовиться, собрать все необходимоы документы, оценить вероятность успеха. Не спешите с выбором юриста. Трезво оценивайте шансы. Мы гордимся тем, что не обещаем клиентам недостижимых результатов, но при этом часто добиваемся поставленной цели.";
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
  <div itemprop="offers" itemscope itemtype="https://schema.org/Offer" class="grid grid-cols-1 md:grid-cols-3 p-5">
    <meta itemprop="priceValidUntil" content="2029-12-31">
    <div class="grid grid-cols-1 content-center p-5">
      <h2>
        <span class="text-3xl">{{ header }}</span> <span v-if="subheader"> в категории<br><span class="text-2xl">{{ subheader }}</span></span>
      </h2>

      <p class="font-medium mt-5">
        {{ description }}
      </p>
      <p class="font-light mt-5">
        {{ body }}
      </p>
      <link itemprop="availability" href="https://schema.org/InStock" /> В наличии
    </div>

    <div class="col-span-2">
      <div
        v-for="item in prices"
        :key="item"
        class="grid grid-cols-1 md:grid-cols-3"
      >
        <div class="rounded-full flex items-center justify-center">
          <img
            :src="'https://nedicom.ru//storage/images/services/' + item.img"
            width="120"
            class="rounded-full"
          />
        </div>

        <div
          class="col-span-2 grid grid-cols-1 content-center text-center md:text-start"
        >
          <div  itemprop="name" class="text-2xl font-bold">
            {{ item.name }}
          </div>
          <div itemprop="description" class="text-sm text-gray-500">
            {{ item.value }} 
          </div>
          <div class="mt-3">
            <span class="text-sm">стоимость: </span> {{ item.price }} рублей
          </div>
        </div>
      </div>
    </div>
  </div>
</template>