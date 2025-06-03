<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import PromoHeader from "@/Layouts/PromoHeader.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import Answer from "@/Layouts/Answer.vue";
import Answers from "@/Layouts/Answers.vue";
import ShareButtons from "@/Components/ShareButtons.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";

let vars = defineProps({
  article: Object,
  user: Object,
  usluga: Object,
  auth: Object,
  region: Object,
  question: Object,
  answers: Object,
  authid: Number,
});

let avito = vars.article.avito.includes('avito')
</script>

<style>
.article h3 {
  font-size: 2rem;
}

.article ul,
ol {
  padding: 0 1rem;
  margin-left: 1rem;
  list-style-type: square;
}

.articlebody p {
  padding: 10px 0px;
}

p {
  text-overflow: ellipsis;
}

.artlwrhref {
  vertical-align: top;
  max-width: 100%;
  border: none;
}

a img {
  border-radius: 50%;
  width: 80px;
}

.artlwrhref::before {
  content: "Мнение эксперта";
  font-weight: bold;
  display: block;
  margin-bottom: 5px;
  font-size: 1.2rem;
}

blockquote {
  padding-left: 1rem;
  margin: 2rem;
  font-style: italic;
  border-left: solid red;
  min-height: 20vh;
}
</style>

<template>

  <Head title="{{ vars.article.header }}">
    <title>{{ vars.article.header }} - {{ usluga.usl_name }}</title>
    <meta name="description" :content="vars.article.description" />
    <meta property="og:title" :content="vars.article.header" />
    <meta property="og:description" :content="vars.article.description" />
    <meta property="og:type" content="article" />
    <meta property="og:url" :content="'https://nedicom.ru/articles/' + vars.article.url" />
    <meta property="og:image" :content="'https://nedicom.ru/' + vars.article.practice_file_path" />
    <meta property="og:site_name" content="nedicom.ru" />
    <meta property="og:locale" content="ru_RU" />
  </Head>

  <MainHeader :auth="vars.auth" />

  <PromoHeader />

  <Body>
    <div class="flex justify-left text-gray-900 md:px-10" itemscope itemtype="https://schema.org/Article">

      <meta itemprop="wordCount" :content="vars.article.body.length" />
      <meta v-if="vars.answers[0]" itemprop="commentCount" :content="vars.answers.length" />
      <!-- call to action -->
      <div v-if="article.phone"
        class="hidden h-96 md:w-1/4 2xl:w-1/4 md:grid grid-cols-1 place-content-center px-5 md:px-0">
        <a :href="'https://wa.me/' + article.phone + '?text=Здравствуйте.'" type="button" aria-label="Calltowhatsapp"
          class="mb-5 w-full inline-flex items-center justify-center text-white bg-emerald-700 hover:bg-emerald-800 font-medium rounded-lg py-2.5">
          <svg class="mr-2 w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
          </svg>

          <span class="flex flex-col">
            <span class=""> {{ article.phone }} </span>
            <span class="text-xs"> свзязаться с автором</span>
          </span>
        </a>
      </div>
      <!-- call to action -->
      <div class="w-full py-6 md:w-3/4 md:py-12 md:px-20 2xl:w-1/2 flex lg:px-8 bg-white overflow-hidden">
        <div class="px-6 bg-white overflow-hidden">
          <div v-if="vars.auth" class="my-3">
            <div v-if="vars.auth.id == article.userid || vars.auth.isadmin == 1">
              <a :href="route('articles.edit', [article.url])"
                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Редактировать</a>
            </div>
          </div>

          <!-- tooltip component -->
          <div class="group flex justify-between mb-2">
            <div class="group flex item-center">
              <div class="flex items-center justify-center relative">
                <Link :href="route('lawyer', article.userid)" class="hover:underline">
                <img :src="'https://nedicom.ru/' + article.avatar_path" :alt="usluga.usl_name" width="40"
                  class="rounded-full" />
                <span
                  class="-left-1 -top-1 animate-pulse absolute w-3.5 h-3.5 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></span>
                </Link>
              </div>

              <div class="flex items-center justify-center" itemprop="author" itemscope
                itemtype="https://schema.org/Person">
                <span
                  class="group-hover:opacity-100 transition-opacity bg-gray-800 mx-3 px-1 py-2 text-sm text-gray-100 rounded-md md:opacity-0">
                  автор -
                  <a itemprop="url" :href="route('lawyer', article.userid)"
                    :aria-label="'автор статьи - ' + article.name" class="hover:underline">
                    <span itemprop="name">{{ article.name }}</span>
                  </a>
                </span>
              </div>
            </div>

            <div class="mr-2 my-5 md:my-0 flex">
              <ShareButtons :bundle="vars.article" :auth="vars.auth" />

              <div class="flex items-center text-xs">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                  class="w-5 h-5 ml-5 mr-1">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>

                <p class="">{{ article.counter }}</p>
              </div>
            </div>
          </div>

          <div class="flex items-center justify-center text-xs md:text-sm">
            <div class="md:block hidden mr-6" itemprop="dateModified" :content="vars.article.updated_at">
              <div class="md:block hidden font-bold text-gray-600">
                время чтения:
              </div>
              <div>{{ (vars.article.body.length / 1000).toFixed(0) }} мин</div>
            </div>
            <div class="mr-3" itemprop="datePublished" :content="vars.article.created_at">
              <div class="md:block hidden font-bold text-gray-600">
                создано:
              </div>
              <div>{{ vars.article.created }}</div>
            </div>

            <div class="md:block hidden" itemprop="dateModified" :content="article.updated_at">
              <div class="md:block hidden font-bold text-gray-600">
                обновлено:
              </div>
              <div>{{ article.updated }}</div>
            </div>
          </div>
          <!-- tooltip component -->

          <!-- cta AI 
                  <section class="bg-white dark:bg-gray-900 visible md:hidden">
                    <div
                      class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400"
                      role="alert"
                    >
                      <span class="font-medium">Внимание.</span> Автор статьи
                      сейчас онлайн.
                    </div>
                    <div class="px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
                      <div class="max-w-screen-md">
                        <div
                          class="flex flex-col space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4"
                        >
                          <a
                            :href="route('lawyer.message', [article.userid])"
                            class="inline-flex items-center justify-center px-4 py-2.5 text-base font-medium text-center text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                          >
                            Чат с автором
                          </a>
                        </div>
                      </div>
                    </div>
                  </section>
                  cta AI -->

          <h1 v-if="vars.article.header" itemprop="headline"
            class="my-4 text-3xl font-extrabold leading-tight lg:mb-6 lg:text-4xl dark:text-white lead">
            {{ vars.article.header }}
          </h1>
          <p v-if="article.description" class="my-9 text-2xl lead text-gray-800" itemprop="description">
            {{ article.description }}
          </p>

          <span v-if="vars.article.avito">
            <div v-if="avito"
              class="flex flex-col md:flex-row items-center justify-between my-8 p-6 bg-white rounded-lg shadow-md ">
              <h2 class="text-base text-center font-semibold text-gray-800 mb-2 md:mb-0 px-5">Этот юрист оказывает услуги на
                Авито. Скажите, что Вы нашли его через nedicom.ru</h2>
              <a :href="vars.article.avito" target="_blank"
                class="inline-flex items-center px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd"
                    d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z"
                    clip-rule="evenodd" />
                </svg>
                Заказать на Авито
              </a>
            </div>
            <div v-else
              class="flex flex-col md:flex-row items-center justify-between my-8 p-6 bg-white rounded-lg shadow-md ">
              <h2 class="text-base text-center font-semibold text-gray-800 mb-2 md:mb-0">Этот юрист оказывает услуги по теме статьи</h2>
              <a :href="vars.article.avito" target="_blank"
                class="inline-flex items-center px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd"
                    d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z"
                    clip-rule="evenodd" />
                </svg>
                Заказать услугу
              </a>
            </div>
          </span>



          <!-- CTA wa -->
          <div class="md:hidden md:h-96 md:w-1/4 grid grid-cols-1 place-content-center px-5">
            <a :href="'https://wa.me/79788838978?text=Здравствуйте. Меня заинтересовала статья.' +
              vars.article.header +
              ' Не могли бы Вы мне помочь?'
              " type="button" aria-label="Calltowhatsapp"
              class="mb-5 w-full inline-flex items-center justify-center text-white mr-2 bg-emerald-700 hover:bg-emerald-800 font-medium rounded-lg py-2.5">
              <svg class="mr-2 w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
              </svg>

              <span class="flex flex-col">
                <span class=""> 8 (978) 8838 978 </span>
                <span class="text-xs"> свзяаться с автором в whatsapp </span>
              </span>
            </a>
          </div>
          <!-- CTA wa -->

          <div v-if="article.youtube_file_path" class="my-6">
            <iframe width="100%" height="500" :src="article.youtube_file_path" loading="lazy"
              title="YouTube video player" frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
              allowfullscreen></iframe>
          </div>
          <div v-if="vars.article.practice_file_path"
            class="float-none lg:float-right lg:w-1/2 text-center mx-auto mx-1 lg:ml-10 rounded-lg">
            <img rel="preload" fetchpriority="high" itemprop="image"
              class="border-2 border-gray-600 rounded-lg shadow-lg"
              :src="'https://nedicom.ru/' + vars.article.practice_file_path"
              :alt="vars.article.header + ' - практика'" />
            <div class="text-sm font-bold text-center mt-3">
              {{ usluga.usl_name }} - практика
            </div>
          </div>
          <div class="text-justify text-lg text-gray-800 articlebody" v-html="article.body" itemprop="text"></div>

          <div v-if="usluga" class="my-9 text-lg">
            Найти юриста:
            <a v-if="vars.region && vars.usluga" :href="'https://nedicom.ru/uslugi/' +
              vars.region.url +
              '/' +
              vars.usluga.url
              " class="font-bold hover:underline">{{ usluga.usl_name }} ({{ vars.region.title }})</a>
            <a v-else-if="vars.region" :href="'https://nedicom.ru/uslugi/' + vars.region.url"
              class="font-bold hover:underline">{{ vars.region.title }}</a>
            <a v-else-if="vars.usluga" :href="'https://nedicom.ru/uslugi/all-cities/' + vars.usluga.url"
              class="font-bold hover:underline">{{ usluga.usl_name }}</a>
          </div>

          <div class="w-full">
            <Answer :answerclass="'w-full sm:px-6 lg:px-4 mx-5 py-12 bg-white overflow-hidden'" :question="vars.article"
              :authid="vars.authid" :type="'article'" :article_id="vars.article.id" :subcomments="true" />
          </div>

          <div class="flex justify-center" id="comment">
            <Answers class="sm:px-6 lg:px-4 mx-5 py-12 md:w-4/6 w-full" :answers="vars.answers"
              :question="vars.question ? vars.question : null" :authid="vars.authid" :type="'article'"
              :article_id="vars.article.id" />
          </div>
        </div>
      </div>
      <!--
      <Chat
        :user="vars.article"
        :usluga="vars.usluga"
        class="hidden md:block"
      />
    -->
    </div>
  </Body>

  <!-- <Sidebaraction :ModalBtnText="ModalBtnText" /> -->
  <MainFooter />
</template>
