<script setup>
defineProps({
    usluga: Object,
    main_usluga: Object,
    second_usluga: Object,
    city: Object,
});
</script>

<template>
    <!-- Breadcrumb -->
    <nav class="flex px-5 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700 overflow-x-auto"
        aria-label="Breadcrumb">
        <ol itemscope itemtype="https://schema.org/BreadcrumbList"
            class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse  list-none">
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"
                class="inline-flex items-center">
                <a itemprop="item" href="https://nedicom.ru/"
                    class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                    <svg class="w-3 h-3 mx-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                    </svg>
                    <span itemprop="name">Главная</span>
                    <meta itemprop="position" content="1" />
                </a>
            </li>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <div class="flex items-center">
                    <svg class="rtl:rotate-180 block w-3 h-3 mx-1 text-gray-400 " aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 9 4-4-4-4" />
                    </svg>
                    <a itemprop="item" href="https://nedicom.ru/uslugi"
                        class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">
                        <span itemprop="name">Услуги</span></a>
                    <meta itemprop="position" content="2" />
                </div>
            </li>

            <!-- main_usluga -->
            <li v-if="main_usluga" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <div class="flex items-center">
                    <svg class="rtl:rotate-180 block w-3 h-3 mx-1 text-gray-400 " aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 9 4-4-4-4" />
                    </svg>
                    <a itemprop="item" :href="route('uslugi.url', [main_usluga.url])"
                        class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white truncate ">
                        <span itemprop="name">{{ main_usluga.usl_name }}</span></a>
                    <meta itemprop="position" content="3" />
                </div>
            </li>

            <!-- second_usluga -->
            <li v-if="second_usluga" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <div class="flex items-center">
                    <svg class="rtl:rotate-180 block w-3 h-3 mx-1 text-gray-400 " aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 9 4-4-4-4" />
                    </svg>
                    <a itemprop="item" :href="route('uslugi.second.url', [main_usluga.url, second_usluga.url])"
                        class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white truncate ">
                        <span itemprop="name">{{ second_usluga.usl_name }}</span></a>
                    <meta itemprop="position" content="4" />
                </div>
            </li>

            <!-- city -->
            <li v-if="city" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <div class="flex items-center">
                    <svg class="rtl:rotate-180 block w-3 h-3 mx-1 text-gray-400 " aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 9 4-4-4-4" />
                    </svg>

                    <a itemprop="item" :href="route('show.city', [main_usluga.url, second_usluga.url, city.url])"
                        class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white truncate ">
                        <span itemprop="name">{{ city.title }}</span></a>
                    <meta itemprop="position" content="5" />
                </div>
            </li>

            <!-- usluga -->
            <li v-if="usluga" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"
                aria-current="page">
                <div class="flex items-center">
                    <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 9 4-4-4-4" />
                    </svg>
                    <a itemprop="item"
                        class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white pointer-events-none"
                        href="#">
                        <span itemprop="name"
                            class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400 truncate ">{{
                usluga.usl_name }}</span></a>
                    <meta v-if="city" itemprop="position" content="6" />
                    <meta v-else-if="usluga.is_second" itemprop="position" content="4" />
                    <meta v-else itemprop="position" content="3" />
                </div>
            </li>
        </ol>
    </nav>


</template>
