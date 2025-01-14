<script setup>
import { ref, watch, reactive } from "vue";
import { ModalsContainer, useModal } from 'vue-final-modal';
import CitySet from '@/Components/CitySet.vue';

let set = defineProps({
  mainoffers: Object,
  secondoffers: Object,
  city: Object,
});

let form = reactive({
  question: "",
});

const alloffers = set.mainoffers.concat(set.secondoffers);
const wordscounter = ref();
const hidden = ref(true);

const searcharr = ref(null);
const startarr = alloffers.sort(() => 0.5 - Math.random()).slice(0, 5);

const filterItems = (arr, query) => {
  searcharr.value = arr.filter(
    (element) => element.usl_name.toLowerCase().indexOf(query.toLowerCase()) !== -1,
  );
};

function Search(x) {
  searcharr.value = startarr;
  hidden.value = x;
}

watch(
  () => form.question,
  (question) => {
    wordscounter.value = question.length;
    hidden.value = true;
    filterItems(alloffers, question);
    if (question.length > 1) {
      hidden.value = false;
    }
  }
);

const { open, close } = useModal({
  component: CitySet,
  attrs: {
    modalPageTitle: 'front',
    onConfirm() {
      close()
    },
  },
})

let submit = () => {
  Inertia.get("/uslugi", form);
};
</script>

<template>{{ city }}
  <form @submit.prevent="submit" class="flex justify-center mt-20">
    <div class="w-full md:w-1/3 flex justify-center">
      <div class="">
        <button  @click="() => open()"
          class="text-white inline-block h-full rounded-l-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium text-sm px-4 py-2">
          <span v-if="city.title">{{ city.title }}</span>
          <span v-else>город</span>        
        </button>
      </div>

      <div class="relative w-full">

        <div class="relative w-full">
          <div class="absolute inset-y-0 left-1 flex items-center pl-3 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 20 20">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
            </svg>
          </div>

          <input v-model="form.question" @focus="Search(false)" type="search" id="search"
            autocomplete="off"
            class="flex w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300  rounded-r-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Какую услугу будем искать?" required />

          <a v-if="searcharr" @click="alert(1)" :href="'/uslugi/' + city.url + '/' + searcharr[0].url"
            class="text-white absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">
            найти
          </a>
          <a v-else href="#"
            class="text-white absolute cursor-default right-2.5 bottom-2.5 bg-blue-400 font-medium rounded-lg text-sm px-4 py-2">
            найти
          </a>
        </div>

        <!-- SearchBox Dropdown -->
        <div :class="{ hidden: hidden }" class="absolute z-50 w-full bg-white border border-gray-200 rounded-lg">
          <div v-for="(item, num) in searcharr" :key="num" @click="Selected(item.id)"
            class="max-h-72 rounded-lg overflow-hidden overflow-y-auto m-1 p-1 cursor-pointer hover:bg-gray-200">
            <a :href="'/uslugi/' + city.url + '/' + item.url">{{ item.usl_name }}</a>
          </div>
        </div>
        <!-- SearchBox Dropdown -->

      </div>
    </div>
  </form>
  <ModalsContainer />
</template>