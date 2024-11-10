<script setup>
import { ref } from "vue";

let set = defineProps({
  lawyer: "$string",
});
let postprogresswidth = set.lawyer.pub_rating * 10;
let questprogresswidth = set.lawyer.question_rating * 10;
let answerprogresswidth = set.lawyer.answer_rating * 10;
let practiceprogresswidth = set.lawyer.practice_rating * 10;

let total_rating;

if (set.lawyer.total_rating < 2.0) {
  total_rating = "Плоховато";
} else if (set.lawyer.total_rating < 5.0 && set.lawyer.total_rating > 2.0) {
  total_rating = "Неплохо";
} else if (set.lawyer.total_rating < 8.0 && set.lawyer.total_rating > 5.0) {
  total_rating = "Хорошо";
} else {
  total_rating = "Отлично";
}
</script>

<template>
  <h2 class="text-4xl font-semibold text-grey text-center py-10">
    Рейтинг юриста
  </h2>
  <div class="w-full flex justify-center mb-5">
    <div class="md:w-1/2">
      <div class="flex items-center mb-5">
        <p
          class="bg-blue-100 text-blue-800 text-sm font-semibold inline-flex items-center p-1.5 rounded dark:bg-blue-200 dark:text-blue-800"
        >
          {{ set.lawyer.total_rating }}
        </p>
        <p class="mx-2 font-medium text-gray-900 dark:text-white">
          {{ total_rating }}
        </p>
        <span
          class="w-1 h-1 mx-2 bg-gray-900 rounded-full dark:bg-gray-500"
        ></span>

        <a
          href="#"
          class="ms-auto text-sm ml-3 font-medium text-blue-600 hover:underline dark:text-blue-500"
          >Как считается рейтинг</a
        >
      </div>
      <div class="gap-8 sm:grid sm:grid-cols-2">
        <div>
          <dl>
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
              Публикации
            </dt>
            <dd class="flex items-center mb-3">
              <div
                class="w-full bg-gray-200 rounded h-2.5 dark:bg-gray-700 me-2"
              >
                <div
                  class="bg-blue-600 h-2.5 rounded dark:bg-blue-500"
                  :style="{
                    width: postprogresswidth + '%',
                  }"
                ></div>
              </div>
              <span
                class="text-sm font-medium ml-1 text-gray-500 dark:text-gray-400"
                >{{ set.lawyer.pub_rating }}</span
              >
            </dd>
          </dl>
          <dl>
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
              Практика
            </dt>
            <dd class="flex items-center mb-3">
              <div
                class="w-full bg-gray-200 rounded h-2.5 dark:bg-gray-700 me-2"
              >
                <div
                  class="bg-blue-600 h-2.5 rounded dark:bg-blue-500"
                  :style="{
                    width: practiceprogresswidth + '%',
                  }"
                ></div>
              </div>
              <span
                class="text-sm font-medium ml-1 text-gray-500 dark:text-gray-400"
                >{{ set.lawyer.practice_rating }}</span
              >
            </dd>
          </dl>
        </div>
        <div>
          <dl>
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
              Вопросы
            </dt>
            <dd class="flex items-center mb-3">
              <div
                class="w-full bg-gray-200 rounded h-2.5 dark:bg-gray-700 me-2"
              >
                <div
                  class="bg-blue-600 h-2.5 rounded dark:bg-blue-500"
                  :style="{
                    width: questprogresswidth + '%',
                  }"
                ></div>
              </div>
              <span
                class="text-sm font-medium ml-1 text-gray-500 dark:text-gray-400"
                >{{ set.lawyer.question_rating }}</span
              >
            </dd>
          </dl>
          <dl>
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
              Ответы на вопросы
            </dt>
            <dd class="flex items-center">
              <div
                class="w-full bg-gray-200 rounded h-2.5 dark:bg-gray-700 me-2"
              >
                <div
                  class="bg-blue-600 h-2.5 rounded dark:bg-blue-500"
                  :style="{
                    width: answerprogresswidth + '%',
                  }"
                ></div>
              </div>
              <span
                class="text-sm font-medium ml-1 text-gray-500 dark:text-gray-400"
                >{{ set.lawyer.answer_rating }}</span
              >
            </dd>
          </dl>
        </div>
      </div>
    </div>
  </div>
</template>