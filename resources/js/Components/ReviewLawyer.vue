<script setup>
import { ref, reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";

import Rating from "@/Components/Rating.vue";

const rate = ref("5");

const add = ref(false);

let set = defineProps({
  mainuslugaid: Number,
  uslugaid: Number,
  errors: Object,
  lwrid: Number,
  auth: Object,
});

let form = reactive({
  created_at: new Date().toISOString().slice(0, 19).replace("T", " "),
  mainusl_id: set.mainuslugaid ? (set.mainuslugaid) : null,
  usl_id: set.uslugaid ? (set.uslugaid) : null,
  lawyer_id: set.lwrid,
  user_id: set.auth ? (set.auth.id) : null,
  rating: rate,
  fio: set.auth ? (set.auth.name) : null,
  description: "",
});

function sendreview() {
  Inertia.post("/send/review", form, { preserveScroll: true });
}

function changeRate() {
  add.value == false ? (add.value = true) : null;
}
</script>

<template>
  <div class="text-sm grid grid-cols-1 content-center gap-2">
    <div v-if="!add">Ваш отзыв</div>
    <div v-if="!add" @click="changeRate" class="flex items-center justify-center">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.5"
        stroke="currentColor"
        class="w-6 h-6"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
        />
      </svg>
    </div>

    <Rating v-model="rate" @click="changeRate" />

  </div>
  <div v-if="add">
    <div v-if="!set.auth">
      <div class="text-sm my-2">
        нельзя просто так взять <br />и сделать отзыв без проверки
      </div>
      <a :href="route('login')" class="hover:underline font-semibold"
        >Войдите</a
      >
      или
      <a :href="route('register')" class="hover:underline font-semibold"
        >зарегистрируйтесь</a
      >
    </div>
    <div v-else>
      <div v-if="!set.auth.has_comment">
        <form @submit.prevent="sendreview" class="mt-2">
          <input
            v-model="form.fio"
            spellcheck="true"
            name="otzivfio"
            maxlength="155"
            placeholder="Ваше имя"
            required
            class="mb-1 form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
            rows="1"
          />
          <span v-if="errors">
            <div class="text-xs text-red-600 animate-pulse" v-if="errors.fio">
              {{ errors.fio }}
            </div>
          </span>

          <textarea
            v-model="form.description"
            spellcheck="true"
            name="otzivbody"
            maxlength="155"
            required
            class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
            rows="2"
            placeholder="Ваш отзыв"
          ></textarea>
          <span v-if="errors">
            <div
              class="text-xs text-red-600 animate-pulse"
              v-if="errors.description"
            >
              {{ errors.description }}
            </div>
          </span>

          <span v-if="errors">
            <div
              class="text-xs text-red-600 animate-pulse"
              v-if="errors.mainusl_id"
            >
              {{ errors.mainusl_id }}
            </div>
          </span>

          <button
            type="submit"
            class="inline-flex mt-2 items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800"
          >
            Опубликовать отзыв
          </button>
        </form>
      </div>
      <div v-else class="mt-2">у вас уже есть отзыв</div>
    </div>
  </div>
</template>