<script setup>
import Bundle from "@/Components/Bundle.vue";

defineProps({
    bundles: Object,
    auth: Object,
});
</script>

<template>
    <div class="pt-20 flex justify-center  px-3 md:px-10">
        <div class="w-full grid grid-cols-2 gap-4 place-content-center">
            <Bundle :bundles="bundles" :auth="auth"/>
        </div>
    </div>
</template>