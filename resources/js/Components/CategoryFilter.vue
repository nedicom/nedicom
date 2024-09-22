<script setup>
import { ref } from "vue";

const hideLinks = ref(true);

let scr = screen.width;

if (scr < 500) {
  hideLinks.value = false;
}

defineProps({
  category: Object,
  cityUrl: String,
  main_usluga_url: String,
  second_usluga_url: String,
  activeSts: Boolean,
});

defineEmits(['activeSts']);

</script>

<style scoped>
.passive {
  opacity: 0;
  height: 0;
}

.active {
  transition: opacity 0.5s, display 0.5s, height 0.5s;
  opacity: 100;
  height: 100%;
}
</style>

<template>
  <div class="mx-2">
    <h2 class="flex justify-center md:justify-end mb-2 md:mb-10">
      <div @click="hideLinks = !hideLinks"
        class="flex justify-center md:justify-end md:w-2/3 font-bold border-b md:border-none ">Юристы</div>
      <button @click="hideLinks = !hideLinks" type="button"
        class="flex items-center justify-between ml-4 font-medium rtl:text-right text-gray-500">
        <svg class="w-3 h-3 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor"
            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
      </button>
    </h2>
    <!-- Nested accordion -->
    <div :class="{ active: hideLinks, passive: !hideLinks }">
      <div id="accordion-flush" data-accordion="collapse"
        data-active-classes="bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
        data-inactive-classes="text-gray-500 dark:text-gray-400">
        <div v-for="(u, numb) in category" :key="numb">
          <h2 :id="'accordion-flush-heading-' + numb">
            <div class="flex justify-start md:justify-end my-3 md:my-2 mx-3 md:mx-0 text-sm md:text-base font-semibold">
              <a v-if="cityUrl" :href="route('offer.main', [cityUrl, u.url])"
                class="flex justify-start md:justify-end w-full md:w-2/3 transition hover:-translate-x-1 duration-300 cursor-pointer">
                <span :class="{ 'border-b-2 border-gray-300': u.url == main_usluga_url }">{{ u.usl_name }}</span>
              </a>
              <a v-else href="#" 
              @click="$emit('activeSts', true)"
               class="flex justify-start md:justify-end p-1 text-gray-900 text-right group">
                {{ u.usl_name }}
              </a>
              <button v-if="u.mainhassecond" type="button"
                class="flex items-center justify-between ml-4 font-medium rtl:text-right text-gray-500"
                :data-accordion-target="'#accordion-flush-body-' + numb" :aria-expanded="u.url == main_usluga_url"
                :aria-controls="'accordion-flush-body-' + numb">
                <svg v-if="u.mainhassecond.length > 0" data-accordion-icon class="w-3 h-3 text-gray-800 dark:text-white"
                  aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m19 9-7 7-7-7" />
                </svg>
                <svg v-else data-accordion-icon class="w-3 h-3 text-gray-800 dark:text-white" aria-hidden="true"
                  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                </svg>
              </button>

            </div>
          </h2>

          <!-- Subested accordion -->
          <div v-if="u.mainhassecond" :id="'accordion-flush-body-' + numb" class="hidden mr-10"
            :aria-labelledby="'accordion-flush-heading-' + numb">
            <div v-if="u.mainhassecond.length > 0">
              <span v-for="(second, secnumb) in u.mainhassecond" :key="secnumb">
                <a v-if="cityUrl" :href="route('offer.second', [cityUrl, u.url, second.url])"
                  :class="{ 'border-b-2 border-gray-300': second.url == second_usluga_url }"
                  class="flex justify-start md:justify-end transition hover:-translate-x-1 ml-5 md:ml-2 my-2 hover:ml-2 cursor-pointer text-sm md:text-base font-semibold md:font-normal text-gray-600">
                  {{ second.usl_name
                  }}</a>
              </span>

            </div>
          </div>
          <!-- Subested accordion -->

        </div>
      </div>
    </div>
    <!-- Nested accordion -->
  </div>
</template>