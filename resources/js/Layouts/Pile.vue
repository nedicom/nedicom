<script setup>
const props = defineProps({
    uslugislider: Object,
    city: String,
});
</script>

<template>
    <div class="flex justify-center mt-20">
        <div class="bg-slate-100 w-full xl:w-2/3 grid grid-cols-2 lg:grid-cols-4 gap-4 px-10 py-5 rounded-lg">
            <div v-for="(item, numb) in props.uslugislider" :key="numb" class="bg-white rounded-lg border border-gray-200">
                <a :href="'/uslugi/' + props.city +'/' + item.url" class="">
                    <img :src="'https://nedicom.ru/' + item.file_path" class="rounded-t-lg"/>
                    <h4 class="text-sm md:text-xl py-3 text-center font-bold tracking-tight text-gray-900">
                        {{ item.usl_name }}
                    </h4>
                </a>
            </div>
        </div>
    </div>
</template>