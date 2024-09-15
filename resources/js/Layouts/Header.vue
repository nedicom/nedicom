<script setup>
import Modal from "@/Components/Modal.vue";

let phone = "89788838978";

const props = defineProps({
  address: String,
  phone: String,
  modalPageTitle: String
});

if (props.phone) {
  phone = props.phone;
}

let phoneto = "tel:" + phone;

let ModalBtnText = "записаться на консультацию";
</script>


<template>
  <!-- Page Heading -->
  <noscript>
    <div>
      <img src="https://mc.yandex.ru/watch/24900584" style="position: absolute; left: -9999px" />
    </div>
  </noscript>
  <header class="bg-white shadow sticky inset-x-0 -top-1 h-22 md:h-16 z-40">
    <div class="max-w-7xl mx-auto pt-2 pb-1 md:py-3 px-4 sm:px-6 lg:px-4">
      <h2 class="font-semibold text-gray-800 leading-tight text-center">
        <div class="grid md:grid-cols-3 md:gap-4">
          <!--modal btn -->
          <div class="flex items-center justify-center md:justify-start px-2">
            <div class="">
              <Modal :ModalBtnText="ModalBtnText" :modalPageTitle="modalPageTitle"/>
            </div>
          </div>
          <!--modal btn -->

          <div v-if="props.phone" class="grid grid-cols-1 content-center">
            <a :href="phoneto" class="p-0 text-2xl">{{ phone }} </a>
          </div>

          <div v-else class="grid grid-cols-1 content-center">
            <a :href="phoneto" class="p-0 text-2xl">{{ phone }} </a>
          </div>

          <div class="hidden md:flex text-sm items-center justify-start text-center px-2">
            <span v-if="props.address">
              {{ props.address }}
            </span>
            <span v-else>
              Качество юридических услуг гарантировано
            </span>
          </div>

        </div>
      </h2>
    </div>
  </header>
</template>
