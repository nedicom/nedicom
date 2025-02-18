<script setup lang="ts">
import { ModalsContainer, useModal } from "vue-final-modal";
import ModalConfirm from "./ModalConfirm.vue";

let set = defineProps({
  modalPageTitle: String,
  ModalBtnText: String,
  secondtext: String,
});

const { open, close } = useModal({
  component: ModalConfirm,
  attrs: {
    modalPageTitle: set.modalPageTitle,
    onConfirm() {
      close();
    },
  },
  slots: {
    default: "<p>UseModal: The content of the modal</p>",
  },
});
</script>

<template>
  <button
    @click="() => open()"
    class="inline-flex items-center h-8 w-full md:w-48 xl:w-64 mx-auto py-1 px-2 xl:py-2.5 xl:px-5 font-medium text-white focus:outline-none bg-slate-600 rounded-lg hover:bg-slate-700 focus:z-10 focus:ring-4 focus:ring-gray-100 justify-center cursor-pointer"
    onclick="ym(24900584, 'reachGoal', 'PhoneForm_Click'); return true;"
  >
    <svg
      class="mr-3 w-4 h-4 xl:w-6 xl:h-6"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
      stroke-width="1"
      stroke="currentColor"
    >
      <path
        d="M14.25 9.75v-4.5m0 4.5h4.5m-4.5 0 6-6m-3 18c-8.284 0-15-6.716-15-15V4.5A2.25 2.25 0 0 1 4.5 2.25h1.372c.516 0 .966.351 1.091.852l1.106 4.423c.11.44-.054.902-.417 1.173l-1.293.97a1.062 1.062 0 0 0-.38 1.21 12.035 12.035 0 0 0 7.143 7.143c.441.162.928-.004 1.21-.38l.97-1.293a1.125 1.125 0 0 1 1.173-.417l4.423 1.106c.5.125.852.575.852 1.091V19.5a2.25 2.25 0 0 1-2.25 2.25h-2.25Z"
      />
    </svg>
    <span class="flex flex-col">
      <span class="text-xs 2xl:text-base">
        {{ set.ModalBtnText }}
      </span>
      <span class="text-xs">
        {{ set.secondtext }}
      </span>
    </span>
  </button>

  <ModalsContainer />
</template>
