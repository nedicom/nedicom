<script setup lang="ts">
import { ModalsContainer, useModal } from "vue-final-modal";
import ModalPhoneConfirm from "./ModalPhoneConfirm.vue";

let set = defineProps({
  phone: String,
  modalPageTitle: String,
  phoneto: String,
  avatarPath: String,
  lawyer: String,
});

const { open, close } = useModal({
  component: ModalPhoneConfirm,
  attrs: {
    phone: set.phone,
    phoneto: set.phoneto,
    modalPageTitle: set.modalPageTitle,
    avatarPath: set.avatarPath,
    lawyer: set.lawyer,
    onClose() {
      close();
    },
  },
});
</script>

<template>
  <button
    onclick="ym(24900584, 'reachGoal', 'OPEN_PHONE'); return true;"
    @click="() => open()"
    class="inline-flex w-full md:w-64 mx-1 md:mx-auto py-2.5 px-2 md:px-5 focus:outline-none text-white sm:text-base text-sm bg-blue-700 hover:bg-blue-800 rounded-lg justify-center cursor-pointer"
  >
    <div class="flex items-center justify-center h-full">
      <svg
        class="mr-3 w-6 h-6 text-white"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.5"
        stroke="currentColor"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"
        />
      </svg>
    </div>
    <div class="flex flex-col items-center justify-center h-full">
      <div class="text-xs md:text-base">Показать телефон</div>
      <!--<div class="bg-gradient-to-r from-gray-50 to-blue-700 bg-clip-text text-transparent">{{ set.phone }}</div>-->
    </div>
  </button>
  <ModalsContainer />
</template>
