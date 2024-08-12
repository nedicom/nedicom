<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';

const props = defineProps(['href', 'active']);

const classes = computed(() =>
    props.active
        ? 'flex justify-center min-w-full mx-5 p-3 my-1 rounded-lg bg-white  text-gray-600 focus:outline-none'
        : 'flex justify-center min-w-full mx-5 p-3 my-1 text-gray-600  rounded-lg hover:text-gray-800 hover:bg-white focus:outline-none focus:text-gray-700'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <div class="w-1/2"><slot /></div>
    </Link>
</template>
