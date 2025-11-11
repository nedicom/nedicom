<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import { ref, computed, watch } from "vue";
import { Head } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { reactive } from "vue";

let set = defineProps({
  auth: Object,
  pensionData: Object,
  errors: Object,
  years: Object,
});

const isChecked = ref(true); // Начальное состояние
const pensYear = ref(false); // Начальное состояние

let form = reactive({
  gender: set.pensionData.gender ? set.pensionData.gender : 20,
  stagh2002: set.pensionData.stagh2002,
  stagh1991: set.pensionData.stagh1991,
  zp: set.pensionData.zp,
  szp: set.pensionData.szp,
  pktwo: set.pensionData.pktwo,
  ipkn: set.pensionData.ipkn,
  year: set.pensionData.year,
});

// Автоматически обновляем yearData при изменении selectedYear
watch(
  () => form.year,
  (newYear) => {
    const selected = set.years.find((item) => item.year === newYear);
    pensYear.value = selected ? { ...selected } : null;
  },
  { immediate: true }
);

const pns = computed(() => {
  return form.stagh2002 ? Math.min(form.stagh2002 / form.gender, 1) : 0;
});

const sk = computed(() => {
  let value = form.stagh2002 > form.gender ? form.stagh2002 - form.gender : 0;
  return form.stagh2002
    ? Math.round(Math.min(0.55 + value * 0.01, 0.75) * 100) / 100
    : 0;
});

const szp = computed(() => {
  return form.szp ? Math.round((form.zp / form.szp) * 10000) / 10000 : 0;
});

const rp = computed(() => {
  if (isChecked.value) {
    console.log(isChecked.value);
    return form.szp ? Math.round(szp.value * sk.value * 1671.0 * 100) / 100 : 0;
  } else {
    console.log(isChecked.value);
    return 660;
  }
});

const PkOne = computed(() => {
  return form.szp
    ? Math.round((rp.value - 450) * pns.value * 228 * 5.61481656 * 100) / 100
    : 0;
});

const sv = computed(() => {
  return form.szp
    ? Math.round(PkOne.value * (0.1 + form.stagh1991 / 100) * 100) / 100
    : 0;
});

const pk = computed(() => {
  return form.ipkn
    ? Math.round((PkOne.value + sv.value + form.pktwo) * 100) / 100
    : 0;
});

const sch = computed(() => {
  return form.ipkn ? Math.round((pk.value / 228) * 100) / 100 : 0;
});

const ipks = computed(() => {
  return form.ipkn ? Math.round((sch.value / 64.1) * 100) / 100 : 0;
});

const ipktotal = computed(() => {
  return form.ipkn ? Math.round((form.ipkn + ipks.value) * 100) / 100 : 0;
});

const spst = computed(() => {
  if (!pensYear.value || !pensYear.value.stipk) return 0;
  return pensYear.value.stipk
    ? Math.round(
        (ipktotal.value * pensYear.value.stipk + pensYear.value.fvipl) * 100
      ) / 100
    : 0;
});

let submit = () => {
  Inertia.post("/pension", form);
};
</script>

