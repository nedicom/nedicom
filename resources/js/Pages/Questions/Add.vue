<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import SendButton from "@/Components/SendButton.vue";
import SliderQuestions from "@/Layouts/SliderQuestions.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { reactive } from "vue";
import VueWriter from "vue-writer";

let form = reactive({
  header: "",
  body: "",
});

let set = defineProps({
  lawyers: Object,
  SliderQ: Array,
  auth: Object,
  city: Object,
  hasquestion: Boolean,
});

const data = ref(set.SliderQ);
const buttonDisabled = ref(true);
let progresswidth = ref(0);
let wordscounter = ref(0);
const progressColor = ref({
  color: "bg-red-600",
  width: "Начинайте писать",
});

let arr = [
  "юристы, которые точно помогут",
  "не сольют Ваши данные колл-центрам",
  "ответят на вопрос бесплатно",
  "без телефона и смс",
];

function onInputheader(e) {
  wordscounter.value = e.target.value.length;
  if (e.target.value.length <= 100) {
    progresswidth.value = e.target.value.length;
    buttonDisabled.value = true;
    progressColor.value.color = "bg-red-600";
    progressColor.value.width = "Вопрос еще слишком короткий";
  } else {
    buttonDisabled.value = false;
    progressColor.value.color = "bg-blue-600";
    progressColor.value.width =
      "Вопрос уже можно публиковать, но Вы можете рассказать подробнее";
  }
}

let submit = () => {
  buttonDisabled.value = true;
  Inertia.post("/questions/post", form);
};

let flag = 0; //cheker func once
let words = [];

//get similar question
const getQuestions = () => {
  let wordArray = form.header.split(" "); //get words from title onBlur
  wordArray.forEach((item) => {
    //sort trough
    if (item.length > 6 && flag < 2) {
      //sort trough when letter in words more than six
      flag++;
      words.push(item.slice(0, -2)); //trim ending of a word
    }
  });

  async function asyncCall() {
    if (flag === 2) {
      flag++;
      const response = await fetch(`/questions/similar/` + words.toString());
      if (response.ok) {
        data.value = await response.json();
      }
    }
  }
  asyncCall();
};
</script>

