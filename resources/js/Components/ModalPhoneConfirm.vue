<script setup>
import { VueFinalModal } from "vue-final-modal";

let set = defineProps({
    phone: String,
    modalPageTitle: String,
    phoneto: String,
    avatarPath: String,
    lawyer: String,
});

const emit = defineEmits({
    close: "close",
});

</script>

<template>
    <VueFinalModal class="flex justify-center items-center"
        content-class="flex flex-col max-w-xl mx-4 p-4 bg-white dark:bg-gray-900 border dark:border-gray-700 rounded-lg space-y-2">
        <div class="relative">
            <button type="button" @click="emit('close')"
                class="absolute top-0 right-0 bg-white rounded-md p-2 inline-flex items-end justify-end text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                <span class="sr-only">Close menu</span>
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="px-6 py-6 lg:px-8 text-center">

            <div class="flex justify-center -space-x-2 overflow-hidden pb-6">
                <img v-if="set.avatarPath" class="inline-block h-12 w-12 rounded-full ring-2 ring-white"
                    :src="'https://nedicom.ru/' + set.avatarPath" width="50" height="50" :alt="set.modalPageTitle" />
                <img class="inline-block h-12 w-12 rounded-full ring-2 ring-white bg-gray-50"
                    src='https://nedicom.ru/mstile-144x144.png' width="50" height="50" :alt="set.modalPageTitle" />
            </div>

            <a :href="phoneto" class="mb-4 text-3xl font-medium text-gray-900">{{ set.phone }}</a>
            <p class="my-4 text-2xl font-medium text-gray-700">{{ set.lawyer }}</p>
            <p class="mb-4 text-xl font-medium text-gray-900">
                Вам удалось дозвониться?
            </p>
            <input type="button" onclick="ym(24900584, 'reachGoal', 'PHONE_BTN'); return true;" @click="emit('close')"
                value="Да"
                class="mr-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none cursor-pointer
            focus:ring-blue-300 font-medium rounded-lg text-base  sm:w-auto px-6 py-3 text-center" />

            <input type="button" @click="emit('close')" value="Нет"
                class="ml-1 text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none cursor-pointer 
            focus:ring-blue-300 font-medium rounded-lg text-base sm:w-auto px-6 py-3 text-center" />

        </div>
    </VueFinalModal>
</template>