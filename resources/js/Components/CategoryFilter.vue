<script setup>
import { ref } from "vue";

const hideLinks = ref(false);

const hideusl = ref(0);

defineProps({
  category: Object,
  cityUrl: String,
  main_usluga_url: String,
  second_usluga_url: String,
});
</script>

<style scoped>
@media (max-width: 1024px) {
  .passive {
    transition: opacity 0.1s, display 0.1s, height 0.1s;
    opacity: 0;
    height: 0;
    visibility: collapse
  }

  .active {
    transition: opacity 1s, display 0.2s, height 0.2s;
    opacity: 100;
    height: 100%;
    visibility: visible
  }
}

@media (min-width: 1024px) {
  .active {
    transition: opacity 0.2s, display 0.2s, height 0.2s;
    opacity: 0;
    height: 0;
  }

  .passive {
    transition: opacity 0.2s, display 0.2s, height 0.2s;
    opacity: 100;
    height: 100%;
  }
}
</style>

<template>
  <div class="lg:mt-10 flex items-center flex-col">
    <h2 class="flex justify-center lg:justify-end px-3 lg:my-5 w-full">
      <button @click="hideLinks = !hideLinks" type="button"
        class="flex items-center justify-between lg:ml-4 font-medium text-gray-500">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
          class="w-6 h-6 shrink-0">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
        </svg>
      </button>
    </h2>
    <!-- Nested accordion -->

    <div :class="{ active: hideLinks, passive: !hideLinks }">
      <div v-for="(u, numb) in category" :key="numb" class="mx-3">
        <h2 class="flex justify-start md:justify-end my-3 md:my-2 mx-3 md:mx-0 text-sm xl:text-base font-semibold">
          <a v-if="cityUrl" :href="route('offer.main', [cityUrl, u.url])"
            class="flex justify-start md:justify-end w-full md:w-2/3 transition hover:-translate-x-1 duration-300 cursor-pointer text-right">
            <span :class="{
              'underline  underline-offset-4 decoration-gray-700 m':
                u.url == main_usluga_url,
            }">{{ u.usl_name }}
            </span>
          </a>
          <a v-else href="#" disabled class="flex justify-start md:justify-end p-1 text-gray-900 text-right group">
            {{ u.usl_name }}
          </a>
          <button v-if="u.mainhassecond" @click="hideusl = u.id" type="button"
            class="flex items-center justify-between ml-4 font-medium rtl:text-right text-gray-500"
            :data-accordion-target="'#accordion-flush-body-' + numb" :aria-expanded="u.url == main_usluga_url"
            :aria-controls="'accordion-flush-body-' + numb">
            <svg v-if="u.mainhassecond.length > 0" data-accordion-icon class="w-3 h-3 text-gray-800 dark:text-white"
              aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m19 9-7 7-7-7" />
            </svg>
            <svg v-else data-accordion-icon class="w-3 h-3 text-gray-800 dark:text-white" aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"></svg>
          </button>
        </h2>

        <!-- Subested accordion -->
        <div v-if="u.mainhassecond" class="relative overflow-hidden transition-all max-h-0 duration-700 ease-out pr-5"
          :class="{
            'max-h-96': u.id == hideusl,
            invisible: !(u.id == hideusl),
          }">
          <div v-if="u.mainhassecond.length > 0">
            <span v-for="(second, secnumb) in u.mainhassecond" :key="secnumb">
              <a v-if="cityUrl" :href="route('offer.second', [cityUrl, u.url, second.url])"
                class="flex justify-start md:justify-end transition hover:-translate-x-1 ml-5 md:ml-2 my-2 hover:ml-2 cursor-pointer text-sm xl:text-base font-semibold md:font-normal text-gray-600">
                <span :class="{
                  'underline  underline-offset-4 decoration-gray-600':
                    second.url == second_usluga_url,
                }" class="md:text-right">{{ second.usl_name }}</span></a>
            </span>
          </div>
        </div>
        <!-- Subested accordion -->
      </div>
    </div>
    <!-- Nested accordion -->
  </div>
</template>