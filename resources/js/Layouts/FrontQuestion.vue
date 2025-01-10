<script setup>
import { ref, watch, reactive, computed } from "vue";

let set = defineProps({
  mainoffers: Object,
  secondoffers: Object,
  city: String,
});

let form = reactive({
  question: "",
});

let searcharr = ref(["apple", "banana", "grapes", "mango", "orange"]);

const filterItems = (arr, query) => {
  searcharr.value = arr.filter(
      (element) => element.usl_name.toLowerCase().indexOf(query.toLowerCase()) !== -1,    
  );
};

const wordscounter = ref();
const hidden = ref(true);

watch(
  () => form.question,
  (question) => {
    wordscounter.value = question.length;
    hidden.value = true;
    //alert(set.mainoffers);
    filterItems(set.mainoffers, question);
    if (question.length > 1) {
      hidden.value = false;
    }
  }
);

function Selected(value) {
  alert(value);
}

function Hide() {
  hidden.value = true;
}

/**
 * Filter array items based on search criteria (query)
 */

let submit = () => {
  //buttonDisabled.value = true;
  Inertia.get("/uslugi", form);
};
</script>

<template>
  <div @click="filterItems(searcharr, 'ap')">123</div>
  {{ searcharr }}
  <form @submit.prevent="submit" class="flex justify-center mt-20">
    <div class="relative w-full md:w-1/3">
      <label
        for="search"
        class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white"
        >Search</label
      >
      <div class="relative">
        <div
          class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none"
        >
          <svg
            class="w-4 h-4 text-gray-500 dark:text-gray-400"
            aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 20 20"
          >
            <path
              stroke="currentColor"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"
            />
          </svg>
        </div>
        <input
          :onBlur="Hide"
          v-model="form.question"
          type="search"
          id="search"
          autocomplete="off"
          class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
          placeholder="Какую услугу будем искать?"
          required
        />
        <button
          type="submit"
          class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
        >
          поиск по услугам
        </button>
      </div>
      <!-- SearchBox Dropdown -->
      <div
        :class="{ hidden: hidden }"
        class="absolute z-50 w-full bg-white border border-gray-200 rounded-lg"
      >
        <div
          v-for="(item, num) in set.mainoffers"
          :key="num"
          @click="Selected(item.id)"
          class="max-h-72 rounded-b-lg overflow-hidden overflow-y-auto m-2 cursor-pointer"
        >
          {{ item.usl_name }}
        </div>
      </div>
      <!-- End SearchBox Dropdown -->
    </div>
  </form>
</template>