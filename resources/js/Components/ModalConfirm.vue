<script setup>
import { VueFinalModal } from "vue-final-modal";
import PhoneFormModal from "@/Components/PhoneFormModal.vue";
import { reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";
import PhoneForm from "@/Components/PhoneForm.vue";

let set = defineProps({
    modalPageTitle: String,
});

const emit = defineEmits({
    e: "confirm",
});

</script>

<template>
    <VueFinalModal class="flex justify-center items-center"
        content-class="flex flex-col max-w-xl mx-4 p-4 bg-white dark:bg-gray-900 border dark:border-gray-700 rounded-lg space-y-2">
        <div class="relative">
            <button type="button" @click="emit('confirm')"
                class="absolute top-0 right-0 bg-white rounded-md p-2 inline-flex items-end justify-end text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                <span class="sr-only">Close menu</span>
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="px-6 py-6 lg:px-8">
            <h3 class="mb-4 text-xl font-medium text-center text-gray-900 dark:text-white">
                {{ set.modalPageTitle }}
                Ваш номер телефона для связи
            </h3>

            <PhoneFormModal :modalPageTitle = "set.modalPageTitle" :modalid="'m'"/>

            <PhoneForm :modalPageTitle = "set.modalPageTitle"/>

            
        </div>
    </VueFinalModal>
</template>