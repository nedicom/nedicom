<script setup>
import { ref, reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";

let set = defineProps({
  prices: Object,
  user: Number,
  usl_id: Number,
  userprices: Object,
});

let showPrice = ref(true);
let insertPrice = ref(null);
let form = reactive({insertPrice});

function sendPrice(x) {
  if (insertPrice.value) {
    Inertia.post(
      "/usluga/{url}/update",
      {
        updtpractice: x,
        users_id: set.user,
        uslugis_id: set.usl_id,
        prices_id: form.insertPrice.id,
        value: form.insertPrice.value,
        price: form.insertPrice.price,
      },
      { preserveScroll: true }
    );
  }
}
</script>

<template>
  <div id="scroll" class="">
    <p class="text-2xl font-semibold my-5 text-center">Ваши цены</p>
    <div v-if="set.userprices[0]">
      <div
        v-for="userprice in set.userprices"
        :key="userprice.id"
        class="inline-block"
      >
        <button
          type="button"
          class="text-center inline-flex items-center text-white bg-gradient-to-br from-purple-800 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1 me-2 mb-2"
        >
          {{ userprice.name }}
          <svg
            @click="insertPrice = userprice; sendPrice(2)"
            class="w-6 h-6 text-white hover:cursor-pointer"
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
              d="M6 18 17.94 6M18 18 6.06 6"
            />
          </svg>
        </button>
      </div>
    </div>
    <div class="text-center" v-else>Вы еще не добавили цены в объявление</div>

    <div
      :class="{ hidden: showPrice }"
      v-if="insertPrice"
      class="flex flex-col"
    >
      <p class="text-2xl font-semibold my-5 text-center">
      </p>
      <form class="flex flex-col">

        <p class="my-3 text-2xl te">{{ insertPrice.name }}</p>
        <div class="mb-5">
          <input
            type="text"
            v-model="form.insertPrice.id"            
            class="hidden"
          />

          <label
            for="large-input"
            class="block mb-2 text-sm font-medium text-gray-900"
            >Описание услуги</label
          >
          <textarea
            type="text"
            id="large-input"
            rows="3"
            v-model="form.insertPrice.value"
            class="block w-full p-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-base focus:ring-blue-500 focus:border-blue-500"
          >
          </textarea>
        </div>

        <div class="mb-5 grid grid-cols-1 md:grid-cols-3 gap-5">
          <div>
            <label
              for="base-input"
              class="block text-sm font-medium text-gray-900"
              >Минимальная цена</label
            >
            <input
              type="text"
              id="base-input"
              v-model="form.insertPrice.price"  
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
            />
          </div>

          <button
            type="button"
            @click.prevent="
              sendPrice(1);
              showPrice = true;
            "
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5"
          >
            Добавить
          </button>
          <button
            type="button"
            @click="
              insertPrice = null;
              showPrice = true;
            "
            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
          >
            Убрать
          </button>
        </div>
      </form>
    </div>

    <p class="text-2xl font-semibold my-5 text-center">Добавьте еще</p>

    <div v-for="price in set.prices" :key="price.id" class="inline-block">
      <div v-if="!set.userprices.some((item) => item.prices_id === price.id)">
        <a
          @click="
            insertPrice = price;
            showPrice = false;
          "
          href="#scroll"
          type="button"
          class="text-white text-xs bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg px-3 py-2 text-center me-2 my-1 block"
        >
          {{ price.name }}
      </a>
      </div>
    </div>
  </div>
</template>