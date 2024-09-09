<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import Editor from "@/Components/Tiptap.vue";
import { ref } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { reactive } from "vue";

let set = defineProps({
  all_uslugi: Object,
  second_uslugi: Object,
  cities: Object,
  user: Object,
});

let form = reactive({
  header: "",
  description: "",
  main_usluga_id: "",
  second_usluga_id: "",
  is_main: null,
  is_second: null,
  is_feed: null,
  sity: "",
});

let submit = () => {
  Inertia.post("/uslugi/create", form);
};

let title = ref("Добавить услугу");
</script>

<template>
  <Head title="Добавить услугу" />

  <MainHeader />

  <Header :ttl="title" />

  <Body>
    <div class="bg-white py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="flex md:justify-start justify-center px-6">
            <div class="mb-3 md:w-3/6 w-full">
              <h2 class="text-lg font-medium text-gray-900 ">
                Краткая информация об услуге
              </h2>
              <label
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >После создания Вы сможете ее дополнить</label
              >
              <form @submit.prevent="submit" class="mt-10">
                <!-- is main? -->
                <div v-if="user.isadmin == 1" class="flex justify-between mb-4">
                  <div v-if="form.is_second !== true">
                    <input
                      v-model="form.is_main"
                      id="default-checkbox"
                      type="checkbox"
                      class="w-4 h-4 ml-2 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                    />
                    <label
                      for="default-checkbox"
                      class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                      >Главная</label
                    >
                  </div>
                  <div v-if="form.is_main !== true">
                    <input
                      v-model="form.is_second"
                      id="default-checkbox"
                      type="checkbox"
                      class="w-4 h-4 ml-2 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                    />
                    <label
                      for="default-checkbox"
                      class="mx-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                      >Вторичная</label
                    >
                  </div>
                  <div>
                    <input
                      v-model="form.is_feed"
                      id="default-checkbox"
                      type="checkbox"
                      class="w-4 h-4 ml-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                    />
                    <label
                      for="default-checkbox"
                      class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                      >Яндекс.фид</label
                    >
                  </div>
                </div>
                <!-- is main? -->

                <!-- main usluga -->
                <div v-if="form.is_main !== true">
                  <div>
                    <label
                      class="block mt-5 mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >Выберите категорию</label
                    >
                    <select
                      v-model="form.main_usluga_id"
                      required
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                      <option disabled value="">
                        Выберите один из вариантов
                      </option>
                      <option
                        v-for="option in set.all_uslugi"
                        :key="option.id"
                        v-bind:value="option.id"
                      >
                        {{ option.usl_name }}
                      </option>
                    </select>
                  </div>
                  <!-- main usluga -->

                  <!-- second usluga -->
                  <div v-if="form.is_second !== true">
                    <label
                      class="block mt-5 mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >Выберите подкатегорию (необязательно)</label
                    >
                    <select
                      v-model="form.second_usluga_id"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                      <option disabled value="">
                        Выберите один из вариантов
                      </option>
                      <option
                        v-for="option in set.second_uslugi[form.main_usluga_id]"
                        :key="option.id"
                        v-bind:value="option.id"
                      >
                        {{ option.usl_name }}
                      </option>
                    </select>
                  </div>
                </div>
                <!-- second usluga -->

                <textarea
                  v-model="form.header"
                  spellcheck="true"
                  maxlength="55"
                  class="mt-5 form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                  id=""
                  rows="2"
                  placeholder="Название услуги (до 55 символов)"
                ></textarea>

                <textarea
                  v-model="form.description"
                  spellcheck="true"
                  maxlength="150"
                  class="h-20 form-control mt-3 block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                  id=""
                  name="description"
                  rows="5"
                  placeholder="Ваше уникальное торговое предложение (до 150 символов, его видно в качестве описания в поисковой выдаче)"
                ></textarea>

                <div v-if="!(form.is_main || form.is_second)">
                  <label
                    class="block mt-5 mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    >Выберите город</label
                  >
                  <select
                    v-model="form.sity"
                    required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  >
                    <option disabled value="">город</option>
                    <option
                      v-for="option in set.cities"
                      :key="option.id"
                      v-bind:value="option.id"
                    >
                      {{ option.title }}
                    </option>
                  </select>
                </div>

                <button
                  type="submit"
                  class="my-5 inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800"
                >
                  Опубликовать
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Body>

  <MainFooter />
</template>