<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import PromoHeader from "@/Layouts/PromoHeader.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import Answer from "@/Layouts/Answer.vue";
import Answers from "@/Layouts/Answers.vue";
import SliderQuestions from "@/Layouts/SliderQuestions.vue";
import ShareButtons from "@/Components/ShareButtons.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";

let vars = defineProps({
  article: Object,
  totalComments: Number,
  SliderQ: Object,
  user: Object,
  usluga: Object,
  auth: Object,
  region: Object,
  question: Object,
  answers: Object,
  authid: Number,
});

let avito = vars.article.avito ? vars.article.avito.includes('avito') : null;

const trackClick = (x) => {
  if (typeof ym !== 'undefined') {
    ym(24900584, 'reachGoal', x, {
      url: vars.article.url,
      element: 'link'
    });   
  }
};
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
        <a :href="'https://wa.me/' + article.phone + '?text=Здравствуйте. Меня заинтересовала статья на nedicom.ru - ' +
              vars.article.header +
              '. Можно к Вам обратиться?'
              " 
         type="button" aria-label="Calltowhatsapp"
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

          <span v-if="vars.article.tg">
            <div class="flex flex-col md:flex-row items-center justify-between my-8 p-6 bg-white rounded-lg shadow-md">
              <h2 v-if="vars.article.tg_description" class="text-base text-center font-semibold text-gray-800 mb-2 md:mb-0 px-5">{{ vars.article.tg_description }}</h2>
              <h2 v-else class="text-base text-center font-semibold text-gray-800 mb-2 md:mb-0 px-5">У этого юриста есть
                телеграм</h2>
              <a :href="vars.article.tg" target="_blank" @click="trackClick('tg_click')"
                class="mr-5 mb-5 lg:mb-0 hover:text-gray-800 hover:opacity-80 flex items-center gap-4">
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="48" height="48" viewBox="0 0 48 48">
                  <path fill="#29b6f6" d="M24 4A20 20 0 1 0 24 44A20 20 0 1 0 24 4Z"></path>
                  <path fill="#fff"
                    d="M33.95,15l-3.746,19.126c0,0-0.161,0.874-1.245,0.874c-0.576,0-0.873-0.274-0.873-0.274l-8.114-6.733 l-3.97-2.001l-5.095-1.355c0,0-0.907-0.262-0.907-1.012c0-0.625,0.933-0.923,0.933-0.923l21.316-8.468 c-0.001-0.001,0.651-0.235,1.126-0.234C33.667,14,34,14.125,34,14.5C34,14.75,33.95,15,33.95,15z">
                  </path>
                  <path fill="#b0bec5"
                    d="M23,30.505l-3.426,3.374c0,0-0.149,0.115-0.348,0.12c-0.069,0.002-0.143-0.009-0.219-0.043 l0.964-5.965L23,30.505z">
                  </path>
                  <path fill="#cfd8dc"
                    d="M29.897,18.196c-0.169-0.22-0.481-0.26-0.701-0.093L16,26c0,0,2.106,5.892,2.427,6.912 c0.322,1.021,0.58,1.045,0.58,1.045l0.964-5.965l9.832-9.096C30.023,18.729,30.064,18.416,29.897,18.196z">
                  </path>
                </svg>
                <div class="text-2xl font-extrabold tracking-tight leading-none md:text-3xl lg:text-4xl">
                  telegram
                </div>
              </a>
            </div>
          </span>


          <span v-if="vars.article.avito">
            <div v-if="avito"
              class="flex flex-col md:flex-row items-center justify-between my-8 p-6 bg-white rounded-lg shadow-md ">
              <h2 class="text-base text-center font-semibold text-gray-800 mb-2 md:mb-0 px-5">Этот юрист оказывает
                услуги на
                Авито.</h2>
              <a :href="vars.article.avito" target="_blank" @click="trackClick('avito_click')"
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
              <h2 class="text-base text-center font-semibold text-gray-800 mb-2 md:mb-0">Этот юрист оказывает услуги по
                теме статьи</h2>
              <a :href="vars.article.avito" target="_blank" @click="trackClick('usluga_click')"
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

          <p class="text-xs text-center my-8">*nedicom.ru не отвечает за ресурсы по внешним ссылкам</p>

          <!-- CTA wa -->
          <div v-if="article.phone" class="md:hidden md:h-96 md:w-1/4 grid grid-cols-1 place-content-center px-5">
            <a 
              :href="'https://wa.me/' + article.phone + '?text=Здравствуйте. Меня заинтересовала статья на nedicom.ru - ' +
              vars.article.header +
              '. Можно к Вам обратиться?'
              "               
              type="button" aria-label="Calltowhatsapp"
              class="mb-5 w-full inline-flex items-center justify-center text-white mr-2 bg-emerald-700 hover:bg-emerald-800 font-medium rounded-lg py-2.5">
              <svg class="mr-2 w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
              </svg>

              <span class="flex flex-col">
                <span class=""> {{ article.phone }} </span>
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



    <div
      class="fixed left-1/2 bottom-0 -translate-x-1/2 flex items-center p-3 mb-4 text-gray-500 bg-white rounded-lg z-50 shadow-lg"
      role="menu">
      <a href="#"
        class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-blue-500 bg-blue-100 rounded-lg mx-2">
        <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
          viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 6v13m0-13 4 4m-4-4-4 4" />
        </svg>
        <span class="sr-only">Top icon</span>
      </a>
      <a href="#question-article"
        class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-blue-500 bg-blue-100 rounded-lg mx-2">
        <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M14.6144 7.19994c.3479.48981.5999 1.15357.5999 1.80006 0 1.6569-1.3432 3-3 3-1.6569 0-3.00004-1.3431-3.00004-3 0-.67539.22319-1.29865.59983-1.80006M6.21426 6v4m0-4 6.00004-3 6 3-6 2-2.40021-.80006M6.21426 6l3.59983 1.19994M6.21426 19.8013v-2.1525c0-1.6825 1.27251-3.3075 2.95093-3.6488l3.04911 2.9345 3-2.9441c1.7026.3193 3 1.9596 3 3.6584v2.1525c0 .6312-.5373 1.1429-1.2 1.1429H7.41426c-.66274 0-1.2-.5117-1.2-1.1429Z" />
        </svg>
        <span class="sr-only">Question icon</span>
      </a>
      <a href="#comment"
        class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-blue-500 bg-blue-100 rounded-lg mx-2">
        <svg class="w-6 h-6 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M7 9h5m3 0h2M7 12h2m3 0h5M5 5h14a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1h-6.616a1 1 0 0 0-.67.257l-2.88 2.592A.5.5 0 0 1 8 18.477V17a1 1 0 0 0-1-1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z" />
        </svg>
        <span class="sr-only">Comment icon</span>
      </a>{{ vars.totalComments }}

    </div>

    <SliderQuestions :sliderq="vars.SliderQ" />

  </Body>

  <!-- <Sidebaraction :ModalBtnText="ModalBtnText" /> -->
  <MainFooter />
</template>
