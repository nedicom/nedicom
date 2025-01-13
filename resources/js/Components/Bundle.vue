<script setup>
defineProps({
    bundles: Object,
});
</script>

<template>
    <div v-for="bundles in bundles.data" :key="bundles.id" class="flex justify-center mx-3 md:mx-0">
        <article
            class="min-w-full p-6 bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">




            <div class="flex justify-between items-center mb-5 text-gray-500">


                <!-- name component-->
                <div class="flex justify-between w-full">

                    <div class="flex w-full">

                        <a v-if="bundles.lawyer" :href="route('lawyer', bundles.id)" class="flex items-center">
                            <div>
                                <img :src="'https://nedicom.ru/' + bundles.apath" class="w-10 h-10 rounded-full"
                                    alt="{{ bundles.name }} avatar" />
                            </div>
                        </a>

                        <a v-else class="flex pointer-events-none justify-center w-10 h-10" aria-label="Home"
                            href="#"><svg width="50px" height="50px" fill="none" viewBox="0 0 50 50"
                                xmlns="http://www.w3.org/2000/svg" class="block w-auto fill-current text-gray-800">
                                <path d="m14.984 14.932 8.86-4.51 8.678 4.27.008 13.396-17.503.003-.043-13.159" style="
                            fill: none;
                            stroke: rgb(234, 0, 0);
                            stroke-width: 2.41544;
                            stroke-linecap: round;
                            stroke-linejoin: round;
                            stroke-miterlimit: 4;
                            stroke-dasharray: none;
                            stroke-opacity: 1;
                          "></path>
                                <path
                                    d="m2.705 22.19 8.859-4.51 8.679 4.27.008 13.397-17.503.003-.043-13.16m24.664 0 8.859-4.51 8.679 4.27.007 13.397-17.503.003-.042-13.16"
                                    style="
                            fill: none;
                            stroke: rgb(0, 0, 0);
                            stroke-width: 2.41544;
                            stroke-linecap: round;
                            stroke-linejoin: round;
                            stroke-miterlimit: 4;
                            stroke-dasharray: none;
                            stroke-opacity: 0.988235;
                          "></path>
                            </svg></a>


                        <span class="grid grid-cols-1 content-center ml-2">

                            <span class="text-base">
                                <a v-if="bundles.lawyer" :href="route('lawyer', bundles.id)" class="hover:underline hover:font-semibold">
                                    {{ bundles.name }}
                                </a>
                                <span v-else>
                                    Пользователь скрыт
                                </span>
                            </span>

                            <span class="mr-5 text-xs flex items-center">
                                <span v-if="bundles.type == '0' || bundles.type == 0"
                                    class="font-bold inline-flex items-center py-0.5 rounded">
                                    <svg class="mr-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z">
                                        </path>
                                    </svg>
                                    Вопрос
                                </span>

                                <span v-else
                                    class="font-bold inline-flex items-center py-0.5 rounded">
                                    <svg class="mr-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z">
                                        </path>
                                    </svg>
                                    Статья
                                </span>
                                <span class="ml-4">{{ bundles.created_at }}</span>
                            </span>
                        </span>
                    </div>




                    <div class="flex items-center text-xs">


                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            class="w-5 h-5 ml-5 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>

                        <p class="">{{ bundles.counter }}</p>

                    </div>

                </div>
                <!--name component -->





            </div>
            <h2 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">
                <a v-if="bundles.type == '0' || bundles.type == 0" :href="route('questions.url', bundles.url)">{{
                    bundles.aheader }}</a>
                <a v-else :href="route('articles/url', bundles.url)">{{
                    bundles.aheader
                    }}</a>
            </h2>
            <p class="mb-5 font-light text-gray-800 line-clamp-2">
                {{ bundles.abody }}
            </p>

            <div class="group flex item-center mb-2">
                <div v-if="bundles.comment">
                    <div class="flex items-center">
                        <img :src="'https://nedicom.ru/' + bundles.avatar" class="w-8 h-8 rounded-full"
                            alt="{{ bundles.name }} avatar" />
                        <span class="text-sm text-gray-500 ml-2 line-clamp-2">
                            {{ bundles.comment }}
                        </span>
                    </div>
                </div>
            </div>


            <div class="flex justify-end">

                <a v-if="bundles.type == '0' || bundles.type == 0" :href="route('questions.url', bundles.url)"
                    class="inline-flex items-center font-medium text-primary-600 dark:text-primary-500 hover:underline">
                    подробнее

                    <svg class="ml-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </a>

                <a v-else :href="route('articles/url', bundles.url)"
                    class="inline-flex items-center font-medium text-primary-600 dark:text-primary-500 hover:underline">
                    подробнее

                    <svg class="ml-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </a>
            </div>
        </article>
    </div>
</template>