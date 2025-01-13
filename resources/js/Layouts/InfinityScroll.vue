<script setup>
import Bundle from "@/Components/Bundle.vue";

defineProps({
    bundles: Object,
});
</script>

<template>
    <div class="pt-20 flex justify-center mx-3 md:mx-0">
        <div class="w-full md:w-1/3 flex flex-col gap-9">
            <Bundle :bundles="bundles" />
        </div>
    </div>
</template>