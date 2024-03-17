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
  messages: "Array",
});

let title = ref("Сообщения");
</script>

<template>

  <Head>
    <title>{{ title }}</title>
    <meta name="description" content="Сообщения" />
  </Head>

  <MainHeader />

  <Header :ttl="title" />

  <Body>

    <div class="bg-white py-12 md:mx-12 flex justify-center">
      <div v-if="messages.total > 0" class="grid grid-cols-1 gap-9 m-5 md:w-1/2">
        <!-- card -->
        <div v-for="messages in messages.data" class="flex justify-center">
          <div class="
                grid grid-cols-1 gap-4 content-between
                min-w-full
                p-6
                rounded-lg
                shadow-lg
                bg-white
                max-w-sm
              ">
            <div class="flex justify-between">
              <p class="text-gray-500 text-md">
                {{ messages.created_at }}
              </p>
              <p class="text-gray-500 text-xs">
                откуда - {{ messages.location }}
              </p>
            </div>
            <p class="text-gray-700 text-base mb-2">
            <div v-for="key in JSON.parse(messages.json)" >
               - {{ key.user_message }}
               {{ key.ai_message }}
            </div>
            </p>
          </div>
        </div>
        <!-- card -->

        <Pagination v-if="messages.total > 3" :links="messages.links" />

      </div>
    </div>
  </Body>

  <MainFooter />

  <MainFooter />

  <Tg />

</template>

<script>
import Pagination from "@/Components/Pagination.vue";
export default {
  components: {
    Pagination,
  },
};
</script>
