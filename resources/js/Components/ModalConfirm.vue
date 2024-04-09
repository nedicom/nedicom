<script setup lang="ts">
import { VueFinalModal } from "vue-final-modal";
import { reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";

defineProps<{
    title?: string;
}>();

const emit = defineEmits<{
    (e: "confirm"): void;
}>();

let form = reactive({
    phone: "",
});

let submit = () => {
    Inertia.post("/phone/send", form), emit('confirm');
};
</script>

<template>
    <VueFinalModal
        class="flex justify-center items-center"
        content-class="flex flex-col max-w-xl mx-4 p-4 bg-white dark:bg-gray-900 border dark:border-gray-700 rounded-lg space-y-2"
    >
        <div class="relative">
            <button
                type="button"
                @click="emit('confirm')"
                class="absolute top-0 right-0 bg-white rounded-md p-2 inline-flex items-end justify-end text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
            >
                <span class="sr-only">Close menu</span>
                <svg
                    class="h-6 w-6"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>
        </div>
        <div class="px-6 py-6 lg:px-8">
            <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">
                Ваш номер телефона для связи
            </h3>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="flex justify-center">
                    <div>
                        <input
                            v-model="form.phone"
                            type="text"
                            min="7000000000"
                            max="999999999999"
                            name="phone"
                            id="phone"
                            class="text-3xl px-5 block py-2.5 ps-6 pe-0 text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder="+7 "
                            minlength="10"
                            maxlength="12"
                            size="12"
                            required
                        />
                    </div>
                </div>

                <div class="flex justify-between">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input
                                id="remember"
                                type="checkbox"
                                value=""
                                class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800"
                                required
                            />
                        </div>
                        <label
                            for="remember"
                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                            >Согласен с политикой сайта</label
                        >
                    </div>
                    <a
                        href="/policy"
                        class="text-sm text-blue-700 hover:underline dark:text-blue-500"
                        >Подробнее</a
                    >
                </div>
                <button
                    type="submit"
                    class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                >
                    Отправить
                </button>
            </form>
        </div>
    </VueFinalModal>
</template>

<style>
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>