<template>
  <Head title="Пенсионный калькулятор Марка Мины" />

  <MainHeader :auth="auth" />

  <Body>
    <div
      class="bg-white py-12 min-h-screen max-w-7xl mx-auto sm:px-6 lg:px-8"
      itemscope
      itemtype="https://schema.org/WebApplication"
    >
      <!--promo-->
      <div
        class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-12"
      >
        <h1
          class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl"
          itemprop="name"
        >
          Пенсионный калькулятор <br />Марка Мины
        </h1>
        <p
          class="my-8 text-lg font-normal text-gray-500 lg:text-2xl sm:px-16 xl:px-48"
          itemprop="description"
        >
          Калькулятор пенсии по старости с возможностью расчета ИПК за прошлый
          период
        </p>

        <div
          class="px-4 mx-auto text-center md:max-w-screen-md lg:max-w-screen-lg lg:px-36"
        >
          <div
            class="flex flex-wrap flex-col justify-center items-center mt-8 text-gray-500"
          >
            <a
              href="https://uslugi.yandex.ru/profile/MarkAnatolevichMina-2975377"
              target="_blank"
              rel="nofollow noopener noreferrer"
              class="mr-5 mb-5 lg:mb-0 hover:text-gray-800 hover:opacity-80 flex items-center gap-4"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="48"
                height="48"
                viewBox="0 0 48 48"
              >
                <path
                  fill="#fc3f1d"
                  d="M24 4A20 20 0 1 0 24 44A20 20 0 1 0 24 4Z"
                />
                <path
                  fill="#fff"
                  d="M32.5 16.5H15.5C14.1 16.5 13 17.6 13 19V29C13 30.4 14.1 31.5 15.5 31.5H32.5C33.9 31.5 35 30.4 35 29V19C35 17.6 33.9 16.5 32.5 16.5ZM19.5 28.5H16.5V21.5H19.5V28.5ZM23.5 28.5H20.5V21.5H23.5V28.5ZM27.5 28.5H24.5V21.5H27.5V28.5ZM31.5 28.5H28.5V21.5H31.5V28.5Z"
                />
              </svg>
              <div
                class="text-2xl font-extrabold tracking-tight leading-none md:text-3xl lg:text-4xl"
              >
                Яндекс услуги
              </div>
            </a>
            <p
              class="my-2 text-lg font-normal text-gray-500 lg:text-xl px-16"
              itemprop="description"
            >
              Если нужна бесплатная помощь, у нас стартовала акция -
              консультация за отзыв. Перейдите по ссылке, оформите заказ на мое
              имя на Яндекс услугах.
            </p>
          </div>
        </div>
      </div>

      <form
        @submit.prevent="submit"
        class="py-12 grid grid-cols-1 md:grid-cols-2 place-items-center gap-10 text-gray-900 text-3xl font-medium"
      >
        <!--header-->
        <h2 class="text-4xl">Ваши данные</h2>
        <h2 class="text-4xl hidden sm:block">Как мы считаем</h2>

        <!-- пол -->
        <div class="relative">
          <label for="quantity-input" class="block mb-2">Ваш пол:</label>
          <div class="flex items-center mb-4">
            <input
              id="default-radio-1"
              v-model="form.gender"
              type="radio"
              :value="25"
              name="default-radio"
              class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 text-xl"
            />
            <label
              for="default-radio-1"
              class="ms-2 text-lg font-medium text-gray-900"
              >Мужчина</label
            >
          </div>
          <div class="flex items-center">
            <input
              checked
              id="default-radio-2"
              v-model="form.gender"
              type="radio"
              :value="20"
              name="default-radio"
              class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500"
            />
            <label
              for="default-radio-2"
              class="ms-2 text-lg font-medium text-gray-900"
              >Женщина</label
            >
          </div>
          <div
            v-if="errors.gender"
            class="text-red-500 text-base animate-pulse"
          >
            {{ errors.gender }}
          </div>
        </div>
        <div class="text-lg w-full">
          Влияет на пропорцию неполного стажа. Для мужчин пропорция неполного
          стажа 100% при стаже в 25 лет до 2002 года. Для женщин достаточно 20.
          Ваш {{ form.gender }}
        </div>
        <!-- пол -->

        <!-- стаж до 2002 года -->
        <div class="grid place-items-center">
          <label for="quantity-input" class="block mb-2"
            >Трудовой стаж до 2002:</label
          >
          <div class="relative flex items-center max-w-[8rem]">
            <input
              type="number"
              id="stagh2002"
              v-model="form.stagh2002"
              aria-describedby="helper-text-explanation"
              min="0"
              max="50"
              step="1"
              class="bg-gray-50 text-2xl border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full px-4 py-3"
              placeholder="20"
              required
            />
          </div>
          <p id="helper-text-explanation" class="mt-2 text-lg text-gray-500">
            Укажите количество полных лет трудового стажа до 2002 года
          </p>
        </div>

        <div class="text-lg w-full">
          <p>Пропорция неполного стажа (ПНС) = {{ pns }}</p>
          <p>Стажевый коэффициент (СК) = {{ sk }}</p>
        </div>
        <!-- стаж до 2002 года -->

        <!-- справка о зарплате -->
        <div class="grid place-items-center">
          <label class="inline-flex items-center cursor-pointer">
            <input
              checked
              type="checkbox"
              v-model="isChecked"
              class="sr-only peer"
            />
            <div
              class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600"
            ></div>
            <span class="ms-3">Справка о зарплате</span>
          </label>
          <p id="helper-text-explanation" class="mt-2 text-lg text-gray-500">
            Справка о зарплате до 2002 года
          </p>
        </div>

        <div class="text-lg w-full">
          <p>Влияет на формулу расчета</p>
          <div v-if="isChecked">
            <p>Считаем по п. 3 ст. 30 ФЗ 173</p>
            <p>РП = {{ sk }} * {{ szp }} * 1 671.00 руб. = {{ rp }} руб.</p>
            <p>
              ПК1 = ({{ rp }} - 450) * {{ pns }} * 228 * 5.61481656 =
              {{ PkOne }} руб.
            </p>
          </div>

          <div v-else>
            <p>Считаем по п. 4 ст. 30 ФЗ 173</p>
            <p>РП = {{ rp }} руб.</p>
            <p>
              ПК1 = ({{ rp }} - 450) * {{ pns }} * 226 * 5.61481656 =
              {{ PkOne }} руб.
            </p>
          </div>
        </div>
        <!-- справка о зарплате -->

        <!-- размер зарплаты -->
        <transition
          enter-active-class="transition-opacity duration-300"
          enter-from-class="opacity-0"
          enter-to-class="opacity-100"
          leave-active-class="transition-opacity duration-300"
          leave-from-class="opacity-100"
          leave-to-class="opacity-0"
        >
          <span v-if="isChecked">
            <div class="grid place-items-center">
              <label for="quantity-input" class="block mb-2 text-2xl"
                >Размер зарплаты:
              </label>
              <div class="relative flex items-center max-w-[12rem]">
                <input
                  type="number"
                  id="zp"
                  v-model="form.zp"
                  aria-describedby="helper-text-explanation"
                  min="0"
                  max="50000"
                  step="1"
                  class="bg-gray-50 text-2xl border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full px-4 py-3"
                  placeholder="200"
                  required
                />
              </div>
              <p
                id="helper-text-explanation"
                class="mt-2 text-lg text-gray-500"
              >
                Ваша средняя зарплата на основании среднемесячного заработка за
                5 лет до 2000 года или за 2 года до 2002. Берем из справки от
                работодателя
              </p>
            </div>

            <div class="grid place-items-center">
              <label for="quantity-input" class="block mb-2 text-2xl"
                >Средняя зарплата по стране:
              </label>
              <div class="relative flex items-center max-w-[12rem]">
                <input
                  type="number"
                  id="stagh2002"
                  v-model="form.szp"
                  aria-describedby="helper-text-explanation"
                  min="0"
                  max="50000"
                  step="1"
                  class="bg-gray-50 text-2xl border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full px-4 py-3"
                  placeholder="215"
                  required
                />
              </div>
              <p
                id="helper-text-explanation"
                class="mt-2 text-lg text-gray-500"
              >
                Средняя зарплата по стране за 5 лет Вашей работы до 2000 года
                (или 2 года до 2002)
              </p>
            </div>
          </span>
        </transition>
        <!-- размер зарплаты -->

        <!-- средняя зарплата по стране -->
        <transition
          enter-active-class="transition-opacity duration-300"
          enter-from-class="opacity-0"
          enter-to-class="opacity-100"
          leave-active-class="transition-opacity duration-300"
          leave-from-class="opacity-100"
          leave-to-class="opacity-0"
        >
          <div v-if="isChecked" class="text-lg w-full">
            Соотношение зарплат (СЗП) = {{ szp }}
          </div>
        </transition>
        <!-- средняя зарплата по стране -->

        <!-- стаж до 1991 года -->
        <div class="grid place-items-center">
          <label for="quantity-input" class="block mb-2"
            >Стаж до 1991 года:</label
          >
          <div class="relative flex items-center max-w-[8rem]">
            <input
              type="number"
              id="stagh1991"
              v-model="form.stagh1991"
              min="0"
              max="50"
              step="1"
              aria-describedby="helper-text-explanation"
              class="bg-gray-50 text-2xl border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full px-4 py-3"
              placeholder="10"
              required
            />
          </div>
          <p id="helper-text-explanation" class="mt-2 text-lg text-gray-500">
            Укажите количество полных лет стажа до 1991 года
          </p>
        </div>

        <div class="text-lg w-full">
          <p>сумма валоризации = {{ sv }}</p>
          <p>{{ PkOne }} * (10% + {{ form.stagh1991 }} %) = {{ sv }}</p>
        </div>
        <!-- стаж до 1991 года -->

        <div class="grid place-items-center">
          <label for="quantity-input" class="block mb-2"
            >Размер отчислений с 2002 года:
          </label>
          <div class="relative flex items-center max-w-[12rem]">
            <input
              type="number"
              id="pktwo"
              v-model="form.pktwo"
              aria-describedby="helper-text-explanation"
              min="0"
              max="5000000"
              step="1"
              class="bg-gray-50 text-2xl border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full px-4 py-3"
              placeholder="600000"
              required
            />
          </div>
          <p id="helper-text-explanation" class="mt-2 text-lg text-gray-500">
            Сумма страховых взносов в ПФР, начиная с 1 января 2002 года. Можно
            запросить на Госуслугах
          </p>
        </div>

        <div class="text-lg w-full">
          <p>ПК2 = {{ form.pktwo }} руб.</p>

          <p>ПК = {{ PkOne }} + {{ sv }} + {{ form.pktwo }} = {{ pk }} руб.</p>

          <p>СЧ = {{ pk }} / 228 = {{ sch }} руб.</p>

          <p>ИПКс = {{ sch }} / 64,10 = {{ ipks }} руб.</p>
        </div>

        <div class="grid place-items-center">
          <label
            for="quantity-input"
            class="block mb-2 font-medium text-gray-900 text-lg"
            >Год на который считаем пенсию</label
          >
          <div class="relative flex items-center max-w-[12rem]">
            <select
              id="year-select"
              v-model="form.year"
              class="bg-gray-50 border border-gray-300 text-2xl text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
            >
              <option
                v-for="item in set.years"
                :key="item.year"
                :value="item.year"
                :data-value="item.stipk"
              >
                {{ item.year }}
              </option>
            </select>
          </div>

          <p id="helper-text-explanation" class="mt-2 text-lg text-gray-500">
            Необходим для определения стоимости пенсионного коэффициента и
            размера фиксированной надбавки
          </p>
        </div>

        <div class="text-lg w-full">
          <p>Фиксированная доплата - {{ pensYear.fvipl }}</p>
          <p>Цена ИПК - {{ pensYear.stipk }}</p>
        </div>

        <div class="grid place-items-center">
          <label for="quantity-input" class="block mb-2">ИПКн:</label>
          <div class="relative flex items-center max-w-[8rem]">
            <input
              type="number"
              id="ipkn"
              v-model="form.ipkn"
              min="0"
              max="1000"
              step="0.01"
              aria-describedby="helper-text-explanation"
              class="bg-gray-50 text-2xl border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full px-4 py-3"
              placeholder="10"
              required
            />
          </div>
          <p id="helper-text-explanation" class="mt-2 text-lg text-gray-500">
            Он же пенсионный бал с 2015 года. Смотрите размер в выписке из ИЛС
          </p>
        </div>

        <div class="text-lg w-full">
          ИПК = ({{ ipks }}+ {{ form.ipkn }}) * 1 = {{ ipktotal }}
        </div>

        <button
          type="submit"
          class="my-5 inline-flex items-center px-5 py-3 text-2xl font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800"
        >
          Расчитать
        </button>

        <div class="text-lg w-full">
          СПст (Ваша пенсия) = ({{ ipktotal }} * {{ pensYear.stipk }}) +
          {{ pensYear.fvipl }} = {{ spst }}
        </div>
      </form>

      <div
        class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-12"
      >
        <div
          class="flex flex-col mb-8 lg:mb-16 space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4"
        >
          <a
            href="https://www.youtube.com/@advokatmina"
            rel="nofollow noopener noreferrer"
            class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-gray-900 rounded-lg border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-800"
          >
            <svg
              class="mr-2 -ml-1 w-5 h-5"
              fill="currentColor"
              viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"
              ></path>
            </svg>
            смотреть видео
          </a>
        </div>

        <div
          class="px-4 mx-auto text-center md:max-w-screen-md lg:max-w-screen-lg lg:px-36"
        >
          <span class="font-semibold text-gray-400 uppercase"
            >УЗНАТЬ БОЛЬШЕ</span
          >
          <div
            class="flex flex-wrap justify-center items-center mt-8 text-gray-500"
          >
            <a
              href="https://www.youtube.com/@advokatmina"
              rel="nofollow noopener noreferrer"
              target="_blank"
              class="mr-5 mb-5 lg:mb-0 hover:text-gray-800 hover:opacity-80 flex items-center gap-4"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                x="0px"
                y="0px"
                width="48"
                height="48"
                viewBox="0 0 48 48"
              >
                <path
                  fill="#FF3D00"
                  d="M43.2,33.9c-0.4,2.1-2.1,3.7-4.2,4c-3.3,0.5-8.8,1.1-15,1.1c-6.1,0-11.6-0.6-15-1.1c-2.1-0.3-3.8-1.9-4.2-4C4.4,31.6,4,28.2,4,24c0-4.2,0.4-7.6,0.8-9.9c0.4-2.1,2.1-3.7,4.2-4C12.3,9.6,17.8,9,24,9c6.2,0,11.6,0.6,15,1.1c2.1,0.3,3.8,1.9,4.2,4c0.4,2.3,0.9,5.7,0.9,9.9C44,28.2,43.6,31.6,43.2,33.9z"
                ></path>
                <path fill="#FFF" d="M20 31L20 17 32 24z"></path>
              </svg>
              <div
                class="text-2xl font-extrabold tracking-tight leading-none md:text-3xl lg:text-4xl"
              >
                youtube
              </div>
            </a>
          </div>
        </div>
      </div>

      <div class="text-center text-gray-400">
        <link itemprop="url" href="https://nedicom.ru/pension/create" />

        <div>Требования:</div>
        <div
          itemprop="applicationCategory"
          itemscope
          itemtype="PensionCalculator"
        >
          <meta itemprop="name" content="PensionCalculator" />
        </div>

        <div itemprop="browserRequirements">
          Современный браузер с поддержкой JavaScript
        </div>

        <div itemprop="offers" itemscope itemtype="https://schema.org/Offer">
          <span itemprop="priceCurrency" content="RUB">р. </span>
          <span itemprop="price" content="15000">15000</span>
        </div>

        <div itemprop="operatingSystem" content="Web browser">Веб-браузер</div>
      </div>
    </div>
  </Body>

  <MainFooter />
</template>