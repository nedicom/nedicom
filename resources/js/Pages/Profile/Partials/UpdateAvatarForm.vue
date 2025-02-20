<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import { Inertia } from "@inertiajs/inertia";
import { Cropper, CircleStencil } from 'vue-advanced-cropper';
import { VueFinalModal } from "vue-final-modal";
import 'vue-advanced-cropper/dist/style.css';

const emit = defineEmits({
  close: "close",
});

defineProps({
  avatarurl: String,
});

</script>

<template>
  <VueFinalModal class="flex justify-center items-center"
    content-class="flex flex-col w-5/6 md:w-1/2 mx-4 p-4 bg-white dark:bg-gray-900 border dark:border-gray-700 rounded-lg space-y-2">
    <h2 class="flex justify-center text-lg font-medium text-gray-900 py-5">Ваш
      идеальный аватар</h2>
    <div id="crop" class="grid grid-cols-1 md:grid-cols-2 w-full gap-1">
      <div class="grid justify-items-center">
        <InputLabel class="text-center mb-2" value="Ваш новый аватар" />
        <div class="w-40 h-40">
          <cropper ref="cropper" class="cropper" :src="image.src" :resizeImage="{ wheel: false }"
            :stencil-component="$options.components.CircleStencil" :stencil-props="{
              handlers: { eastSouth: true, },
              movable: true,
              resizable: true,
              aspectRatio: 1 / 1,
            }" image-restriction="stencil" />
        </div>
        <div class="my-5">
          <button
            class="button mr-5  inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
            @click="$refs.file.click()">
            <input type="file" ref="file" accept="image/jpeg, image/png" @change="uploadImage($event)" />
            Загрузить
          </button>
          <button
            class="button inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
            @click="cropImage">Сохранить</button>
        </div>
      </div>

      <div class="grid justify-items-center">
        <InputLabel class="text-center mb-2" value="Ваш текущий аватар" />
        <div class="w-40 h-40">
          <img class="rounded-full" :src="'/' + avatarurl" alt="Аватар юриста" />
        </div>
        <div class="my-5 text-center text-sm text-gray-700">
          Выбирайте аватар, чтобы нравился лично Вам
        </div>
      </div>
    </div>
  </VueFinalModal>
</template>


<script>
export default {
  name: "imgupld",
  props: ['avatarurl'],
  data() {
    return {
      image: {
        src: this.avatarurl,
        type: null,
      },
    };
  },
  components: {
    Cropper, CircleStencil
  },
  methods: {
    cropImage() {
      const { canvas } = this.$refs.cropper.getResult();
      if (canvas) {
        const avaform = new FormData();
        avaform.append('pagetype', 'profileavatar');
        canvas.toBlob(blob => {
          avaform.append('file', blob, 'avatar');
          Inertia.post("/imagepost", avaform);
        });
      }
    },
    uploadImage(event) {
      /// Reference to the DOM input element
      const { files } = event.target;
      // Ensure that you have a file before attempting to read it
      if (files && files[0]) {
        // 1. Revoke the object URL, to allow the garbage collector to destroy the uploaded before file
        if (this.image.src) {
          URL.revokeObjectURL(this.image.src);
        }
        // 2. Create the blob link to the file to optimize performance:
        const blob = URL.createObjectURL(files[0]);
        // 3. Update the image. The type will be derived from the extension and it can lead to an incorrect result:
        this.image = {
          src: blob,
          type: files[0].type,
        };
      }
    },
  },
  destroyed() {
    // Revoke the object URL, to allow the garbage collector to destroy the uploaded before file
    if (this.image.src) {
      URL.revokeObjectURL(this.image.src);
    }
  },
};
</script>

<style lang="scss">
/*
.cropper {
  width: 200px;
  height: 200px;
}
*/
.button input {
  display: none;
}
</style>