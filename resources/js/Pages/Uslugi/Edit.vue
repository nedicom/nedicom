<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import Editor from "@/Components/Tiptap.vue";
import UslugaCropper from "@/Components/UslugaCropper.vue";
import SquareCropper from "@/Components/SquareCropper.vue";
import UslugaMobileCropper from "@/Components/UslugaMobileCropper.vue";
import FlashMessage from "@/Components/FlashMessage.vue";

import Review from "@/Components/Review.vue";
import PopularQuestion from "@/Components/PopularQuestion.vue";
import AddVideo from "@/Components/AddVideo.vue";
import AddPrice from "@/Components/AddPrice.vue";

import "@vuepic/vue-datepicker/dist/main.css";

import { ref, reactive, watch } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";

let set = defineProps({
  uslugi: Object,
  main_uslugi: Object,
  second_uslugi: Object,
  cities: Object,
  user: Object,
  errors: Object,
  flash: Object,
  auth: Object,
  prices: Object,
  userprices: Object,  
});

let checkbox = false;
let checkboxsecond = false;
let checkboxfeed = false;

if (set.uslugi.is_main == 1) {
  checkbox = true;
}

if (set.uslugi.is_second == 1) {
  checkboxsecond = true;
}

if (set.uslugi.is_feed == 1) {
  checkboxfeed = true;
}

if (!set.uslugi.main_usluga_id) {
  set.uslugi.main_usluga_id = 0;
}

if (set.uslugi.popular_question == null) {
  set.uslugi.popular_question = [{ question: "", answer: "" }];
}

if (set.uslugi.video == null) {
  set.uslugi.video = [{ videolink: "" }];
}

let form = reactive({
  header: set.uslugi.usl_name,
  description: set.uslugi.usl_desc,
  longdescription: set.uslugi.longdescription,
  phone: set.uslugi.phone,
  address: set.uslugi.address,
  dopadress: set.uslugi.dopadress,
  maps: set.uslugi.maps,
  is_main: checkbox,
  is_second: checkboxsecond,
  is_feed: checkboxfeed,
  main_usluga_id: set.uslugi.main_usluga_id,
  second_usluga_id: set.uslugi.second_usluga_id,
  ids: set.uslugi.id,
  popular: set.uslugi.popular_question,
  video_links: set.uslugi.video,
  sity: set.uslugi.sity,
  expirience: set.uslugi.expirience,
  price: set.uslugi.price,
});

let zero = ref(true);

//clean second form
watch(
  () => form.main_usluga_id,
  (main_usluga_id) => {
    if (form.main_usluga_id !== set.uslugi.main_usluga_id) {
      form.second_usluga_id = null;
    } else {
      form.second_usluga_id = set.uslugi.second_usluga_id;
    }
    let i = 0;
    while (i < set.main_uslugi.length) {
      if (set.main_uslugi[i].zerocategory) {
        if (set.main_uslugi[i].zerocategory.main_usluga_id == main_usluga_id) {
          zero.value = false;
          break;
        } else {
          zero.value = true;
        }
      }
      i = i + 1;
    }
  }
);

function submit() {
  Inertia.post("/usluga/{url}/update", form);
}

let title = ref("Редактировать услугу");

const date = ref(new Date());
</script>

