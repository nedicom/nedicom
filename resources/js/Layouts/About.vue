<script setup>
defineProps({
    lawyer: Object,
});
</script>

<template>
    <!-- secondary banner - about -->
    <div class="py-10 w-full lg:w-2/3 text-center lg:text-left" id="#about">
        <div class="flex flex-col items-center lg:flex-row lg:items-left gap-4">
            <img class="rounded-full w-48 h-48" :src="'https://nedicom.ru/' + lawyer.avatar_path"
                alt="image description">
            <h1 class="text-4xl font-semibold py-5 lg:text-left">
                {{ lawyer.name }}
            </h1>
        </div>
        <p class="py-5">{{ lawyer.about }}</p>
        <a type="button" :href="'tel:'+ lawyer.phone"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-lg px-6 py-3.5 me-2 mb-2">
            {{ lawyer.phone }}</a>
    </div>
    <!-- secondary banner - about -->
</template>