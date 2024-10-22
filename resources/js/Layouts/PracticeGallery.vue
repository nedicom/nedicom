<script setup>
defineProps({
    sliderheader: String,
    practice: Array,
});
</script>

<template>
    <section id="prctglr">
        <h3 class="text-4xl mx-12 my-1 font-semibold text-grey md:text-center">Практика</h3>
        <p class="mb-6 mx-12 font-light text-gray-500 md:text-lg dark:text-gray-400 md:text-center">Возможно Ваше дело далеко не первое...</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div v-for="card in practice" :key="card.id" class="w-full flex justify-center p-10">
                <a :href="route('articles/url', card.url)" target="_blank"
                    class="md:flex w-full items-center bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100">
                    <img class="object-cover w-full md:w-1/2 md:rounded-l-lg"
                        :src="'https://nedicom.ru/' + card.practice_file_path" :alt="card.header">
                    <div class="flex flex-col justify-between p-4 leading-normal">
                        <h4 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ card.header
                            }}</h4>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{ card.description }}</p>
                    </div>
                </a>
            </div>
        </div>
    </section>
</template>