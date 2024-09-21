<script setup>
import { ref } from "vue";

const video = ref(false);

defineProps({
  video: Array,
});

</script>

<style scoped>
.passive {
  transition: opacity 1s, display-none 1s, height 1s;
  opacity: 0;
  height: 0;
  overflow: hidden;
}
.active {
  transition: opacity 1s, display 1s, height 1s;
  opacity: 100;
  height: 240px;
  overflow: hidden;
}
</style>

<template>

  {{ video }}
  <div class="hidden md:block">
    <div class="flex flex-wrap justify-center">
      <div
        v-for="(arr, numb) in video"
        :key="arr"
        class="mt-1 w-full md:w-1/3 px-2"
      >
        <div v-if="numb < 3">
          <iframe
            :src="video.videolink"
            width="100%"
            height="240"
            allow="autoplay; encrypted-media; fullscreen; picture-in-picture; screen-wake-lock;"
            frameborder="0"
            allowfullscreen
          ></iframe>
        </div>
        <div v-if="numb > 2" :class="{ active: video, passive: !video }">
          <iframe
            :src="arr"
            width="100%"
            height="240"
            allow="autoplay; encrypted-media; fullscreen; picture-in-picture; screen-wake-lock;"
            frameborder="0"
            allowfullscreen
          ></iframe>
        </div>
      </div>
    </div>

    <div class="flex justify-center" v-if="video.length > 2">
      <div class="flex justify-center mt-2 border-t-2 w-1/2">
        <button
          @click="video = !video"
          type="button"
          class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
        >
          развернуть
        </button>
      </div>
    </div>
  </div>

  <!-- mobile-->
  <div class="block md:hidden">
    <div class="flex flex-wrap justify-center">
      <div
        v-for="(arr, numb) in video"
        :key="arr"
        class="mt-1 w-full md:w-1/3 px-2"
      >
        <div v-if="numb < 3">
          <iframe
            :src="arr"
            width="100%"
            height="240"
            allow="autoplay; encrypted-media; fullscreen; picture-in-picture; screen-wake-lock;"
            frameborder="0"
            allowfullscreen
          ></iframe>
        </div>
        <div v-if="numb > 2" :class="{ active: video, passive: !video }">
          <iframe
            :src="arr"
            width="100%"
            height="240"
            allow="autoplay; encrypted-media; fullscreen; picture-in-picture; screen-wake-lock;"
            frameborder="0"
            allowfullscreen
          ></iframe>
        </div>
      </div>

      <div class="flex justify-center" v-if="video.length > 2">
        <div class="flex justify-center mt-2 border-t-2 w-1/2">
          <button
            @click="video = !video"
            type="button"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
          >
            развернуть
          </button>
        </div>
      </div>
    </div>
  </div>
</template>