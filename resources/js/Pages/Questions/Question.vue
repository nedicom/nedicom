<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import Answer from "@/Layouts/Answer.vue";
import AIAnswer from "@/Layouts/AI/AIAnswer.vue";
import Answers from "@/Layouts/Answers.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import ShareButtons from "@/Components/ShareButtons.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { reactive, ref } from "vue";

let set = defineProps({
  question: Object,
  answers: Object,
  authid: Number,
  uslugi: Array,
  auth: Object,
  city: Object,
});

let processing = ref(false);
let isPulse = ref(false);
let cntedt = ref(false);
let newBody = ref(false);

function startPulse() {
  isPulse.value = !isPulse.value;
  setTimeout(() => {
    isPulse.value = !isPulse.value;
  }, 5000);
}

let btnAI = ref();
if (!set.answers[0]) {
  btnAI.value = true;
}

let usluga_id = 0;
if (set.question.usluga != null) {
  usluga_id = set.question.usluga;
}

let form = reactive({
  id: set.question.id,
  body: set.question.abody,
  usluga: usluga_id,
});

const getAIAnswer = () => {
  Inertia.post(route("answer.ia"), form, {
    preserveScroll: true,
    onStart: () => {
      processing.value = true;
    },
    onFinish: () => {
      processing.value = false;
      btnAI.value = false;
    },
  });
};

const setUsluga = () => {
  Inertia.post(route("questions.setusl", [set.question.id]), form, {
    preserveScroll: true,
  });
};

const update = () => {
  if (cntedt.value == true) {
    Inertia.post(
      route("questions.update"),
      { id: set.question.id, body: newBody.value.innerText },
      {
        preserveScroll: true,
      }
    );
  }
};

const childRef = ref(null);

const callChildMethod = () => {
  childRef.value?.open();
};

let percent = ref(25);
let color = ref("bg-red-600");
let message = ref("вопрос нужно дополнить");
percent.value += set.question.shares ? 15 : 0;
percent.value += set.question.uslugis_usl_name ? 15 : 0;
percent.value += set.question.phone ? 15 : 0;
percent.value += set.answers.length > 0 ? 15 : 0;
percent.value += set.question.cities_title ? 15 : 0;

percent.value > 25
  ? ((color.value = "bg-blue-200"), (message.value = "хорошо, но можно лучше"))
  : null;
percent.value > 55
  ? ((color.value = "bg-blue-400"), (message.value = "Вы почти у цели"))
  : null;
percent.value == 100
  ? ((color.value = "bg-blue-600"),
    (message.value = "Отлично, Ваш вопрос самый лучший"))
  : null;
</script>

