<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import { ref, computed } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { reactive } from "vue";

let set = defineProps({
  auth: Object,
  pensionData: Object,
  errors: Object,
});

const isChecked = ref(false); // Начальное состояние

const logCheckboxState = () => {
  console.log("Чекбокс:", isChecked.value ? "ON" : "OFF");
};

let form = reactive({
  gender: set.pensionData.gender ? set.pensionData.gender : 20,
  stagh2002: set.pensionData.stagh2002,
  stagh1991: set.pensionData.stagh1991,
  zp: set.pensionData.zp,
  szp: set.pensionData.szp,
  sv: set.pensionData.sv,
  pktwo: set.pensionData.pktwo,
  /*
  stazgh: 20,
  punkt: 0,
  pns: 0,
  t: 0,
  kv: 0,
  pk: 0,
  pk1: 0,
  pk2: 0,
  spk: 0,
  ipkn: 0,
  ipks: 0,
  ipk: 0,
  spst: 0,
  propzp: 0,
  rp: 0,
  sv: 0,
  p: 0,
  nadb: 0,
  pension: 0
  */
});

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
  return form.szp ? Math.round(szp.value * sk.value * 1671.0 * 100) / 100 : 0;
});

const PkOne = computed(() => {
  return form.szp
    ? Math.round((rp.value - 450) * pns.value * 252 * 5.61481656 * 100) / 100
    : 0;
});

const sv = computed(() => {
  return form.szp
    ? Math.round(PkOne.value * (0.1 + form.stagh1991 / 100) * 100) / 100
    : 0;
});

const ipk = computed(() => {
  return form.ipkn
    ? Math.round((form.ipkn + form.ipkn) * 100) / 100
    : 0;
});

let submit = () => {
  Inertia.post("/pension", form);
};
</script>

<template>
  <Head title="Пенисонный калькулятор Марка Мины" />

  <MainHeader :auth="auth" />

  <Body>
    <div class="bg-white py-12 min-h-screen max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div itemscope itemtype="https://schema.org/WebApplication">
        <h1 itemprop="name">Пенсионный калькулятор Марка Мины</h1>
        <link itemprop="url" href="https://nedicom.ru/pension/create" />
        <div itemprop="description">
          Калькулятор пенсии с возможностью расчета ИПК за прошлый период
        </div>

        <div
          itemprop="applicationCategory"
          itemscope
          itemtype="PensionCalculator"
        >
          <meta itemprop="name" content="PensionCalculator" />
        </div>

        <div itemprop="browserRequirements">
          Требуется современный браузер с поддержкой JavaScript
        </div>

        <div itemprop="offers" itemscope itemtype="https://schema.org/Offer">
          <span itemprop="priceCurrency" content="RUB">$</span>
          <span itemprop="price" content="500">500</span>
        </div>

        <div itemprop="operatingSystem" content="Web browser">
          Платформа: Веб-браузер
        </div>
      </div>
      {{ pns }}
      {{ set.pensionData }}
      {{ form }}
      {{ set.errors }}
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
        <div class="text-lg">
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

        <div class="text-lg">
          <p>Пропорция неполного стажа (ПНС) = {{ pns }}</p>
          <p>Стажевый коэффициент (СК) = {{ sk }}</p>
        </div>
        <!-- стаж до 2002 года -->

        <!-- справка о зарплате -->
        <div class="grid place-items-center">
          <label class="inline-flex items-center cursor-pointer">
            <input
              type="checkbox"
              value=""
              v-model="isChecked"
              @change="logCheckboxState"
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

        <div class="text-lg">
          <p>Влияет на формулу расчета ст. 30 ФЗ 17</p>
          <p>РП = {{ sk }} * {{ szp }} * 1 671.00 руб. = {{ rp }} руб.</p>
          <p>
            ПК1 = ({{ rp }} - 450) * {{ pns }} * 252 * 5.61481656 =
            {{ PkOne }} руб.
          </p>
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
              <label for="quantity-input" class="block mb-2"
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
              <label for="quantity-input" class="block mb-2"
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
          <div v-if="isChecked" class="text-lg">
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

        <div class="text-lg">
          <p>сумма валоризации = {{ sv }}</p>
          <p>{{PkOne}} * (10% + {{form.stagh1991 }} %) = {{ sv }}</p>
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

        <div class="text-lg">ПК2 = {{ form.pktwo }} руб.</div>

        <div class="grid place-items-center">
          <label for="quantity-input" class="block mb-2"
            >Год на который считаем пенсию</label
          >
          <div class="relative flex items-center max-w-[12rem]">
            <select
              id="year"
              class="bg-gray-50 text-2xl border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full px-4 py-3"
              required
            >
              <option selected>2025</option>
            </select>
          </div>
          <p id="helper-text-explanation" class="mt-2 text-lg text-gray-500">
            Необходим для определения стоимости пенсионного коэффициента и
            размера фиксированной надбавки
          </p>
        </div>

        <div class="text-lg">Влияет на пропорцию неполного стажа</div>

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

        <div class="text-lg">ИПК = (127,960 + {{ form.ipkn }}) * 1 = {{ ipk }}</div>

        <button
          type="submit"
          class="my-5 inline-flex items-center px-5 py-3 text-2xl font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800"
        >
          Расчитать
        </button>
      </form>
    </div>
  </Body>

  <MainFooter />
</template>