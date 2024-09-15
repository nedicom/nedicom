<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import { ref, reactive } from "vue";
import { Head } from "@inertiajs/inertia-vue3";
import { ModalsContainer, useModal } from "vue-final-modal";
import DeleteModalConfirm from "@/Components/DeleteModalConfirm.vue";
import { Inertia } from "@inertiajs/inertia";
import { Link } from "@inertiajs/inertia-vue3";

let modalTitle = ref(null);
let userId = ref(null);
let title = ref("Услуги");

defineProps({
  uslugi: Array,
  filters: Object,
  lawyers: Object,
  cities: Object,
});

const { open, close } = useModal({
  component: DeleteModalConfirm,
  attrs: {
    title: modalTitle,
    id: userId,
    onConfirm(id) {
      Inertia.get(`/admin/uslugi/${id}/delete`);
    },
    onClose() {
      close();
    },
  },
  slots: {
    default: "<p>UseModal: The content of the modal</p>",
  },
});

const handleDelete = (id, title) => {
  userId.value = id;
  modalTitle.value = title;
  open();
};
</script>

<template>

  <Head>
    <title>{{ title }}</title>
    <meta name="description" content="Услуги админа" />
  </Head>

  <MainHeader />

  <ModalsContainer />

  <Body>
    <div class="bg-white py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <!--filters-->
          <div class="flex items-center justify-between mb-6">
            <div class="mr-4 grow">
              <input v-model="form.search"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                autocomplete="off" type="text" name="search" placeholder="Искать по названию..." />
            </div>
            <div class="grid gap-6 md:grid-cols-2">
              <button @click="reset"
                class="font-bold text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                type="button">
                сбросить
              </button>

              <Link
                class="font-bold text-white bg-sky-600 hover:bg-sky-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                href="/uslugiadd">
              Добавить услугу
              </Link>
            </div>
          </div>

          <div class="grid gap-6 mb-6 md:grid-cols-3">
            <div class="max-w">
              <select v-model="form.author"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option disabled value="">Юрист</option>
                <option v-for="option in lawyers" :key="option.id" v-bind:value="option.id">
                  {{ option.name }}
                </option>
              </select>
            </div>

            <div class="max-w">
              <select v-model="form.city"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option disabled value="">Город</option>
                <option v-for="option in cities" :key="option.id" v-bind:value="option.id">
                  {{ option.title }}
                </option>
              </select>
            </div>

            <div class="flex items-center">
              <div class="flex items-center">
                <input v-model="form.main" id="default-checkbox" type="checkbox" value=""
                  class="w-4 h-4 mr-1 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                <label for="default-checkbox"
                  class="ms-2 mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">Главная</label>
              </div>

              <div class="flex items-center">
                <input v-model="form.second" id="default-checkbox" type="checkbox" value=""
                  class="w-4 h-4 mr-1 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                <label for="default-checkbox"
                  class="ms-2 mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">Вторичная</label>
              </div>

              <div class="flex items-center">
                <input v-model="form.feed" id="default-checkbox" type="checkbox" value=""
                  class="w-4 h-4 mr-1 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                <label for="default-checkbox"
                  class="ms-2 mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">Яндекс фид</label>
              </div>
            </div>
          </div>

          <!--filters-->

          <div v-if="uslugi.total > 0">
            <table class="table-auto w-full text-sm text-left">
              <thead>
                <tr class="font-bold text-xs text-gray-700 uppercase bg-gray-50">
                  <th scope="col" class="p-4">№</th>
                  <th scope="col" class="p-4">ID</th>
                  <th scope="col" class="p-4">Заголовок</th>
                  <th scope="col" class="p-4">Автор</th>
                  <th scope="col" class="p-4">город</th>
                  <th scope="col" class="p-4">Главная</th>
                  <th scope="col" class="p-4">Вторичная</th>
                  <th scope="col" class="p-4">Я.Фид</th>
                  <th scope="col" class="p-4">Дата создания</th>
                  <th scope="col" class="p-4"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(uslugi, n) in uslugi.data" :key="uslugi"
                  class="hover:bg-gray-100 focus-within:bg-gray-100 text-left">
                  <th scope="row" class="border-t">
                    <div class="flex items-center p-4">{{ n + 1 }}</div>
                  </th>
                  <th scope="row" class="border-t">
                    <div class="flex items-center p-4">{{ uslugi.id }}</div>
                  </th>
                  <td class="border-t w-full">
                    <a class="" :href="route('uslugi.url', [uslugi.url])">{{
                      uslugi.usl_name
                    }}</a>
                  </td>
                  <td class="border-t">
                    <div v-if="uslugi.firstlawyer" class="p-4">
                      {{ uslugi.firstlawyer.name }}
                    </div>
                    <div v-else></div>
                  </td>
                  <td class="border-t">
                    <div v-if="uslugi.cities" class="p-4">
                      {{ uslugi.cities.title }}
                    </div>
                    <div v-else></div>
                  </td>
                  <td class="border-t">
                    <div v-if="uslugi.is_main" class="flex items-center w-12 p-4">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                      </svg>
                    </div>
                    <div v-else></div>
                  </td>
                  <td class="border-t">
                    <div v-if="uslugi.is_second" class="flex items-center w-12 p-4">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                      </svg>
                    </div>
                    <div v-else></div>
                  </td>
                  <td class="border-t">
                    <div v-if="uslugi.is_feed" class="flex items-center w-12 p-4">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                      </svg>
                    </div>
                    <div v-else></div>
                  </td>
                  <td class="border-t p-4">{{ uslugi.created_at }}</td>
                  <td class="border-t p-4 text-left">
                    <button @click="handleDelete(uslugi.id, uslugi.usl_name)" class="btn btn-light w-100 ml-5">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                      </svg>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- row -->
          <div v-else class="flex justify-center h-96">
            <h5 class="text-gray-900 text-xl leading-tight font-medium m-6 p-6">
              Услуги не найдены
            </h5>
          </div>
          <!-- row -->

          <Pagination :links="uslugi.links" />
        </div>
      </div>
    </div>
  </Body>

  <MainFooter />
</template>

<script>
import Pagination from "@/Components/Pagination.vue";
export default {
  components: {
    Pagination,
  },

  data() {
    return {
      form: {
        search: this.filters.search,
        author: this.filters.author,
        city: this.filters.city,
        main: this.filters.main,
        second: this.filters.second,
        feed: this.filters.feed,
      },
    };
  },

  watch: {
    form: {
      deep: true,
      handler: function (value) {
        this.$inertia.get(
          "/admin/uslugi",
          {
            search: this.form.search,
            author: this.form.author,
            city: this.form.city,
            main: this.form.main,
            second: this.form.second,
            feed: this.form.feed,
          },
          { preserveState: true }
        );
      },
    },
  },

  methods: {
    reset() {
      this.form.search = null;
      this.form.author = null;
      this.form.city = null;
      this.form.main = null;
      this.form.second = null;
      this.form.feed = null;
    },
  },
};
</script>