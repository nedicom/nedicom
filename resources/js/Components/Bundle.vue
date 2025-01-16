<script setup>
import { Inertia } from "@inertiajs/inertia";
import { ref, reactive, onMounted } from "vue";

let set = defineProps({
  bundles: Object,
  auth: Object,
});

let like = ref(true);
let share = ref(true);
let bookmark = ref(true);
let comment = ref(true);

let submit = (property, id, type, value) => {
  if (set.auth) {
    Inertia.post(
      "/social",
      { property: property, id: id, type: type, value: value },
      { preserveScroll: true }
    );
  }
};
</script>

<template>
  <div
    v-for="bundles in set.bundles.data"
    :key="bundles.id"
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
                v-if="bundles.lawyer"
                :href="route('lawyer', bundles.id)"
                class="flex items-center"
              >
                <div>
                  <img
                    :src="'https://nedicom.ru/' + bundles.apath"
                    class="w-10 h-10 rounded-full"
                    alt="{{ bundles.name }} avatar"
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
                    v-if="bundles.lawyer"
                    :href="route('lawyer', bundles.id)"
                    class="hover:underline hover:font-semibold"
                  >
                    {{ bundles.name }}
                  </a>
                  <span v-else> Пользователь скрыт </span>
                </span>

                <span class="mr-5 text-xs flex items-center">
                  <span
                    v-if="bundles.type == '0' || bundles.type == 0"
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
                    Вопрос
                  </span>

                  <span
                    v-else
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
                    Статья
                  </span>
                  <span class="ml-4">{{ bundles.created_at }}</span>
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

              <p class="">{{ bundles.counter }}</p>
            </div>
          </div>
          <!--name component -->
        </div>
        <h2 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">
          <a
            v-if="bundles.type == '0' || bundles.type == 0"
            :href="route('questions.url', bundles.url)"
            >{{ bundles.aheader }}</a
          >
          <a v-else :href="route('articles/url', bundles.url)">{{
            bundles.aheader
          }}</a>
        </h2>
        <p class="mb-5 font-light text-gray-800 line-clamp-2">
          {{ bundles.abody }}
        </p>

        <div class="group flex item-center mb-2">
          <div v-if="bundles.comment">
            <div class="flex items-center">
              <img
                :src="'https://nedicom.ru/' + bundles.avatar"
                class="w-8 h-8 rounded-full"
                alt="{{ bundles.name }} avatar"
              />
              <span class="text-sm text-gray-500 ml-2 line-clamp-2">
                {{ bundles.comment }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <div class="flex justify-between">
        <div class="flex justify-left">
          <svg
            v-if="like"
            class="w-6 h-6 text-gray-800 dark:text-white"
            aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            fill="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              d="m12.75 20.66 6.184-7.098c2.677-2.884 2.559-6.506.754-8.705-.898-1.095-2.206-1.816-3.72-1.855-1.293-.034-2.652.43-3.963 1.442-1.315-1.012-2.678-1.476-3.973-1.442-1.515.04-2.825.76-3.724 1.855-1.806 2.201-1.915 5.823.772 8.706l6.183 7.097c.19.216.46.34.743.34a.985.985 0 0 0 .743-.34Z"
            />
          </svg>

          <svg
            v-else
            class="w-6 h-6 text-gray-800 dark:text-white"
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
              d="M12.01 6.001C6.5 1 1 8 5.782 13.001L12.011 20l6.23-7C23 8 17.5 1 12.01 6.002Z"
            />
          </svg>

          <svg
            class="w-6 h-6 text-gray-800 dark:text-white"
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
              d="M4 15v2a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-2M12 4v12m0-12 4 4m-4-4L8 8"
            />
          </svg>

          <!--bookmarks-->


          <button data-popover-target="popover-click" data-popover-trigger="click" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Click popover</button>

<div data-popover id="popover-click" role="tooltip" class="absolute z-10 invisible inline-block w-64 text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800">
    <div class="px-3 py-2 bg-gray-100 border-b border-gray-200 rounded-t-lg dark:border-gray-600 dark:bg-gray-700">
        <h3 class="font-semibold text-gray-900 dark:text-white">Popover click</h3>
    </div>
    <div class="px-3 py-2">
        <p>And here's some amazing content. It's very engaging. Right?</p>
    </div>
    <div data-popper-arrow></div>
</div>

           <div
            data-popover 
            id="popover-click"
            role="tooltip"
            class="absolute z-10 invisible inline-block w-64 text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800"
          >
            <div class="px-3 py-2">
              <p>And here's some amazing content. It's very engaging. Right?</p>
            </div>
            <div data-popper-arrow></div>
          </div>

          <span
            v-if="bundles.user_bookmark"
            @click="submit(bundles.type, bundles.qid, 'bookmarks', 'down')"
            class="flex items-center text-gray-500 hover:text-gray-700"
          >
            <svg
              class="w-6 h-6"
              aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              fill="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                d="M7.833 2c-.507 0-.98.216-1.318.576A1.92 1.92 0 0 0 6 3.89V21a1 1 0 0 0 1.625.78L12 18.28l4.375 3.5A1 1 0 0 0 18 21V3.889c0-.481-.178-.954-.515-1.313A1.808 1.808 0 0 0 16.167 2H7.833Z"
              /></svg
            ><span v-if="bundles.bookmarks > 0" class="text-sm">{{
              bundles.bookmarks
            }}</span>
          </span>

          <span
            v-else
            data-popover-target="popover-default"
            data-popover-trigger="click"
            @click="submit(bundles.type, bundles.qid, 'bookmarks', 'up')"
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
                d="m17 21-5-4-5 4V3.889a.92.92 0 0 1 .244-.629.808.808 0 0 1 .59-.26h8.333a.81.81 0 0 1 .589.26.92.92 0 0 1 .244.63V21Z"
              /></svg
            ><span v-if="bundles.bookmarks > 0" class="text-sm">{{
              bundles.bookmarks
            }}</span>
          </span>
          <!--bookmarks-->

          <!--commets-->
          <a
            :href="
              route(
                bundles.type == 0 ? 'questions.url' : 'articles/url',
                bundles.url + '#comment'
              )
            "
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
            ><span v-if="bundles.comment_count > 1" class="text-sm">{{
              bundles.comment_count
            }}</span>
          </a>
          <!--commets-->
        </div>

        <a
          v-if="bundles.type == '0' || bundles.type == 0"
          :href="route('questions.url', bundles.url)"
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

        <a
          v-else
          :href="route('articles/url', bundles.url)"
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