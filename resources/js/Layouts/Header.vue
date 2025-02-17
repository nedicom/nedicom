<script setup>
import Modal from "@/Components/Modal.vue";
import ModalPhone from "@/Components/ModalPhone.vue";
import WaBtn from "@/Components/WaBtn.vue";

let phone = "+79788838978";

const props = defineProps({
  address: String,
  phone: String,
  modalPageTitle: String,
  avatarPath: String,
  lawyer: String,
});

if (props.phone) {
  phone = props.phone;
}

let phoneto = "tel:" + phone;

let ModalBtnText = "Обратный звонок";
</script>


<template>
  <!-- Page Heading -->
  <noscript>
    <div>
      <img
        src="https://mc.yandex.ru/watch/24900584"
        style="position: absolute; left: -9999px"
      />
    </div>
  </noscript>
  <header class="bg-white shadow inset-x-0 -top-1 h-22 md:h-16 z-40"><!--stycky-->
    <div
      class="grid grid-cols-2 lg:grid-cols-4 md:gap-2 2xl:w-3/4 font-semibold text-gray-800 leading-tight text-center mx-auto pt-2 pb-1 md:py-3 px-4 sm:px-6 lg:px-4"
    >
      <!--modal btn -->
      <div
        class="hidden lg:flex items-center justify-center md:justify-start px-2"
      >
        <Modal
          v-if="props.modalPageTitle"
          :ModalBtnText="ModalBtnText"
          :modalPageTitle="props.modalPageTitle"
        />
      </div>
      <!--modal btn -->

      <div v-if="props.phone" class="flex justify-center">
        <ModalPhone
          :phone="props.phone"
          :lawyer="props.lawyer"
          :phoneto="phoneto"
          :avatarPath="props.avatarPath"
        />
      </div>

      <div v-else class="grid grid-cols-1 content-center px-1 md:px-0">
        <ModalPhone
          :phone="phone"
          :lawyer="props.lawyer"
          :phoneto="phoneto"
          :avatarPath="props.avatarPath"
        />
      </div>

      <div class="grid grid-cols-1 content-center px-1 md:px-0">
        <WaBtn :phone="phone" />
      </div>

      <div
        class="hidden lg:flex text-xs xl:text-sm items-center justify-start text-center"
      >
        <span v-if="props.address" v-html="props.address"> </span>
        <span v-else> Качество юридических услуг гарантировано </span>
      </div>
    </div>
  </header>
</template>
