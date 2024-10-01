<script setup>
import { ref } from "vue";

const hideVideo = ref(false);

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
  height: 360px;
  overflow: hidden;
}
</style>

<template> 
  <div v-if="video" class="hidden md:block" >
    <div v-if="video[0].videolink" class="flex flex-wrap justify-center">
      <div v-for="(link, numb) in video" :key="numb" class="mt-1 w-full md:w-1/3 px-2">
        <div v-if="numb < 3">
          <iframe :src="link.videolink" width="100%" height="360"
            allow="encrypted-media; fullscreen; picture-in-picture; screen-wake-lock;" frameborder="0"
            allowfullscreen></iframe>
        </div>
        <div v-if="numb > 2" :class="{ active: hideVideo, passive: !hideVideo }">
          <iframe :src="link.videolink" width="100%" height="360"
            allow="encrypted-media; fullscreen; picture-in-picture; screen-wake-lock;" frameborder="0"
            allowfullscreen></iframe>
        </div>
      </div>
    </div>

    <div class="flex justify-center" v-if="video.length > 3">
      <div class="flex justify-center mt-2 border-t-2 w-1/2">
        <button @click="hideVideo = !hideVideo" type="button"
          class="mt-4 text-blue-700 border border-blue-700 hover:bg-blue-700 hover:text-white hover:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center">
          <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
          </svg>
          <span class="sr-only">Icon description</span>
        </button>
      </div>
    </div>
  </div>

  <!-- mobile-->
  <div v-if="video" class="block md:hidden">
    <div class="flex flex-wrap justify-center">
      <div v-for="(link, numb) in video" :key="numb" class="mt-1 w-full md:w-1/3 px-2">
        <div v-if="numb < 1">
          <iframe :src="link.videolink" width="100%" height="240"
            allow="encrypted-media; fullscreen; picture-in-picture; screen-wake-lock;" frameborder="0"
            allowfullscreen></iframe>
        </div>
        <div v-if="numb > 1" :class="{ active: hideVideo, passive: !hideVideo }">
          <iframe :src="link.videolink" width="100%" height="240"
            allow="encrypted-media; fullscreen; picture-in-picture; screen-wake-lock;" frameborder="0"
            allowfullscreen></iframe>
        </div>
      </div>

      <div class="flex justify-center" v-if="video.length > 1">
        <div class="flex justify-center mt-2 border-t-2 w-1/2">
          <button @click="hideVideo = !hideVideo" type="button"
            class="mt-4 text-blue-700 border border-blue-700 hover:bg-blue-700 hover:text-white hover:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
            </svg>
            <span class="sr-only">Icon description</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>