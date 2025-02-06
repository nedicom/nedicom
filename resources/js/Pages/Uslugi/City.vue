<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import Timeline from "@/Components/Timeline.vue";

import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import { Head } from "@inertiajs/inertia-vue3";
import PopupDialogue from "@/Layouts/PopupDialogue/PopupDialogue.vue";

let vars = defineProps({
  uslugi: Object,
  city: Object,
  main_usluga: Object,
  second_usluga: Object,
  auth: Object,
  cityheader: Object,
});


</script>

<template>

  <Head>
    <title>{{ vars.main_usluga.usl_name + " " + vars.city.title }}</title>
    <meta name="description" :content="'Объявления в городе ' + vars.city.title" />
  </Head>

  <MainHeader :auth="vars.auth" :city="vars.cityheader"/>

  <Body>

    <div itemscope itemtype="https://schema.org/Product">
      <Header :phone="'89788838978'" :address="null" />

      <Timeline :secondUslugi="vars.city.uslugies" :mainUsluga="null" :city="vars.city" />
    </div>

    <Breadcrumbs :main_usluga="vars.main_usluga" :second_usluga="vars.second_usluga" :city="vars.city" :usluga="null" />

  </Body>

  <MainFooter />

  <PopupDialogue />
</template>
