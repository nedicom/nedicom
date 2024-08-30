<script setup>
defineProps({
    secondUslugi: "Object",
    mainUsluga: "Object",
});
</script>

<template>

    <div class=" pt-5">
        <h1
            class=" flex justify-center mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white">
            {{mainUsluga.usl_name}} </h1>
        <p class="flex justify-center text-lg font-normal text-gray-500 lg:text-xl dark:text-gray-400">{{mainUsluga.usl_desc}}</p>
    </div>

    <div class="flex justify-center mt-5  pt-5">
        <ol class="relative border-l border-gray-200 dark:border-gray-700 list-none">
            <li v-for="usluga in secondUslugi" :key="usluga.id" class="mb-10 ms-4">
                <div
                    class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -left-1.5 border border-white dark:border-gray-900 dark:bg-gray-700">
                </div>
                <time v-if="usluga.cities" class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{ usluga.cities.title }}</time>
                <time v-else class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">услуга</time>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ usluga.usl_name }}</h3>
                <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">{{ usluga.usl_desc }}</p>
                <a :href="usluga.url"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                    подробнее <svg class="w-3 h-3 ml-2 rtl:rotate-180" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M1 5h12m0 0L9 1m4 4L9 9" />
                    </svg></a>
            </li>
        </ol>
    </div>
</template>
