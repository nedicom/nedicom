<script setup>
import { VueFinalModal } from "vue-final-modal";
import { reactive, ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";

let set = defineProps({
  profile: Boolean,
  reloadpage: Boolean,
  newurl: String,
  mainurl: Number,
  secondurl: String,
});



let form = reactive({
  city: "",
  cityid: "",
  changeCityFromProfile: set.profile,
  cityurl: set.cityurl,
  mainurl: set.mainurl,
  secondurl: set.secondurl,
  reloadpage: set.reloadpage,
});

let cities = ref(null);
let simplecities = ref(null);
let findcity = ref(null);
let regionname = ref(false);

const emit = defineEmits({
  e: "confirm",
});

let submit = () => {
  Inertia.post("/setcity", form, { preserveScroll: true });
};

const getData = () => {
  fetch("/getcities", {
    headers: { "Content-type": "application/json" },
  })
    .then((res) => res.json())
    .then((data) => {
      cities.value = data[0];
      simplecities.value = data[1];
    })
    .catch((error) => {
      console.log("Looks like there was a problem: \n", error);
    });
};

getData();

const filterItems = (arr, query) => {
  return (findcity.value = arr.filter(
    (element) => element.title.toLowerCase().indexOf(query.toLowerCase()) !== -1
  ));
};

watch(
  () => form.city,
  (city) => {
    filterItems(simplecities.value, city);
  }
);
</script>

<template>
  <VueFinalModal class="flex justify-center items-center"
    content-class="flex flex-col max-w-xl mx-4 p-4 bg-white md:w-2/3 border dark:border-gray-700 rounded-lg space-y-2">
    <div class="relative">
      <button type="button" @click="emit('confirm')"
        class="absolute top-0 right-0 bg-white rounded-md p-2 inline-flex items-end justify-end text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
        <span class="sr-only">Close menu</span>
        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
          aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <div class="px-6 py-6 lg:px-8">
      <h3 class="mb-4 text-xl font-medium text-center text-gray-900 dark:text-white">
        Давайте узнаем откуда Вы
      </h3>

      <form @submit.prevent="submit">
        <div>
          <input v-model="form.city" type="string" name="city" id="city" autocomplete="off"
            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
            required />
        </div>
      </form>

      <div v-if="cities != null" class="grid grid-cols-2 divide-x mt-5 gap-2 h-40">
        <div class="pl-2 overflow-y-auto overflow-x-hidden">
          <div v-for="(region, n) in cities" :key="n" class="">
            <div @click="(regionname = n), (findcity = false)"
              class="hover:cursor-pointer hover:underline transition hover:translate-x-1 duration-300">
              {{ n }}
            </div>
          </div>
        </div>

        <div v-if="findcity" class="pl-4 overflow-y-auto overflow-x-hidden">
          <div v-for="(city, n) in findcity" :key="n" @click="
            (form.city = city.title),
            (form.cityid = city.id),
            (form.cityurl = city.url),
            $emit('confirm'),
            submit()
            " class="hover:cursor-pointer hover:underline transition hover:-translate-x-1 duration-300 ml-2">
            {{ city.title }}
          </div>
        </div>

        <div v-else class="pl-4 overflow-y-auto">
          <div v-for="(city, n) in cities[regionname]" :key="n" @click="
            (form.city = city.title),
            (form.cityid = city.id),
            (form.cityurl = city.url),
            $emit('confirm'),
            submit()
            " class="hover:cursor-pointer hover:underline transition hover:-translate-x-1 duration-300 ml-2">
            {{ city.title }}
          </div>
        </div>
      </div>
    </div>
  </VueFinalModal>
</template>