<template>
  <FlashMessage :message="set.flash.message" />

  <Head title="Редактировать услугу" />

  <MainHeader :auth="set.auth" />

  <Body>
    <div class="bg-white py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-center p-5">
          <div class="mb-3 md:w-3/6">
            <form @submit.prevent="submit">
              <input v-model="form.ids" class="invisible" />

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
                    >Сделать услугу главной</label
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
                    >Сделать услугу вторичной</label
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
                    >Яндекс фид</label
                  >
                </div>
              </div>
              <!-- is main? -->

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
                    :selected="option.id == set.uslugi.sity"
                  >
                    {{ option.title }}
                  </option>
                </select>
              </div>

              <!-- main usluga -->
              <div
                v-if="form.is_main !== true"
                class="grid grid-cols-1 lg:grid-cols-2 gap-2"
              >
                <div>
                  <label
                    class="flex justify-between mt-5 mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    ><span class="text-sm font-bold"
                      >Выберите категорию услуг</span
                    >
                  </label>

                  <select
                    v-model="form.main_usluga_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  >
                    <option
                      v-for="option in set.main_uslugi"
                      :key="option.id"
                      v-bind:value="option.id"
                    >
                      {{ option.usl_name }}
                    </option>

                    <option v-if="uslugi.main" v-bind:value="uslugi.main.id">
                      {{ uslugi.main.name }}
                    </option>
                  </select>

                  <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    На одну услугу доступно всего одно объявление. Чтобы
                    разместить больше выбирайте подкатегорию. Если Вы не видите
                    категории, которая Вам требуется проверьте, нет ли у Вас уже
                    опубликованного объявления на странице
                    <a
                      :href="route('uslugi.user')"
                      class="font-medium text-blue-600 hover:underline dark:text-blue-500"
                      target="_blank"
                      >Ваших услуг</a
                    >
                  </p>
                </div>
                <!-- main usluga -->

                <!-- second usluga -->
                <div v-if="form.is_second !== true">
                  <label
                    class="flex justify-between mt-5 mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    ><span class="text-sm font-bold"
                      >Выберите подкатегорию</span
                    >
                  </label>
                  <select
                    v-if="set.second_uslugi"
                    v-model="form.second_usluga_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  >
                    <option
                      v-if="zero"
                      :selected="set.uslugi.second_usluga_id == 0"
                      v-bind:value="0"
                    >
                      Общая
                    </option>

                    <option
                      v-for="option in set.second_uslugi[form.main_usluga_id]"
                      :key="option.id"
                      v-bind:value="option.id"
                      :selected="option.id == set.uslugi.second_usluga_id"
                    >
                      {{ option.usl_name }}
                    </option>
                  </select>
                  <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    Объявление на странице
                    <a
                      :href="route('uslugi')"
                      class="font-medium text-blue-600 hover:underline dark:text-blue-500"
                      target="_blank"
                      >публичных услуг</a
                    >
                  </p>
                </div>
              </div>
              <!-- second usluga -->

              <label
                for="header"
                class="block text-sm font-medium leading-6 text-gray-900"
                >Название услуги</label
              >
              <textarea
                v-model="form.header"
                spellcheck="true"
                name="header"
                maxlength="55"
                class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 mb-5 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                rows="2"
              ></textarea>

              <label
                for="description"
                class="block text-sm font-medium leading-6 text-gray-900"
                >Ваше уникальное торговое предложение (до 150 символов, его
                видно в качестве описания в поисковой выдаче)</label
              >
              <textarea
                v-model="form.description"
                spellcheck="true"
                name="description"
                maxlength="200"
                class="h-20 form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 mb-5 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                rows="3"
              ></textarea>

              <label
                for="longdescription"
                class="block text-sm font-medium leading-6 text-gray-900"
                >Подробное описание услуги (не более 1000 симв.)</label
              >

              <editor spellcheck="true" v-model="form.longdescription" />

              <PopularQuestion
                :popular_question="set.uslugi.popular_question"
              />
              <AddVideo :video="set.uslugi.video" />

              <div class="w-full my-12">
                <AddPrice :prices="set.prices" 
                :userprices="set.userprices"
                :user="set.uslugi.user_id" 
                :usl_id="set.uslugi.id"/>
              </div>

              <!-- expirience price
              <div class="flex justify-evenly">
                <div class="">
                  <label
                    for="expirience-input"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white text-center"
                    >Лет опыта:</label
                  >
                  <div class="flex justify-center">
                    <input
                      v-model="form.expirience"
                      class="relative flex rounded-lg items-center text-center max-w-[8rem]"
                      type="number"
                      id="expirience-input"
                      name="expirience"
                      min="1"
                      max="30"
                      placeholder="1"
                      required
                    />
                  </div>
                  <p
                    class="mt-2 text-sm text-gray-500 dark:text-gray-400 mb-5 text-center"
                  >
                    до 30 лет
                  </p>
                </div>
                <div>
                  <label
                    for="price-input"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white text-center"
                    >Цена консультации:</label
                  >
                  <div class="flex justify-center">
                    <input
                      v-model="form.price"
                      class="relative flex rounded-lg items-center text-center max-w-[8rem]"
                      type="number"
                      id="price-input"
                      name="price"
                      min="0"
                      max="100000"
                      placeholder="1000"
                      required
                    />
                  </div>
                  <p
                    class="mt-2 text-sm text-gray-500 dark:text-gray-400 mb-5 text-center"
                  >
                    до 100 000 р
                  </p>
                </div>
              </div>
               expirience price-->

              <label
                for="phone"
                class="block text-sm font-medium leading-6 text-gray-900"
                >Телефон</label
              >

              <textarea
                v-model="form.phone"
                name="phone"
                maxlength="20"
                class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 mb-5 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                rows="2"
              ></textarea>

              <label
                for="address"
                class="block text-sm font-medium leading-6 text-gray-900"
                >Адрес (отображается в шапке и на странице услуги в разделе
                "адреса")</label
              >

              <textarea
                v-model="form.address"
                name="address"
                maxlength="100"
                class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 mb-5 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                rows="2"
              ></textarea>

              <label
                for="dopadress"
                class="block text-sm font-medium leading-6 text-gray-900"
                >Дополнительный адрес (не отображается в шапке, только на
                странице услуги в разделе "адреса")</label
              >

              <textarea
                v-model="form.dopadress"
                name="dopadress"
                maxlength="300"
                class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 mb-5 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                rows="2"
              ></textarea>

              <label
                for="maps"
                class="block text-sm font-medium leading-6 text-gray-900"
                >Код из яндекс карт (ссылка начиная с https )</label
              >

              <textarea
                v-model="form.maps"
                name="maps"
                maxlength="300"
                class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 mb-5 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                rows="5 "
              ></textarea>

              <button
                type="submit"
                class="my-5 inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800"
              >
                Обновить
              </button>
            </form>

            <!-- rating -->

            <Review
              v-if="set.uslugi.main_usluga_id"
              class="mt-5 pt-5"
              :mainuslugaid="set.uslugi.main_usluga_id"
              :uslugaid="set.uslugi.id"
              :admin="set.user.id"
              :errors="set.errors"
            />
            <!-- otziv -->
          </div>
        </div>
      </div>
    </div>

    <UslugaCropper :usluga="set.uslugi" />

    <SquareCropper :usluga="set.uslugi" />
  </Body>

  <MainFooter />
</template>