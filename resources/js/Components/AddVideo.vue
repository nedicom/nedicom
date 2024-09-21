<script setup>
import { ref } from "vue";

let props = defineProps({
  video: Array,
});

let items = ref(props.video);

function addVideo() {
  items.value.push({ videolink: "" });
}

function deleteVideo() {
  items.value.splice(-1);
}

//oninput='this.value = this.value.split("\" width=")[0].split("src=\"")[1]'
</script>

<template>
  <div v-for="(item, numb) in props.video" :key="numb" class="col-span-3 mb-5">
    <label
      for="otzivbody"
      class="block text-sm font-medium leading-6 text-gray-900"
      >видео из ВК</label
    >
    <textarea    
      v-model="item.videolink"
      spellcheck="true"
      name=""
      maxlength="555"
      class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
      placeholder="Код видео из ВК (на видео жмем поделиться, потом на вкладке экспорт копируем код который начинается с <iframe). Обязательно убирайте галочку с Автостарт"
      rows="2"
    ></textarea>
  </div>

  <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 text-center">
    Добавление или удаление видео
  </p>

  <div class="flex items-center justify-evenly">
    <div
      :onClick="addVideo"
      class="my-5 inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800"
    >
      добавить видео
    </div>

    <div
      :onClick="deleteVideo"
      class="my-5 inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-red-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800"
    >
      удалить видео
    </div>
  </div>
</template>