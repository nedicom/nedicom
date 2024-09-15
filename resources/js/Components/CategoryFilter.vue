<script setup>
defineProps({
    category: Object
})
</script>

<template>
    <!-- category filter -->
    <div class="my-10">
        <p class="flex justify-end px-10 font-bold uppercase ">категория</p>
        <ul class="space-y-1 font-medium">
            <li v-for="u in category" :key="u.id" class="flex justify-end">
                <span v-if="u.mainhasoffer.length > 0">
                    <a :href="route('offer.main', [u.mainhasoffer[0].cities.url, u.url])"
                        class="flex p-1 font-bold text-gray-900 text-right hover:underline group flex justify-end">
                        {{ u.usl_name }}
                    </a>
                    <span v-if="u.hasuslugi">
                        <ul v-if="u.hasuslugi.length > 0">
                            <li v-for="second in u.hasuslugi" :key="second.id" class="flex justify-end">
                                <a :href="route('offer.second', [u.mainhasoffer[0].cities.url, u.url, second.url])"
                                    class="text-right p-1 text-gray-900 hover:underline group">
                                    {{ second.usl_name }}
                                </a>
                            </li>
                        </ul>
                    </span>
                </span>

                <span v-else>
                    <a href="#" disabled class="flex p-1 text-gray-900 disabled hover:cursor-not-allowed">
                        {{ u.usl_name }}
                    </a>
                </span>

            </li>
        </ul>
    </div>
    <!-- category filter -->
</template>