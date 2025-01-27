<script setup>
import ShareButtons from "@/Components/ShareButtons.vue";

let set = defineProps({
  bundles: Object,
  auth: Object,
});
</script>

<template>
  <div
    v-for="bundle in set.bundles.data"
    :key="bundle.id"
    class="flex justify-center mx-3 md:mx-0"
  >
    <article
      class="min-w-full p-6 bg-white rounded-lg border border-gray-200 shadow-md grid grid-cols-1 content-between"
    >
      <div>
        <div class="flex justify-between items-center mb-5 text-gray-500">
          <!-- name component-->
          <div class="flex justify-between w-full">
            <div class="flex w-full">
              <a
                v-if="bundle.lawyer"
                :href="route('lawyer', bundle.user_id)"
                class="flex items-center"
              >
                <div>
                  <img
                    :src="'https://nedicom.ru/' + bundle.avatar_path"
                    class="w-10 h-10 rounded-full"
                    alt="{{ bundle.name }} avatar"
                  />
                </div>
              </a>

              <a
                v-else
                class="flex pointer-events-none justify-center w-10 h-10"
                aria-label="Home"
                href="#"
                ><svg
                  width="50px"
                  height="50px"
                  fill="none"
                  viewBox="0 0 50 50"
                  xmlns="http://www.w3.org/2000/svg"
                  class="block w-auto fill-current text-gray-800"
                >
                  <path
                    d="m14.984 14.932 8.86-4.51 8.678 4.27.008 13.396-17.503.003-.043-13.159"
                    style="
                      fill: none;
                      stroke: rgb(234, 0, 0);
                      stroke-width: 2.41544;
                      stroke-linecap: round;
                      stroke-linejoin: round;
                      stroke-miterlimit: 4;
                      stroke-dasharray: none;
                      stroke-opacity: 1;
                    "
                  ></path>
                  <path
                    d="m2.705 22.19 8.859-4.51 8.679 4.27.008 13.397-17.503.003-.043-13.16m24.664 0 8.859-4.51 8.679 4.27.007 13.397-17.503.003-.042-13.16"
                    style="
                      fill: none;
                      stroke: rgb(0, 0, 0);
                      stroke-width: 2.41544;
                      stroke-linecap: round;
                      stroke-linejoin: round;
                      stroke-miterlimit: 4;
                      stroke-dasharray: none;
                      stroke-opacity: 0.988235;
                    "
                  ></path></svg
              ></a>

              <span class="grid grid-cols-1 content-center ml-2">
                <span class="text-base">
                  <a
                    v-if="bundle.lawyer"
                    :href="route('lawyer', bundle.user_id)"
                    class="hover:underline hover:font-semibold"
                  >
                    {{ bundle.name }}
                  </a>
                  <span v-else> Пользователь скрыт </span>
                </span>

                <span class="mr-5 text-xs flex items-center">
                  <span
                    class="font-bold inline-flex items-center py-0.5 rounded"
                  >
                    <svg
                      class="mr-1 w-3 h-3"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"
                      ></path>
                    </svg>
                    <span v-if="bundle.type == 'questions'">Вопрос</span>
                    <span v-if="bundle.type == 'articles'">Статья</span>
                    <span v-if="bundle.type == 'uslugi'">Услуга</span>
                    <span v-if="bundle.type == 'lawyer'">Юрист</span>
                  </span>
                  <span class="ml-4">{{ bundle.created_at }}</span>
                </span>
              </span>
            </div>

            <div class="flex items-center text-xs">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                class="w-5 h-5 ml-5 mr-1"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"
                />
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                />
              </svg>

              <p class="">{{ bundle.counter }}</p>
            </div>
          </div>
          <!--name component -->
        </div>
        <h2 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">
          <a :href="'/' + bundle.type + '/' + bundle.url">{{
            bundle.header
          }}</a>
        </h2>
        <p class="mb-8 font-light text-gray-800 line-clamp-2">
          {{ bundle.abody }}
        </p>

        <div v-if="bundle.comment" class="group flex item-center mb-8">          
            <div class="grid grid-cols-6 md:grid-cols-10 w-full items-center">
              <div v-if="bundle.avatar" class="flex justify-end pr-3">
                <img
                  :src="'https://nedicom.ru/' + bundle.avatar"
                  class="w-8 h-8 rounded-full"
                  alt="{{ bundle.name }} avatar"
              /></div>
              <div v-else class="flex justify-end pr-3">
                <svg
                  width="30px"
                  height="30px"
                  fill="none"
                  viewBox="0 0 50 50"
                  xmlns="http://www.w3.org/2000/svg"
                  class="block w-auto fill-current text-gray-800"
                >
                  <path
                    d="m14.984 14.932 8.86-4.51 8.678 4.27.008 13.396-17.503.003-.043-13.159"
                    style="
                      fill: none;
                      stroke: rgb(234, 0, 0);
                      stroke-width: 2.41544;
                      stroke-linecap: round;
                      stroke-linejoin: round;
                      stroke-miterlimit: 4;
                      stroke-dasharray: none;
                      stroke-opacity: 1;
                    "
                  ></path>
                  <path
                    d="m2.705 22.19 8.859-4.51 8.679 4.27.008 13.397-17.503.003-.043-13.16m24.664 0 8.859-4.51 8.679 4.27.007 13.397-17.503.003-.042-13.16"
                    style="
                      fill: none;
                      stroke: rgb(0, 0, 0);
                      stroke-width: 2.41544;
                      stroke-linecap: round;
                      stroke-linejoin: round;
                      stroke-miterlimit: 4;
                      stroke-dasharray: none;
                      stroke-opacity: 0.988235;
                    "
                  ></path></svg
              ></div>
              <div class="col-span-5 md:col-span-9 text-sm text-gray-500 line-clamp-2">
                {{ bundle.comment }}
              </div>
            
          </div>
        </div>
      </div>

      <div class="flex justify-between">
        <ShareButtons :bundle="bundle" :auth="set.auth" />

        <!--commets-->
        <a
          :href="'/' + bundle.type + '/' + bundle.url + '#comment'"
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
          ><span v-if="bundle.comment_count > 1" class="text-sm">{{
            bundle.comment_count
          }}</span>
        </a>
        <!--commets-->
        <a
          :href="'/' + bundle.type + '/' + bundle.url"
          class="inline-flex items-center font-medium text-primary-600 dark:text-primary-500 hover:underline"
        >
          подробнее

          <svg
            class="ml-2 w-4 h-4"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              fill-rule="evenodd"
              d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
              clip-rule="evenodd"
            ></path>
          </svg>
        </a>
      </div>
    </article>
  </div>
</template>