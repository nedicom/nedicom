<script setup>
defineProps({
  category: Object,
  cityUrl: String,
});
</script>

<template>
  <!-- category filter -->
  <div class="my-10">
    <p class="flex justify-end px-10 font-bold uppercase">категория</p>

    <div
      id="tooltip-right"
      role="tooltip"
      class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
    >
      Сначала укажите город
      <div class="tooltip-arrow" data-popper-arrow></div>
    </div>
    <ul class="space-y-1 font-medium">
      <li v-for="u in category" :key="u.id" class="flex justify-end">
        <span>
          <a
            v-if="cityUrl"
            :href="route('offer.main', [cityUrl, u.url])"
            class="flex p-1 font-bold text-gray-900 text-right hover:underline group justify-end"
          >
            {{ u.usl_name }}
          </a>
          <a
            v-else
            data-tooltip-target="tooltip-right"
            data-tooltip-placement="right"
            href="#"
            disabled
            class="flex p-1 font-bold text-gray-900 text-right group justify-end"
          >
            {{ u.usl_name }}
          </a>

          <span v-if="u.hasuslugi">
            <ul v-if="u.hasuslugi.length > 0" class="px-2 mt-1">
              <li
                v-for="second in u.hasuslugi"
                :key="second.id"
                class="flex justify-end"
              >
                <a
                  :href="route('offer.second', [cityUrl, u.url, second.url])"
                  class="text-right p-1 text-sm text-gray-900 hover:underline group"
                >
                  {{ second.usl_name }}
                </a>
              </li>
            </ul>
          </span>
        </span>
      </li>
    </ul>
  </div>
  <!-- category filter -->
</template>