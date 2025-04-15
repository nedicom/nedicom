<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import Editor from "@/Components/Tiptap.vue";
import { ref, watch, reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Head } from "@inertiajs/inertia-vue3";

let set = defineProps({
  auth: Object,
});

let form = reactive({
  header: "",
});

let submit = () => {
  Inertia.post("/articles/generate", form);
};

let title = ref("Добавить статью от админа");
</script>

<template>
  <Head>
    <title>{{ title }}</title>
    <meta name="description" content="Страница добавления статьи" />
  </Head>

  <MainHeader :auth="set.auth" />

  <Header />

  <Body>
    <div class="bg-white py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3 p-5">
            <form @submit.prevent="submit" class="">
              <textarea
                v-model="form.header"
                @input="onInputheader"
                spellcheck="true"
                maxlength="155"
                class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                required
                id=""
                rows="2"
                placeholder="Заголовок. Задумайтесь, что в нем зацепит внимание пользователя"
              ></textarea>
              <div class="flex justify-center">
                <button
                  type="submit"
                  class="my-5 inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800 text-center"
                >
                  Генерировать
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </Body>

  <MainFooter />
</template>