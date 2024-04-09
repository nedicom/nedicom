<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import DeleteModalConfirm from "@/Components/DeleteModalConfirm.vue";
import ContentHero from "@/Components/Hero/ContentHero.vue";
import EmptyHero from "@/Components/Hero/EmptyHero.vue";
import PopupDialogue from "@/Layouts/PopupDialogue/PopupDialogue.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import { Head } from "@inertiajs/inertia-vue3";
import { ModalsContainer, useModal } from "vue-final-modal";
import { Inertia } from "@inertiajs/inertia";

let title = ref("Мои вопросы");
let modalTitle = ref(null);
let questionId = ref(null);

defineProps({
  questions: "Array",
});

const { open, close } = useModal({
  component: DeleteModalConfirm,
  attrs: {
    title: modalTitle,
    id: questionId,
    onConfirm(id) {
      Inertia.post(`/questions/${id}/delete`), close();
    },
    onClose() {
      close();
    },
  },
  slots: {
    default: "<p>UseModal: The content of the modal</p>",
  },
});

const handleDelete = (id, title) => {
  questionId.value = id;
  modalTitle.value = title;
  open();
};
</script>

<template>
  <Head>
    <title>Мои вопросы</title>
    <meta name="description" content="На этой странице храняться вопросы, которые Вы задали" />
  </Head>

  <div class="min-h-screen flex flex-col">
    <MainHeader />

    <ModalsContainer />

    <Body>
      <div
        class="bg-white py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 overflow-hidden shadow-sm sm:rounded-lg"
      >
        <ContentHero v-if="questions.total > 0" :data="questions" :routeurl="'questions.url'"/>

        <EmptyHero
          v-else
          :header="'Вопросов пока нет'"
          :body="'Начните, задав новый вопрос'"
          :btn="'задать вопрос'"
          :btnroute="'questions.add'"
        />

        <!-- row -->
        <Pagination v-if="questions.total > 3" :links="questions.links" />
      </div>
    </Body>

    <MainFooter />

    <PopupDialogue />
  </div>
</template>

<script>
import Pagination from "@/Components/Pagination.vue";
export default {
  components: {
    Pagination,
  },
};
</script>
