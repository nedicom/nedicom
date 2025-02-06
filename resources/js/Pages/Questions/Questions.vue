<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import PopupDialogue from "@/Layouts/PopupDialogue/PopupDialogue.vue";
import Tg from "@/Layouts/TG/TeleGram.vue";
import { ref } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";

defineProps({
  questions: "Array",
  auth: Object,
  city: Object,
});

let title = ref("Вопросы");
</script>

<template>

  <Head>
    <title>{{ title }}</title>
    <meta name="description" content="вопросы" />
  </Head>

  <MainHeader :auth="auth" :city="city"/>

  <Header :ttl="title" />

  <Body>

    <div class="bg-white py-12 md:mx-12">
      <div v-if="questions.total > 0" class="grid md:grid-cols-3 gap-9 m-5">
        <!-- card -->
        <div v-for="question in questions.data" :key="question.id" class="flex justify-center">
          <div class="
                grid grid-cols-1 gap-4 content-between
                min-w-full
                p-6
                rounded-lg
                shadow-lg
                bg-white
                max-w-sm
              ">
            <p class="text-gray-700 text-base line-clamp-3 h-min-24 mb-2">
              {{ question.body }}

            </p>

            <div class="flex justify-between">
              <a :href="route('questions.url', question.url)" class="
                  text-blue-500
                  underline
                  dark:text-blue-500
                  hover:no-underline
                ">подробнее</a>
              <p class="text-sm text-gray-500">{{ question.created_at }}</p>
            </div>

          </div>
        </div>
        <!-- card -->

        <Pagination v-if="questions.total > 3" :links="questions.links" />

      </div>
    </div>
  </Body>

  <MainFooter />

  <MainFooter />

  <PopupDialogue />

</template>

<script>
import Pagination from "@/Components/Pagination.vue";
export default {
  components: {
    Pagination,
  },
};
</script>
