<script setup>
import { reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";

let set = defineProps({
    cities: Object,
    routeurl: String,
});

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
}


function opencity() {
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
    <div class="flex items-center justify-left mb-6">
        
            <div class="w-full md:w-1/2 relative">
                <div class="relative">
                    <div class="absolute inset-y-0 left-2.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>

                    <input v-model="form.cities" @keyup="opencity" type="text" id="city" autocomplete="off"
                        class="p-4 m-1 pl-10 bg-gray-50  border border-gray-300 text-gray-900 text-md rounded-lg focus:border-blue-500 block w-full "
                        placeholder="Город" />

                    <div @click="clear" class="absolute inset-y-0 right-2.5 flex items-center cursor-pointer">
                        <svg class="w-5 h-5 text-gray-400 hover:text-gray-600" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                </div>

                <div v-if="visible"
                    class="absolute z-10 h-14 -bottom-14 text-left text-gray-900 w-full text-md block p-2.5 cursor-pointer">
                    <div v-for="(option, n) in cities" :key="option.id" v-bind:value="option.id"
                        class="px-4 py-3 bg-white drop-shadow-xl">
                        <div @click="city(option.id, option.title)">{{ option.title }}</div>
                    </div>
                </div>
            </div>
        
    </div>
</template>