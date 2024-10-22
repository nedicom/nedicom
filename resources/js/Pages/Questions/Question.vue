<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import Answer from "@/Layouts/Answer.vue";
import AIAnswer from "@/Layouts/AI/AIAnswer.vue";
import Answers from "@/Layouts/Answers.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { reactive, ref } from "vue";

let set = defineProps({
  question: "Object",
  answers: "Object",
  authid: "Number",
  uslugi: "Number",
});

let processing = ref(false);

let btnAI = ref();
if (!set.answers[0]) {
  btnAI.value = true;
}

let usluga_id = 0
if (set.question.usluga != null) {
  usluga_id = set.question.usluga.id;
}

let form = reactive({
  id: set.question.id,
  body: set.question.body,
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
</script>

<template>

  <Head>
    <title>{{ set.question.title }}</title>
    <meta name="description" :content="set.question.body" />
    <meta property="og:title" :content="set.question.title" />
    <meta property="og:type" :content="website" />
    <meta property="og:url" :content="'https://nedicom.ru/questions/' + set.question.url" />
    <meta property="og:image" content="https://nedicom.ru/logoq.webp" />
  </Head>

  <div class="min-h-screen">
    <MainHeader />

    <Header />

    <Body>
      <div itemscope itemtype="https://schema.org/QAPage">
        <div class="xl:w-1/2 sm:px-6 lg:px-4 px-3 md:px-0 mx-auto my-12 bg-white overflow-hidden shadow-sm sm:rounded-lg"
          itemprop="mainEntity" itemscope itemtype="https://schema.org/Question">
          <meta itemprop="datePublished" :content="set.question.updated_at" />

          <div class="grid grid-cols-2">

            <span class="flex flex-right">
              <img v-if="set.question.user" :src="'https://nedicom.ru/' + set.question.user.avatar_path" width="60"
                class="rounded-full m-3 p-1 ring-2 ring-gray-300 dark:ring-gray-500" />
              <p class="mr-3 text-sm text-gray-900 dark:text-white font-semibold h-min-24 flex items-center">
                <span v-if="set.question.user" itemprop="author" itemscope itemtype="https://schema.org/Person">
                  <meta itemprop="url" content="https://nedicom.ru/uslugi" />
                  <span itemprop="name">
                    {{ set.question.user.name }}
                  </span>
                </span>
              </p>
            </span>

            <div class="mx-4 flex items-center flex-col justify-center md:justify-end md:flex-row ">


              <div class="text-gray-400 text-xs md:mr-4 my-2">
                {{ set.question.created_at }}
              </div>

              <div class="md:mr-4 my-2">
                <span
                  class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400">ответов
                  - <span itemprop="answerCount">{{ set.question.quantity_ans_count }}</span></span>
              </div>
            </div>
          </div>


          <h1 itemprop="name" class="mb-5 mt-5 text-2xl font-bold tracking-tight text-gray-900">
            {{ set.question.title }}
          </h1>

          <span v-if="set.question.usluga" class="text-gray-900"> поискать юриста для ответа в категории
            <h2 class="text-lg inline font-bold tracking-tight text-gray-900">
              <a :href="route('offer.main', ['simferopol', set.question.usluga.url])">{{ set.question.usluga.usl_name }}
                в городе Симферополь</a>
            </h2>
            ?
          </span>

          <p itemprop="text" class="mt-5 text-md text-gray-900">
            {{ set.question.body }}
          </p>


          <div class="flex justify-center">
            <Answer
              :answerclass="'md:w-4/6 w-full sm:px-6 lg:px-4 mx-5 py-12 bg-white overflow-hidden shadow-sm sm:rounded-lg'"
              :question="set.question" :authid="set.authid" />
          </div>



          <p class="font-extrabold">
            Получите ответ быстрее!
          </p>

          <div class="md:flex justify-start">

            <div class="mr-3 my-5 md:my-0" v-if="usluga_id == 0 || authid == 1">
              <label class="block mt-5 mb-2 text-sm font-medium text-gray-900 dark:text-white">Выберите
                категорию</label>
              <select v-model="form.usluga" @change="setUsluga()"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option disabled value="0">Выберите один из вариантов</option>
                <option v-for="option in uslugi" :key="option.id" :value="option.id">
                  {{ option.usl_name }}
                </option>
              </select>
            </div>

            <div class="mr-3 my-5 md:my-0">
              <AIAnswer v-if="btnAI">
                <button :disabled="processing" @click="getAIAnswer()" type="button"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                  <svg v-if="processing" aria-hidden="true" role="status"
                    class="animate-spin inline w-4 h-4 me-3 mr-1 text-gray-200 dark:text-gray-600" viewBox="0 0 100 101"
                    fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                      fill="currentColor" />
                    <path
                      d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                      fill="#1C64F2" />
                  </svg>
                  Запросите ответ у дежурного юриста
                </button>
              </AIAnswer>
            </div>

            <div class="mr-2 my-5 md:my-0">
              <label class="block mt-5 mb-2 text-sm font-medium text-gray-900 dark:text-white">Поделиться
              </label>

              <a type="button"
                :href="'https://vk.com/share.php?url=https://nedicom.ru/questions/' + set.question.url + '&image=https://nedicom.ru/logoq.webp&title=Я пользуюсь nedicom.ru. Здесь юристы ответят на любой вопрос!'"
                target="_blank"
                class="mr-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 inline-flex items-center">
                <svg class="w-4 h-4 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                  viewBox="0 0 24 24">
                  <path
                    d="M15.07 2H8.93C3.33 2 2 3.33 2 8.93v6.14C2 20.67 3.33 22 8.93 22h6.14c5.6 0 6.93-1.33 6.93-6.93V8.93C22 3.33 20.67 2 15.07 2m3.08 14.27h-1.46c-.55 0-.72-.45-1.69-1.44c-.88-.83-1.26-.95-1.47-.95c-.29 0-.38.08-.38.5v1.31c0 .35-.11.57-1.04.57c-1.54 0-3.25-.94-4.45-2.67c-1.81-2.54-2.3-4.46-2.3-4.84c0-.21.07-.41.49-.41h1.47c.37 0 .51.16.65.56c.72 2.1 1.92 3.9 2.41 3.9c.19 0 .27-.09.27-.55V10.1c-.05-.98-.58-1.07-.58-1.42c0-.18.14-.34.37-.34h2.29c.31 0 .42.16.42.54v2.89c0 .31.13.42.23.42c.18 0 .34-.11.67-.45c1.05-1.17 1.8-2.98 1.8-2.98c.1-.21.26-.41.65-.41h1.43c.44 0 .54.23.44.54c-.18.85-1.96 3.36-1.94 3.36c-.16.25-.22.36 0 .65c.15.21.66.65 1 1.04c.62.71 1.1 1.3 1.23 1.71c.11.41-.09.62-.51.62z"
                    fill="#c9c9c9" />
                </svg> ВК
              </a>


              <a type="button"
                :href="'https://connect.ok.ru/offer?url=https://nedicom.ru/questions/' + set.question.url + '&title=Я пользуюсь nedicom.ru. Здесь юристы ответят на любой вопрос! &imageUrl=https://nedicom.ru/logoq.webp'"
                target="_blank"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" class="w-4 h-4 mr-2" viewBox="0 0 48 48">
                  <path fill="#FF9800"
                    d="M42,37c0,2.8-2.2,5-5,5H11c-2.8,0-5-2.2-5-5V11c0-2.8,2.2-5,5-5h26c2.8,0,5,2.2,5,5V37z"></path>
                  <path fill="#FFF"
                    d="M26.9,30.4c1.5-0.3,2.9-0.9,4.1-1.7c1-0.6,1.3-1.9,0.7-2.9c-0.6-1-1.9-1.3-2.9-0.7c-2.9,1.8-6.7,1.8-9.6,0c-1-0.6-2.3-0.3-2.9,0.7c-0.6,1-0.3,2.3,0.7,2.9c1.3,0.8,2.7,1.4,4.1,1.7l-4,4c-0.8,0.8-0.8,2.1,0,3c0.4,0.4,0.9,0.6,1.5,0.6c0.5,0,1.1-0.2,1.5-0.6l3.9-3.9l3.9,3.9c0.8,0.8,2.1,0.8,3,0c0.8-0.8,0.8-2.1,0-3C30.9,34.4,26.9,30.4,26.9,30.4z M24,10c-3.9,0-7,3.1-7,7c0,3.9,3.1,7,7,7c3.9,0,7-3.1,7-7C31,13.1,27.9,10,24,10z M24,20c-1.7,0-3-1.3-3-3c0-1.7,1.3-3,3-3c1.7,0,3,1.3,3,3C27,18.7,25.7,20,24,20z">
                  </path>
                </svg>OK
              </a>

            </div>



          </div>

          <p class="font-extrabold mt-10">
            Что самое эффективное!? (доступно только авторам)
          </p>

          <div class="my-5 md:my-0">
              <label class="block mt-5 mb-5 md:mb-2 text-sm font-medium text-gray-900 dark:text-white">Конечно - позвать юриста! </label>
              <label class="md:w-1/2 block mb-5 md:mb-2 text-xs font-medium text-gray-900 dark:text-white">Сейчас мы работаем в двух регионах. Перейдите по ссылке и нажимте на кнопку <span class="font-bold">"запросить ответ"</span> напротив фото юриста. Мы отправим юристу уведомление.</label>
              <a type="button"
                href="https://nedicom.ru/uslugi/simferopol"
                target="_blank"
                class="mr-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 inline-flex items-center">
                Юристы из Крыма
              </a>
              <a type="button"
                href="https://nedicom.ru/uslugi/moscow"
                target="_blank"
                class="mr-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 inline-flex items-center">
                Юристы их Москвы
              </a>
            </div>

          <div class="flex justify-center">
            <Answers class="sm:px-6 lg:px-4 mx-5 py-12 md:w-4/6 w-full" :answers="set.answers"
              :question="set.question.id" :authid="set.authid" />
          </div>

        </div>
      </div>
    </Body>

    <MainFooter />
  </div>
</template>
