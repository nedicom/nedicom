<script setup>
import { reactive, ref } from "vue";
import { Inertia } from "@inertiajs/inertia";

let set = defineProps({
    cities: Object,
    routeurl: String,
    status: Boolean,
    activeSts: Boolean,
});

defineEmits(['activeSts']);

let form = reactive({
    cities: null,
});

let visible = false;

function city(id, title) {
    Inertia.get(
        set.routeurl,
        {
            cityid: id,
        },
        { preserveState: true },
    );
    form.cities = title;
    visible = false;
}


function opencity() {
    if (form.cities) {
        if (form.cities.length > 2) {
            Inertia.get(
                set.routeurl,
                {
                    city: form.cities,
                },
                { preserveState: true }
            );
            visible = true;
        };
    };
}

function clear() {
    form.cities = "";
    Inertia.get(
        set.routeurl,
        { preserveState: true },
    );
}
</script>

<template>
    <div class="flex justify-center md:justify-end mx-5 md:mx-0 mb-5">
        <div class="w-full md:w-2/3 relative h-16 md:h-24">
            <div class="relative z-0 w-full group">
                <input v-model="form.cities" @focus="$emit('activeSts', false)" @keyup="opencity" type="text"
                    name="city" id="city" :class="{ 'animate-pulse border-red-500 border-2': set.status }"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    placeholder=" " autocomplete="off" />
                <label for="floating_email"
                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Ваш
                    город</label>
                <div @click="clear" class="absolute inset-y-0 right-2.5 flex items-center cursor-pointer">
                    <svg class="w-5 h-5 text-gray-400 hover:text-gray-600" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
            </div>
            <p v-if="status" id="helper-text-explanation"
                class="mt-2 text-xs md:text-sm text-gray-500 dark:text-gray-400">Мы
                работаем в нескольких городах. Укажите свой</p>

            <div v-if="visible"
                class="absolute z-10 h-14 bottom-0 text-left text-gray-900 w-full text-md block cursor-pointer">
                <div v-for="(option, n) in cities" :key="option.id" v-bind:value="option.id"
                    class="px-4 py-3 bg-white drop-shadow-xl">
                    <div @click="city(option.id, option.title)">{{ option.title }}</div>
                </div>
            </div>
        </div>

    </div>
</template>