<script setup>
defineProps({
    sliderheader: String,
    practice: Array,
});
</script>

<template>
    <section id="prctglr" class="w-full flex justify-center">
        <div class="md:w-3/4 px-3 md:px-0">
            <h3 class="text-4xl mx-12 my-1 font-semibold">Практика</h3>
            <p class="mb-6 mx-12 font-light text-gray-500 md:text-lg">Возможно Ваше дело
                далеко не первое...</p>
            <ol class="relative border-l border-gray-200 list-none">
                <li v-for="(cards, name) in practice" :key="name" class="mb-10 ml-4">
                    <span
                        class="absolute flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full -left-5">
                        <svg class="w-5 h-5 text-gray-600" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                        </svg>
                    </span>
                    <time class="ml-3 text-4xl text-gray-400">
                        {{ name }} год</time>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                        <div v-for="card in cards" :key="card.id" class="w-full flex justify-center md:p-10">
                            <a :href="route('articles/url', card.url)" target="_blank"
                                :aria-label="'практика юриста - ' + card.header"
                                class="md:flex w-full items-center bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100">
                                <img class="object-cover w-full md:w-1/2 md:rounded-l-lg"
                                    :src="'https://nedicom.ru/' + card.practice_file_path" :alt="card.header">
                                <div class="flex flex-col justify-between p-4 leading-normal">
                                    <h4 class="mb-2 text-lg font-bold text-gray-900">{{
                                        card.header
                                    }}</h4>
                                    <p class="mb-3 text-base font-normal text-gray-500">{{ card.description }}
                                    </p>
                                    <div>
                                        <a :href="route('articles/url', card.url)" target="_blank"
                                            :aria-label="'практика юриста - ' + card.header"
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                                            подробнее
                                            <svg class="w-3 h-3 ml-2 rtl:rotate-180" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M1 5h12m0 0L9 1m4 4L9 9" />
                                            </svg></a>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </li>

            </ol>

        </div>



    </section>
</template>