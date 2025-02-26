<script setup>
import { Inertia } from "@inertiajs/inertia";

let set = defineProps({
    practice: Object,
    usl_id: Number,
    userpractice: Object,
});

function sendPractice(x, article_id) {
    if (article_id) {
        Inertia.post(
            "/usluga/{url}/update",
            {
                updtpractice: x,
                article_id: article_id,
                usluga_id: set.usl_id,
            },
            { preserveScroll: true }
        );
    }
}
</script>

<template>
    <div class="">
        <p class="text-2xl font-semibold my-5 text-center">Ваша практика в объявлении</p>

        <div v-if="set.userpractice[0]">
            <div v-for="userpr in set.userpractice" :key="userpr.article_id" class="inline-block">
                <button @click="sendPractice(2, userpr.article_id)" type="button"
                    class="text-center inline-flex items-center text-white bg-gradient-to-br from-purple-800 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1 me-2 mb-2">
                    {{ userpr.header }}
                    <svg class="w-6 h-6 text-white hover:cursor-pointer" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18 17.94 6M18 18 6.06 6" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="text-center" v-else>Добавьте практику в объявление, это повышает вероятность заинтересовать клиента
        </div>
        <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
        <div v-if="set.practice.length">
            <p class="text-2xl font-semibold my-5 text-center">Добавьте еще</p>

            <div v-for="oneprc in set.practice" :key="oneprc.id" class="">
                <p class="text-xl font-semibold my-5 text-center">{{ oneprc[0].usl_name }}</p>
                <div class="flex flex-wrap">
                    <div v-for="btn in oneprc" :key="btn.id" class="">
                        <a v-if="!set.userpractice.some((item) => item.article_id === btn.id)"
                            @click="sendPractice(1, btn.id)" href="#" type="button"
                            class="text-white text-xs bg-blue-500 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg px-3 py-2 text-center me-2 my-1 block">
                            {{ btn.header }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="p-5">
            <p class="text-2xl font-semibold my-5 text-center">Вы еще не написали ни одну статью</p>
            <div class="flex h-10">
                <a type="button" href="/articlesadd" target="_blank"
                    class="px-3 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 flex items-center"><svg
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span class="ml-1">добавить практику</span></a>
            </div>
        </div>
    </div>
</template>