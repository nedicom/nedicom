<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import Editor from '@/Components/Tiptap.vue'
import { ref } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { reactive } from "vue";

defineProps({
    mainusl_id: Object,
    cities: Object,
  });

let form = reactive({
  title: "",
  sity: "",
  mainusl_id: "",
});

let submit = () => {
  Inertia.post("/offer/create", form);
};

let title = ref("Добавить список услуг (offers)");
</script>

<template>
  <Head title="Добавить список услуг (offers)" />

  <MainHeader />

  <Header :ttl="title" />

  <Body>
    <div class="bg-white py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="flex md:justify-start justify-center px-6">
            <div class="mb-3 md:w-3/6 w-full">
              <form @submit.prevent="submit" class="">
                <h2 class="text-lg font-medium text-gray-900">Заголовок перечня услуг</h2>
                <label class="block mt-5 mb-2 text-sm font-medium text-gray-900 dark:text-white">Коротко об улугах (offers)</label>
                <textarea
                  v-model="form.title"
                  spellcheck="true"
                  maxlength="55"
                  class="
                    form-control
                    block
                    w-full
                    px-3
                    py-1.5
                    text-base
                    font-normal
                    text-gray-700
                    bg-white bg-clip-padding
                    border border-solid border-gray-300
                    rounded
                    transition
                    ease-in-out
                    m-0
                    focus:text-gray-700
                    focus:bg-white
                    focus:border-blue-600
                    focus:outline-none
                  "
                  id=""
                  rows="2"
                  placeholder="Название перечня услуг услуги (до 55 символов)"
                  required
                ></textarea>

                <label class="block mt-5 mb-2 text-sm font-medium text-gray-900 dark:text-white">Выберите город</label>
                   <select v-model="form.sity" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option disabled value="">город</option>
                    <option
                      v-for="option in cities"
                      :key="option.id"
                      v-bind:value="option.id"
                    >
                      {{ option.title }}
                    </option>
                </select>

                <label class="block mt-5 mb-2 text-sm font-medium text-gray-900 dark:text-white">Выберите услугу</label>
                   <select v-model="form.mainusl_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option disabled value="">Категория</option>
                    <option v-for="option in mainusl_id" :key="option.id" v-bind:value="option.id" :selected="option.usl_name">
                      {{ option.usl_name }}
                    </option>
                  </select>

                <button
                  type="submit"
                  class="
                    my-5
                    inline-flex
                    items-center
                    px-5
                    py-2.5
                    text-sm
                    font-medium
                    text-center text-white
                    bg-blue-700
                    rounded-lg
                    focus:ring-4 focus:ring-blue-200
                    dark:focus:ring-blue-900
                    hover:bg-blue-800
                  "
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