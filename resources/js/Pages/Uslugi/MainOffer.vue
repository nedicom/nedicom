<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import UslugiCard from "@/Layouts/UslugiCard.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import { Head } from "@inertiajs/inertia-vue3";
import PopupDialogue from "@/Layouts/PopupDialogue/PopupDialogue.vue";

let vars = defineProps({
    city: Object,
    main_usluga: Object,
    uslugi: Object,
    flash: Object,
});

</script>

<template>
    <FlashMessage :message="flash.message" />

    <Head>
        <title>{{ main_usluga.usl_name }}</title>
        <meta name="description" :content="main_usluga.usl_desc" />
    </Head>

    <MainHeader />

    <Body>
        <div>
            <Header :phone="'89788838978'" :address="null" />

            <UslugiCard :uslugi="vars.uslugi" :city="vars.city" :main_usluga="vars.main_usluga" />
        </div>

    </Body>

    <MainFooter />

    <PopupDialogue />
</template>