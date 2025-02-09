<script setup>
defineProps({
  blockdata: Object,
});
</script>

<template>
  <section v-if="blockdata" class="bg-white">
    <div class="gap-16 items-center py-8 px-4 mx-auto max-w-screen-xl md:grid md:grid-cols-2 md:py-16 md:px-6">
      <div class="font-light text-gray-500 sm:text-lg">
        <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900">
          {{blockdata.usl_name}}
        </h2>
        <p class="mb-4">
          {{blockdata.usl_desc}}
        </p>
      </div>
      <div class="mt-8">
        <img class="w-full rounded-lg" :src="'https://nedicom.ru/' + blockdata.file_path" alt="" />
      </div>
    </div>
  </section>
</template>
