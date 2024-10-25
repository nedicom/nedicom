<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import DeleteModalConfirm from "@/Components/DeleteModalConfirm.vue";
import ContentHero from "@/Components/Hero/ContentHero.vue";
import EmptyHero from "@/Components/Hero/EmptyHero.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import { Head } from "@inertiajs/inertia-vue3";
import { ModalsContainer, useModal } from "vue-final-modal";
import { Inertia } from "@inertiajs/inertia";

let title = ref("Мои ответы");
let modalTitle = ref(null);
let questionId = ref(null);

defineProps({
  answers: "Array",
  auth: Object,
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
  <Head title="Панель управления" />

  <div class="min-h-screen flex flex-col">
    <MainHeader :auth="auth" />

    <ModalsContainer />

    <Body>
      <div
        class="bg-white py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 overflow-hidden shadow-sm sm:rounded-lg"
      >
        <ContentHero v-if="answers.total > 0" :data="answers" :routeurl="'questions.url'" :auth="auth"/>

        <EmptyHero
          v-else
          :header="'Ответов пока нет'"
          :body="'Отвечайте на вопросы и повышайте свой рейтинг'"
          :btn="'перейти к вопросам'"
          :btnroute="'questions'"
        />

        <!-- row -->
        <Pagination v-if="answers.total > 3" :links="answers.links" />
      </div>
    </Body>

    <MainFooter />
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
