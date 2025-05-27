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
  }
  else{
    buttonDisabled.value = false;
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
                  <div
                    class="my-1 w-full bg-gray-200 rounded-full h-1 dark:bg-gray-700"
                  >
                    <div
                      class="bg-blue-600 h-1 rounded-full"
                      :style="{
                        width: progresswidth + '%',
                      }"
                    ></div>
                  </div>
                  <p class="text-xs text-gray-900 dark:text-white">
                    Символов: {{ wordscounter }}
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
        </div>
      </div>
    </div>
  </Body>

  <SliderQuestions :sliderq="data" />

  <MainFooter />
</template>
