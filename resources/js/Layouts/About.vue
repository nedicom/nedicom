<script setup>
const props = defineProps({
    about: String,
});
</script>

<template>
    <!-- secondary banner - about -->
    <h1 class="text-4xl font-semibold text-grey text-center mt-10 pt-10">
        В работе адвоката главное - репутация
      </h1>
      <div
        class="relative my-3 p-12 text-center"
        >
        <h5 class="my-6 py-6 ">{{ about }}</h5>            
    </div> 
    <!-- secondary banner - about -->
</template>