<template>
  <Head>
    <title>Вопрос юристу - {{ set.question.header }}</title>
    <meta
      name="description"
      :content="set.question.abody.substr(0, 260) + '...'"
    />
    <meta property="og:title" :content="set.question.header" />
    <meta property="og:type" content="website" />
    <meta
      property="og:url"
      :content="'https://nedicom.ru/questions/' + set.question.url"
    />
    <meta property="og:image" content="https://nedicom.ru/logoq.webp" />
  </Head>

  <div class="min-h-screen">
    <MainHeader :auth="set.auth" :city="set.city" ref="childRef" />

    <Header />

    <Body>
      <div itemscope itemtype="https://schema.org/QAPage">
        <div
          class="md:w-2/3 xl:w-1/2 sm:px-6 lg:px-4 py-3 px-3 md:px-0 mx-auto my-3 md:my-12 bg-white overflow-hidden"
          itemprop="mainEntity"
          itemscope
          itemtype="https://schema.org/Question"
        >
          <meta
            itemprop="datePublished"
            :content="set.question.chema_created_at"
          />
          <meta
            v-if="question.user_like"
            itemprop="upvoteCount"
            :content="
              question.user_like +
              question.user_bookmark +
              question.user_share +
              question.comment_count
            "
          />
          <div class="grid grid-cols-2">
            <span class="flex flex-left items-center">
              <img
                v-if="set.question.avatar_path"
                :src="'https://nedicom.ru/' + set.question.avatar_path"
                width="60"
                height="60"
                class="rounded-full ring-2 ring-gray-300"
              />

              <div
                class="text-sm text-gray-900 font-semibold flex flex-col md:flex-row md:items-center ml-3"
              >
                <span
                  v-if="set.question.name"
                  itemprop="author"
                  itemscope
                  itemtype="https://schema.org/Person"
                >
                  <meta
                    itemprop="url"
                    :content="
                      'https://nedicom.ru/lawyers/' + set.question.user_id
                    "
                  />
                  <span itemprop="name" class="truncate">
                    {{ set.question.name }}
                  </span>
                </span>

                <div class="text-gray-700 text-sm md:ml-4">
                  {{ set.question.created_at }}
                </div>
              </div>
            </span>

            <div
              class="mx-4 flex items-center flex-col justify-center md:justify-end md:flex-row"
            >
              <div class="flex">
                <ShareButtons
                  :bundle="set.question"
                  :auth="set.auth"
                  :class="{ 'animate-pulse': isPulse }"
                />

                <!--commets-->
                <a
                  href="#comment"
                  class="flex items-center text-gray-500 hover:text-gray-700"
                >
                  <svg
                    class="w-6 h-6"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    fill="none"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke="currentColor"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 17h6l3 3v-3h2V9h-2M4 4h11v8H9l-3 3v-3H4V4Z"
                    /></svg
                  ><span
                    v-if="set.question.quantity_ans_count > 0"
                    class="text-sm"
                    >{{ set.question.quantity_ans_count }}</span
                  >
                  <span class="text-sm text-gray-500" itemprop="answerCount"
                    ><span v-if="set.answers">{{ set.answers.length }}</span
                    ><span v-else>0</span></span
                  >
                </a>

                <!--commets-->
              </div>
            </div>
          </div>

          <div class="my-2 md:my-5">
            <div class="flex justify-between mt-1">
              <a
                href="#guarantee"
                class="text-base font-medium text-blue-700 block hover:underline"
                >Качество вопроса</a
              >
              <a
                href="#guarantee"
                class="text-base font-medium text-blue-700 block hover:underline"
                >{{ percent }}%</a
              >
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
              <div
                class="h-2.5 rounded-full"
                :class="color"
                :style="{
                  width: percent + '%',
                }"
              ></div>
            </div>
            <a
              href="#guarantee"
              class="text-base font-medium text-blue-700 block text-center w-full hover:underline"
              >{{ message }}</a
            >
          </div>

          <h1
            itemprop="name"
            class="mb-5 mt-5 text-2xl font-bold tracking-tight text-gray-900"
          >
            {{ set.question.header }}
          </h1>

          <div v-if="set.auth">
            <div v-if="set.auth.id == 1 || set.auth.id == set.question.user_id">
              <div v-if="!cntedt">
                <button
                  type="button"
                  @click="update(), (cntedt = !cntedt)"
                  class="text-white my-2 bg-blue-700 hover:bg-blue-800 font-medium rounded-full text-xs px-3 py-1 text-center me-2 mb-2"
                >
                  редактировать
                </button>
              </div>

              <div v-else>
                <button
                  type="button"
                  @click="update(), (cntedt = !cntedt)"
                  class="text-white my-2 bg-green-700 hover:bg-green-800 font-medium rounded-full text-xs px-3 py-1 text-center me-2 mb-2"
                >
                  сохранить
                </button>
                <button
                  type="button"
                  @click="
                    (cntedt = !cntedt), (newBody.innerText = set.question.abody)
                  "
                  class="text-white my-2 bg-red-700 hover:bg-red-800 font-medium rounded-full text-xs px-3 py-1 text-center me-2 mb-2"
                >
                  отменить
                </button>
              </div>
            </div>
          </div>
          <p
            itemprop="text"
            ref="newBody"
            :contenteditable="cntedt"
            class="text-md text-gray-900"
            :class="{ 'border-2 border-indigo-600': cntedt }"
          >
            {{ set.question.abody }}
          </p>

          <div class="flex justify-center mt-10">
            <Answer
              :answerclass="'md:w-4/6 w-full sm:px-6 lg:px-4 mx-5 py-12 bg-white overflow-hidden shadow-sm sm:rounded-lg'"
              :question="set.question"
              :authid="set.authid"
              :type="'question'"
              :article_id="null"
            />
          </div>

          <div
            v-if="set.question.uslugis_url"
            class="text-gray-900 text-center"
          >
            Поискать юриста в категории -
            <h2
              class="text-lg inline font-bold tracking-tight text-gray-900 hover:underline"
            >
              <a
                :href="
                  route('offer.main', [
                    set.question.cities_url
                      ? set.question.cities_url
                      : 'all-cities',
                    set.question.uslugis_url,
                  ])
                "
                ><span v-if="set.question.uslugis_usl_name">{{
                  set.question.uslugis_usl_name
                }}</span>
                <span v-if="set.question.cities_title">
                  - {{ set.question.cities_title }}</span
                >
              </a>
            </h2>
          </div>

          <div class="flex justify-center" id="comment">
            <Answers
              class="sm:px-6 lg:px-4 mx-5 py-12 md:w-4/6 w-full"
              :answers="set.answers"
              :question="set.question"
              :authid="set.authid"
              :type="'question'"
              :article="null"
              :article_id="null"
            />
          </div>

          <p
            class="pb-12 font-medium leading-tight text-2xl text-center"
            id="guarantee"
          >
            Гарантированный ответ на улучшенные вопросы
          </p>

          <div class="px-10">
            <ol class="relative text-gray-500 border-l border-gray-200">
              <!--category-->
              <li class="mb-10 ml-6">
                <span
                  v-if="set.question.uslugis_usl_name"
                  class="absolute flex items-center justify-center w-8 h-8 bg-green-200 rounded-full -left-4 ring-4 ring-white"
                >
                  <svg
                    class="w-3.5 h-3.5 text-green-500 dark:text-green-400"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 16 12"
                  >
                    <path
                      stroke="currentColor"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M1 5.917 5.724 10.5 15 1.5"
                    />
                  </svg>
                </span>
                <span
                  v-else
                  class="absolute flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full -left-4 ring-4 ring-white"
                  >1</span
                >

                <h3 class="font-medium leading-tight text-2xl">
                  Выберите категорию
                </h3>

                <p v-if="set.question.uslugis_usl_name" class="text-sm">
                  Категория определена
                </p>
                <p v-else class="text-sm">
                  Отправим вопрос юристам с нужной специализацией
                </p>

                <div
                  class=""
                  v-if="
                    set.question.user_id == authid ||
                    authid == 1 ||
                    !set.question.uslugis_usl_name
                  "
                >
                  <label
                    class="block mt-5 mb-2 text-sm font-medium text-gray-900"
                  ></label>
                  <select
                    v-model="form.usluga"
                    @change="setUsluga()"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  >
                    <option disabled value="0">
                      Выберите один из вариантов
                    </option>
                    <option
                      v-for="option in uslugi"
                      :key="option.id"
                      :value="option.id"
                    >
                      {{ option.usl_name }}
                    </option>
                  </select>
                </div>
              </li>
              <!--category-->

              <!--region-->
              <li class="mb-10 ml-6">
                <span
                  v-if="set.question.cities_title"
                  class="absolute flex items-center justify-center w-8 h-8 bg-green-200 rounded-full -left-4 ring-4 ring-white"
                >
                  <svg
                    class="w-3.5 h-3.5 text-green-500 dark:text-green-400"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 16 12"
                  >
                    <path
                      stroke="currentColor"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M1 5.917 5.724 10.5 15 1.5"
                    />
                  </svg>
                </span>
                <span
                  v-else
                  class="absolute flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full -left-4 ring-4 ring-white"
                  >2</span
                >
                <h3 class="font-medium leading-tight text-2xl">
                  Выберите регион
                </h3>

                <p v-if="set.question.cities_title" class="text-sm">
                  Регион определен
                </p>
                <p v-else class="text-sm">
                  Привлекайте юристов из нужного региона (только для автора)
                </p>

                <div
                  class="my-5"
                  v-if="set.question.user_id == authid || authid == 1"
                >
                  <button
                    @click="callChildMethod()"
                    type="button"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                  >
                    <span v-if="set.question.cities_title">{{
                      set.question.cities_title
                    }}</span>
                    <span v-else>выберите регион</span>
                  </button>
                </div>
              </li>
              <!--region-->

              <!--answer-->
              <li class="mb-10 ml-6">
                <span
                  v-if="set.answers.length"
                  class="absolute flex items-center justify-center w-8 h-8 bg-green-200 rounded-full -left-4 ring-4 ring-white"
                >
                  <svg
                    class="w-3.5 h-3.5 text-green-500 dark:text-green-400"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 16 12"
                  >
                    <path
                      stroke="currentColor"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M1 5.917 5.724 10.5 15 1.5"
                    />
                  </svg>
                </span>
                <span
                  v-else
                  class="absolute flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full -left-4 ring-4 ring-white"
                  >3</span
                >

                <h3 class="font-medium leading-tight text-2xl">
                  Запросите дежурного юриста
                </h3>
                <p v-if="set.answers.length" class="text-sm">Ответ получен</p>
                <p v-else class="text-sm">Получите ответ прямо сейчас</p>

                <div class="my-5">
                  <AIAnswer v-if="btnAI">
                    <button
                      :disabled="processing"
                      @click="getAIAnswer()"
                      type="button"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                    >
                      <svg
                        v-if="processing"
                        aria-hidden="true"
                        role="status"
                        class="animate-spin inline w-4 h-4 me-3 mr-1 text-gray-200 dark:text-gray-600"
                        viewBox="0 0 100 101"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                          fill="currentColor"
                        />
                        <path
                          d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                          fill="#1C64F2"
                        />
                      </svg>
                      Запросите ответ у дежурного юриста
                    </button>
                  </AIAnswer>
                </div>
              </li>
              <!--answer-->

              <!--profile-->
              <li class="mb-10 ml-6">
                <span
                  v-if="set.question.phone"
                  class="absolute flex items-center justify-center w-8 h-8 bg-green-200 rounded-full -left-4 ring-4 ring-white"
                >
                  <svg
                    class="w-3.5 h-3.5 text-green-500 dark:text-green-400"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 16 12"
                  >
                    <path
                      stroke="currentColor"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M1 5.917 5.724 10.5 15 1.5"
                    />
                  </svg>
                </span>
                <span
                  v-else
                  class="absolute flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full -left-4 ring-4 ring-white"
                  >4</span
                >

                <h3 class="font-medium leading-tight text-2xl">
                  Заполните профиль
                </h3>
                <p v-if="set.question.phone" class="text-sm">
                  Профиль заполнен
                </p>
                <p v-else class="text-sm">Автор вопроса не рассказал о себе</p>

                <div
                  v-if="!set.question.phone && set.question.user_id == authid"
                  class="my-5"
                >
                  <a
                    :href="route('profile.edit')"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 inline-block p-2.5"
                  >
                    <span>Заполнить профиль</span>
                  </a>
                </div>
              </li>
              <!--profile-->

              <!--share-->
              <li class="mb-10 ml-6">
                <span
                  v-if="set.question.user_share"
                  class="absolute flex items-center justify-center w-8 h-8 bg-green-200 rounded-full -left-4 ring-4 ring-white"
                >
                  <svg
                    class="w-3.5 h-3.5 text-green-500 dark:text-green-400"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 16 12"
                  >
                    <path
                      stroke="currentColor"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M1 5.917 5.724 10.5 15 1.5"
                    />
                  </svg>
                </span>
                <span
                  v-else
                  class="absolute flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full -left-4 ring-4 ring-white"
                  >5</span
                >

                <h3 class="font-medium leading-tight text-2xl">Поделитесь</h3>
                <p v-if="set.question.user_share" class="text-sm">
                  Кто-то уже поделился
                </p>
                <p v-else class="text-sm">
                  Расскажите другим про Ваш вопрос и наш сервис (только для
                  авторизованных)
                </p>

                <div class="py-5">
                  <a
                    href="#"
                    type="button"
                    @click="startPulse()"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 inline-block p-2.5"
                  >
                    <span v-if="set.question.user_share">Еще поделиться</span>
                    <span v-else>Поделиться</span>
                  </a>
                </div>

                <span
                  v-if="
                    set.question.user_share &&
                    set.question.uslugis_usl_name &&
                    set.question.phone &&
                    set.answers.length &&
                    set.question.cities_title
                  "
                  class="absolute flex items-center justify-center w-8 h-8 bg-green-200 rounded-full -left-4 ring-4 ring-white"
                >
                  <svg
                    class="w-3.5 h-3.5 text-green-500 dark:text-green-400"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 16 12"
                  >
                    <path
                      stroke="currentColor"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M1 5.917 5.724 10.5 15 1.5"
                    />
                  </svg>
                </span>

                <span
                  v-else
                  class="absolute flex items-center justify-center w-10 h-10 bg-gray-100 rounded-full -left-5 ring-4 ring-white"
                >
                  <svg
                    class="w-4 h-4 text-gray-500 lg:w-5 lg:h-5 dark:text-gray-100"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    viewBox="0 0 18 20"
                  >
                    <path
                      d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2ZM7 2h4v3H7V2Zm5.7 8.289-3.975 3.857a1 1 0 0 1-1.393 0L5.3 12.182a1.002 1.002 0 1 1 1.4-1.436l1.328 1.289 3.28-3.181a1 1 0 1 1 1.392 1.435Z"
                    />
                  </svg>
                </span>
              </li>
            </ol>
          </div>
        </div>
      </div>
    </Body>

    <MainFooter />
  </div>
</template>
