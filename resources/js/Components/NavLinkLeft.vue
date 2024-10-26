<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';

const props = defineProps(['href', 'active']);

const classes = computed(() =>
    props.active
        ? 'border-b-2 border-indigo-500'
        : ''
);
</script>

<template>
    <Link :href="href" class="flex justify-end md:min-w-full md:mx-5 md:p-3 my-1 mr-1 text-gray-600 focus:outline-none transition hover:translate-x-1 duration-100"> 
        <div :class="classes"><slot /></div>
    </Link>
</template>
