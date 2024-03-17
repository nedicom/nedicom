<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import Answer from "@/Layouts/Answer.vue";
import AIAnswer from "@/Layouts/AI/AIAnswer.vue";
import Answers from "@/Layouts/Answers.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { reactive, ref } from "vue";

let set = defineProps({
  question: "Object",
  answers: "Object",
  authid: "Number",
});

let processing = ref(false);

let btnAI = ref();
if (!set.answers[0]) {
  btnAI.value = true;
}
let form = reactive({
  id: set.question.id,
  body: set.question.body,
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
</script>

<template>

  <Head>
    <title>{{ set.question.title }}</title>
    <meta name="description" :content="set.question.body" />
  </Head>

  <div class="min-h-screen">
    <MainHeader />

    <Header :ttl="set.question.title" />

    <Body>
      <div class="xl:w-1/2 sm:px-6 lg:px-4 mx-auto my-12 bg-white overflow-hidden shadow-sm sm:rounded-lg" itemscope
        itemtype="https://schema.org/QAPage">

        <div itemprop="mainEntity" itemscope itemtype="https://schema.org/Question">
          <div class="grid grid-cols-2">

            <span class="flex flex-right">
              <img v-if="set.question.user" :src="'https://nedicom.ru/' + set.question.user.avatar_path" width="60"
                class="rounded-full m-3 p-1 ring-2 ring-gray-300 dark:ring-gray-500" />
              <p class="mr-3 text-sm text-gray-900 dark:text-white font-semibold h-min-24 flex items-center">
                <span v-if="set.question.user" itemprop="author" itemscope itemtype="https://schema.org/Person">
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


          <div class="my-8 mx-8 md:mx-5">
            <h5 itemprop="name" class="mb-2 text-2xl font-bold tracking-tight text-gray-900">
              {{ set.question.title }}
            </h5>

            <p itemprop="text" class="text-md text-gray-900">
              {{ set.question.body }}
            </p>
          </div>

        </div>

        <div class="flex justify-center">
          <Answer
            :answerclass="'md:w-4/6 w-full sm:px-6 lg:px-4 mx-5 py-12 bg-white overflow-hidden shadow-sm sm:rounded-lg'"
            :question="set.question" :authid="set.authid" />
        </div>

        <AIAnswer v-if="btnAI" class="sm:px-6 lg:px-4 mx-5 py-12">
          <button :disabled="processing" @click="getAIAnswer()" type="button"
            class="py-2.5 px-3 mt-4 mb-6 me-2 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 inline-flex items-center">
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
            Запросить ответ...
          </button>
        </AIAnswer>

        <div class="flex justify-center">
          <Answers class="sm:px-6 lg:px-4 mx-5 py-12 md:w-4/6 w-full" :answers="answers" :question="set.question.id"
            :authid="set.authid" />
        </div>

      </div>
    </Body>

    <MainFooter />
  </div>
</template>
