<script setup>
import InputLabel from '@/Components/InputLabel.vue';

import { Inertia } from "@inertiajs/inertia";
import { Cropper } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';

defineProps({
  usluga: Object,
});

</script>

<template>
  <div class="p-5 text-center">

    <h2 class="text-lg font-medium text-gray-900">Фото на баннер (ПК)</h2>

    <div class="md:h-12 w-full mt-3">
      <button
        class="button mr-5  inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
        @click="$refs.file.click()">

        <input type="file" ref="file" accept="image/jpeg, image/png" @change="uploadImage($event)" />
        Загрузить
      </button>
      <button
        class="button inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
        @click="cropImage">Сохранить
      </button>
    </div>

    <div class="w-full grid md:grid-cols-2 grid-cols-1 gap-4 border rounded-lg p-5">

      <div id="crop" class="h-96 md:h-full">
        <InputLabel value="Ваше новое изображение" />

        <cropper ref="cropper" class="cropper" :src="image.src" :resizeImage="{ wheel: false }" :stencil-props="{
          handlers: { eastSouth: true, },
          movable: true,
          resizable: true,
          aspectRatio: 5 / 3,
        }" image-restriction="stencil" />
      </div>


      <div class="h-96 md:h-full">
        <InputLabel value="Текущее изображение (ПК)" />
        <div class="bg-contain bg-center bg-no-repeat h-full"
          :style="{ backgroundImage: `url(https://nedicom.ru/${usluga.file_path})` }"></div>
      </div>

    </div>

  </div>
</template>
  
  
<script>
export default {
  name: "imgupld",
  data() {
    return {
      id: null,
      pixels: null,
      image: {
        src: null,
        type: null,
      },
    };
  },
  methods: {
    cropImage() {
      const { canvas } = this.$refs.cropper.getResult();
      //pixels = $refs.cropper.getResult();
      if (canvas) {
        const id = this.usluga.id;
        const form = new FormData();
        form.append('id', id);
        form.append('pagetype', 'usluga');
        canvas.toBlob(blob => {
          form.append('file', blob, 'uslimg');
          Inertia.post("/uslimagepost", form);
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
  components: {
    Cropper,
  },
};
</script>

<style lang="scss">
@media only screen and (max-width: 500px) {
  .cropper {
    height: 300px;
    width: 100%;
  }
}

.button input {
  display: none;
}
</style>