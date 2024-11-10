<script setup>
defineProps({
    about: String,
    name: String,
});
</script>

<template>
    <!-- secondary banner - about -->
    <h1 class="text-4xl font-semibold text-grey text-center mt-10 pt-10">
        {{ name }}
      </h1>
      <div
        class="relative my-3 p-12 text-center"
        >
        <p class="my-6 py-6 ">{{ about }}</p>            
    </div> 
    <!-- secondary banner - about -->
</template>