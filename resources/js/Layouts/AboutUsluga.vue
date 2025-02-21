<script setup>
defineProps({
    usluga: Object,
    lawyer: Object,
    main_usluga: Object
});
</script>

<template>
    <!-- secondary banner - about -->
    <section class="bg-white dark:bg-gray-900" id="about">
        <div
            class="gap-8 items-center py-8 px-4 mx-auto max-w-screen-xl xl:gap-16 md:grid md:grid-cols-2 sm:py-16 lg:px-6">
            <div class="mt-4 md:mt-0">
                <h1 itemprop="name" class="mb-4 text-4xl tracking-tight font-bold text-gray-900">{{
                    usluga.usl_name }}</h1>
                <p itemprop="description" class="mb-6 font-light text-gray-500 md:text-lg">{{
                    usluga.usl_desc }}</p>

                <div class="mt-2 font-bold text-gray-600 text-xl text-center md:text-right">
                    @ {{ lawyer.name }}
                </div>
                <div class="font-medium text-gray-900 text-base text-center md:text-right">
                    {{ main_usluga.usl_name }}.
                    <span v-if="usluga.cities">{{ usluga.cities.title }}</span>
                </div>
            </div>
            <img itemprop="image" class="w-full rounded-3xl" :src="'https://nedicom.ru/' + usluga.file_path" :alt="usluga.usl_name">
        </div>
    </section>
    <!--secondary banner - about -->
</template>