<template>
  <Head>
    <title>
      Вопрос юристу бесплатно - юрист онлайн, без телефона, консультация
    </title>
    <meta
      name="description"
      content="Консультация юриста и вопрос юристу онлайн, бесплатно, без телефона и смс."
    />
    <meta
      property="og:title"
      content="Вопрос юристу бесплатно - юрист онлайн, без телефона, консультация"
    />
    <meta
      property="og:description"
      content="Консультация юриста и вопрос юристу онлайн, бесплатно, без телефона и смс."
    />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://nedicom.ru/questions/add" />
    <meta property="og:site_name" content="nedicom.ru" />
    <meta property="og:locale" content="ru_RU" />
  </Head>

  <MainHeader :auth="set.auth" :city="set.city" />

  <Body>
    <div class="bg-white py-6" itemscope itemtype="https://schema.org/WebSite">
      <link itemprop="url" href="http://nedicom.ru/" />
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="flex flex-col items-center md:mt-10">
            <h1
              class="text-center mx-5 pb-6 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white"
            >
              Задайте вопрос юристу онлайн бесплатно
            </h1>

            <div class="flex -space-x-2 overflow-hidden pb-6">
              <img
                v-for="value in set.lawyers"
                :key="value"
                class="inline-block h-10 w-10 rounded-full ring-2 ring-white"
                :src="'https://nedicom.ru/' + value.avatar_path"
                alt="вопрос юристу онлайн бесплатно"
                width="40"
                height="40"
              />
              <a
                class="flex items-center justify-center w-10 h-10 text-xs font-medium text-white bg-gray-700 border-2 border-white rounded-full hover:bg-gray-600 dark:border-gray-800"
                href="#"
                >+27</a
              >
            </div>
            <h2 class="text-center mx-5 md:pb-6 font-semibold tracking-tight">
              nedicom.ru - сервис поиска юристов и ответов на юридические
              вопросы
            </h2>
            <h2
              class="text-center mx-5 md:pb-6 font-semibold tracking-tight text-gray-900 dark:text-white"
            >
              <vue-writer
                :array="arr"
                :eraseSpeed="20"
                :typeSpeed="50"
                :iterations="1"
              />
            </h2>
          </div>

          <div v-if="!set.hasquestion">
            <form
              @submit.prevent="submit"
              class="p-5 flex justify-center"
              itemprop="potentialAction"
              itemscope
              itemtype="https://schema.org/SearchAction"
            >
              <div class="grid grid-cols-1 place-items-center w-full xl:w-3/4">
                <div class="mb-3 w-full col-span-2">
                  <meta
                    itemprop="target"
                    :content="
                      'http://nedicom.ru/questions/similar/' + words.toString()
                    "
                  />
                  <textarea
                    itemprop="query"
                    :onBlur="getQuestions"
                    v-model="form.header"
                    @input="onInputheader($event)"
                    maxlength="1155"
                    name="header"
                    required
                    class="p-5 form-control text-xl block w-full font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                    id=""
                    rows="8"
                    autofocus
                    placeholder="Спрашивайте, юрист ответит обязательно..."
                  ></textarea>
                  <div class="my-1 w-full bg-gray-200 rounded-full h-1">
                    <div
                      class="h-1 rounded-full"
                      :class="progressColor.color"
                      :style="{
                        width: progresswidth + '%',
                      }"
                    ></div>
                  </div>
                  <p class="text-xs text-gray-900">
                    Символов: {{ wordscounter }} <br />{{ progressColor.width }}
                  </p>

                  <div class="text-center items-center">
                    <div>
                      <div class="flex items-start my-5">
                        <div class="flex items-center h-5">
                          <input
                            id="remember"
                            type="checkbox"
                            class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300"
                            required
                          />
                        </div>
                        <label
                          for="remember"
                          class="ml-2 text-sm font-medium bg-white rounded-lg px-1 text-gray-900"
                          >Если таковые содержаться в вопросе, даю согласие на
                          обработку
                          <Link
                            href="/policy"
                            class="underline text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                          >
                            персональных данных
                          </Link>
                        </label>
                      </div>
                      <div class="flex items-start my-5">
                        <div class="flex items-center h-5">
                          <input
                            id="remember"
                            type="checkbox"
                            class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800"
                            required
                          />
                        </div>
                        <label
                          for="remember"
                          class="ml-2 text-sm font-medium bg-white rounded-lg px-1 text-gray-900"
                          >Мне есть 18 лет
                        </label>
                      </div>
                    </div>

                    <SendButton
                      class="m-5"
                      id="SendButton"
                      :disabled="buttonDisabled"
                    >
                      задать вопрос</SendButton
                    >
                  </div>
                </div>
              </div>
            </form>
          </div>

          <div v-else class="my-20">
            <h2 class="text-4xl font-semibold text-gray text-center py-10">
              Один день - один вопрос
            </h2>
            <p class="text-gray-500 sm:text-xl text-center">
              Мы разрешаем публиковать в день не больше одного вопроса, а Вы
              свой уже задавали.
            </p>
            <p class="text-gray-500 sm:text-xl text-center">
              Посмотрите
              <a
                :href="route('questions.url', [set.hasquestion.url])"
                class="text-blue-500 hover:underline"
                >Ваш вопрос</a
              >.
            </p>
          </div>

          <div class="bg-white p-8 mx-auto my-10">
            <!-- Заголовок с УТП -->
            <div class="text-center">
              <h1 class="text-3xl font-bold text-gray-900 mb-3">
                Хотите
                <span class="text-blue-600">
                  профессиональную платнаю консультация</span
                >
                юриста?
              </h1>
              <h1 class="text-3xl font-bold text-gray-900 mb-3">
                Цена
                <span class="text-blue-600"> 2000</span> рублей
              </h1>
              <p class="text-lg text-gray-600">
                Быстрое решение сложных вопросов с гарантией конфиденциальности
              </p>
            </div>

            <div class="w-full flex justify-center my-12">
            <a
              href="https://t.me/advokatmina"
              class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-md flex text-center items-center w-64"
            >
              Напишите в телеграм чтобы заказать консультацию
            </a>
            </div>

            <!-- Блок преимуществ -->
            <div class="grid md:grid-cols-3 gap-4 mb-8">
              <div class="bg-blue-50 p-4 rounded-lg text-center">
                <svg
                  class="w-10 h-10 mx-auto mb-2 text-blue-600"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                  ></path>
                </svg>
                <h3 class="font-semibold text-gray-800">Гарантия результата</h3>
                <p class="text-sm text-gray-600">Чёткий план действий</p>
              </div>
              <div class="bg-blue-50 p-4 rounded-lg text-center">
                <svg
                  class="w-10 h-10 mx-auto mb-2 text-blue-600"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                  ></path>
                </svg>
                <h3 class="font-semibold text-gray-800">Экономия времени</h3>
                <p class="text-sm text-gray-600">Ответ за 15 минут</p>
              </div>
              <div class="bg-blue-50 p-4 rounded-lg text-center">
                <svg
                  class="w-10 h-10 mx-auto mb-2 text-blue-600"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                  ></path>
                </svg>
                <h3 class="font-semibold text-gray-800">Опыт 10+ лет</h3>
                <p class="text-sm text-gray-600">
                  Специализация по вашему вопросу
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Body>

  <SliderQuestions :sliderq="data" />

  <MainFooter />
</template>
