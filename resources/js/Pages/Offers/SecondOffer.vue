<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import OfferCard from "@/Components/OfferCard.vue";
import CategoryFilter from "@/Components/CategoryFilter.vue";
import CityFilter from "@/Components/CityFilter.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import { Head } from "@inertiajs/inertia-vue3";
import PopupDialogue from "@/Layouts/PopupDialogue/PopupDialogue.vue";
import { ref } from "vue";

let set = defineProps({
    city: Object,
    main_usluga: Object,
    second_usluga: Object,
    uslugi: Object,
    category:  Object,
    cities:  Object,
});

let status = ref(false);

function alertForm (x) {
  status.value = x;
}
</script>

<template>
    <Head>
        <title>{{ set.main_usluga.usl_name }}  {{ set.city.title }}</title>
        <meta name="description" :content="main_usluga.usl_desc" />
    </Head>

    <MainHeader />

    <Body>
        <div class="bg-white py-6 grid grid-cols-1 md:grid-cols-3">
            <CategoryFilter :category="set.category" :cityUrl="city.url" :main_usluga_url="main_usluga.url" :second_usluga_url="second_usluga.url" @activeSts="alertForm"/>

            <div class="w-full col-span-2 mx-auto sm:px-6 lg:px-8">
                <CityFilter :cities="set.cities" :routeurl="'/offers/' + city.url + '/' + main_usluga.url + '/' + second_usluga.url" @activeSts="alertForm"/>

                <div v-if="set.uslugi" class="">
                    <!-- card -->
                        <div v-for="offer in set.uslugi" :key="offer.id" class=" mx-3 md:mx-0">
                            <OfferCard :offer="offer" :city="set.cities" />
                        </div>
                    <!-- card -->
                </div>
            </div>

        </div>
    </Body>

    <MainFooter />

    <PopupDialogue />
</template>