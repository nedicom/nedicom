<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import AIAnswer from "@/Layouts/AI/AIAnswer.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import { Inertia } from "@inertiajs/inertia";
import { Head, usePage, useForm } from "@inertiajs/inertia-vue3";
import SendButton from "@/Components/SendButton.vue";
import Question from "@/Components/Question.vue";

let set = defineProps({
  ownercookie: Object,
  test: String,
  auth: Object,
});

let aianswer;

if (set.auth == null) {
  aianswer =
    "Я готов ответить на Ваш вопрос, но сначала, его нужно опубликовать. ";
} else {
  aianswer = "Вы почти у цели. Нажмите на кнопку опубликовать";
}

const form = useForm({
  header: set.ownercookie.questionTitle,
  body: set.ownercookie.questionBody,
});

let submit = () => {
  Inertia.post("/questions/post", form);
};
</script>

<template>
  <Head>
    <title>Проверка вопроса юристу перед публикацией</title>
    <meta
      name="description"
      content="Здесь пользователи проверяют вопрос юристу перед публикацией."
    />
  </Head>

  <div class="min-h-screen">
    <MainHeader :auth="set.auth" />

    <Body>
      <Question :user="set.auth">
        <template v-slot:title>
          {{ ownercookie.questionTitle }}
        </template>
        <template v-slot:body>
          {{ ownercookie.questionBody }}
        </template>
      </Question>

      <div class="flex justify-between px-4 mx-auto max-w-screen-xl">
        <article
          class="mx-auto w-full max-w-2xl format format-sm sm:format-base lg:format-lg format-blue dark:format-invert"
        >
          <SendButton class="" :onclick="submit" :disabled="!set.auth"
            >Опубликовать</SendButton
          >
        </article>
      </div>

      <div class="flex justify-center">
        <div
          class="md:w-4/6 mx-5 bg-white overflow-hidden shadow-sm sm:rounded-lg"
        >
          <div class="grid grid-cols-1 gap-9 my-2">
            <div class="flex justify-center">
              <div class="block min-w-full p-6 bg-white max-w-sm">
                <AIAnswer class="sm:px-6 lg:px-4 mx-5">{{ aianswer }}
                </AIAnswer>
              </div>
            </div>
            <!-- card -->
          </div>
        </div>
      </div>
    </Body>

    <MainFooter />
  </div>
</template>
