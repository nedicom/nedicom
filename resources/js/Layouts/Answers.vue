<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import AccordionComment from "@/Components/AccordionComment.vue";
import { Inertia } from "@inertiajs/inertia";

defineProps({
  article_id: Number,
  answers: Object,
  question: Object,
  authid: Number,
  type: String,
});
</script>

<template>
  <div class="grid grid-cols-1 gap-9 divide-y">
    <!-- comment -->
    <div v-for="answer in answers" :key="answer.id">
      <div
        v-if="!answer.parent_comment_id"
        :id="answer.id"
        class="min-w-full p-6 bg-white max-w-sm flex flex-col"
        itemprop="suggestedAnswer"
        itemscope
        itemtype="https://schema.org/Answer"
      >
        <meta
          v-if="!answer.parent_comment_id"
          itemprop="datePublished"
          :content="answer.updated_at"
        />
        <meta
          v-if="question"
          itemprop="url"
          :content="'https://nedicom.ru/questions/' + question.url + '#comment'"
        />
        <meta
          v-if="question"
          itemprop="upvoteCount"
          :content="question.user_like"
        />
        <meta v-else itemprop="upvoteCount" content="1" />
        <div class="flex flex-right mb-2">
          <Link
            :href="route('lawyer', answer.user_ans.id)"
            class="hover:underline flex flex-justify"
            itemprop="author"
            itemscope
            itemtype="https://schema.org/Person"
          >
            <meta
              itemprop="url"
              :content="'https://nedicom.ru/lawyers/' + answer.user_ans.id"
            />
            <img
              :src="'https://nedicom.ru/' + answer.user_ans.avatar_path"
              width="40"
              class="rounded-full mr-3"
            />
            <p
              v-if="answer.user_ans.lawyer == 1"
              class="mr-3 text-sm text-gray-900 dark:text-white font-semibold h-min-24 flex items-center"
              itemprop="name"
            >
              {{ answer.user_ans.name }}
            </p>

            <p
              v-else
              class="mr-3 text-sm text-gray-900 dark:text-white font-semibold h-min-24 flex items-center"
              itemprop="name"
            >
              пользователь скрыт
            </p>
          </Link>
          <p class="text-gray-400 text-sm mx-4 flex items-center">
            {{ answer.created_at }}
          </p>
        </div>
        <p class="text-gray-700 text-base h-min-24 mb-2" itemprop="text">
          {{ answer.body }}
        </p>

        <div v-if="authid === 1">
          <svg
            v-if="type === 'article'"
            @click="Inertia.post(route('article.comment.delete', [answer.id]))"
            class="w-6 h-6 text-gray-800 hover:cursor-pointer"
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
              d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"
            />
          </svg>

          <svg
            v-if="type === 'question'"
            @click="Inertia.post(route('answer.delete', [answer.id]))"
            class="w-6 h-6 text-gray-800 hover:cursor-pointer"
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
              d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"
            />
          </svg>
        </div>

        <AccordionComment
          v-if="question"
          :question="question"
          :answerid="answer.id"
          :authid="authid"
          :type="type"
          :article_id="article_id"
        />
      </div>

      <!--subcomments-->
      <div v-if="answer.subcomments">
        <div
          v-for="subcomments in answer.subcomments"
          :key="subcomments.id"
          class="ml-16"
        >
          <div class="min-w-full p-6 bg-white max-w-sm flex flex-col">
            <div class="flex flex-right mb-2">
              <Link
                v-if="subcomments.lawyer == 1"
                :href="route('lawyer', subcomments.id)"
                class="hover:underline flex flex-justify"
              >
                <img
                  :src="'https://nedicom.ru/' + subcomments.avatar_path"
                  width="40"
                  class="rounded-full mr-3"
                />
                <p
                  class="mr-3 text-sm text-gray-900 dark:text-white font-semibold h-min-24 flex items-center"
                >
                  {{ subcomments.name }}
                </p>
              </Link>

              <Link
                v-else
                href="#"
                class="flex flex-justify cursor-default"
              >
                <img
                  :src="'https://nedicom.ru/' + subcomments.avatar_path"
                  width="40"
                  class="rounded-full mr-3"
                />
                <p
                  class="mr-3 text-sm text-gray-900  font-semibold h-min-24 flex items-center"
                >
                  пользователь скрыт
                </p>
              </Link>
              <p class="text-gray-400 text-sm mx-4 flex items-center">
                {{ subcomments.pivot.created_at }}
              </p>
            </div>
            <p class="text-gray-700 text-base h-min-24 mb-2">
              {{ subcomments.pivot.body }}
            </p>

            <svg
              v-if="type === 'question' && authid === 1"
              @click="
                Inertia.post(route('answer.delete', [subcomments.pivot.id]))
              "
              class="w-6 h-6 text-gray-800 hover:cursor-pointer"
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
                d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"
              />
            </svg>

            <svg
              v-if="type === 'article' && authid === 1"
              @click="
                Inertia.post(
                  route('article.comment.delete', [subcomments.pivot.id])
                )
              "
              class="w-6 h-6 text-gray-800 hover:cursor-pointer"
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
                d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"
              />
            </svg>

            <AccordionComment
              :question="question"
              :answerid="answer.id"
              :authid="authid"
              :type="type"
              :article_id="article_id"
            />
          </div>
        </div>
      </div>
    </div>
    <!-- comment -->
  </div>
</template>
