<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import { Inertia } from "@inertiajs/inertia";
import { Cropper, ResizeEvent, CircleStencil } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';

defineProps({
  avatarurl: String,
});

</script>

<template>
  <h2 class="text-lg font-medium text-gray-900 py-5">Ваш идеальный аватар</h2>
  
  <div class="inline-grid w-full grid-cols-2 gap-4">    
    <div id="crop" class="w-full text-center">    
        <InputLabel value="Ваш новый аватар" />
        <div class="my-5">
          <button 
          class="button mr-5  inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" 
          @click="$refs.file.click()">
            <input
              type="file"
              ref="file"
              accept="image/jpeg, image/png"
              @change="uploadImage($event)"
            />
            Загрузить
          </button> 
          <button 
          class="button inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" 
          @click="cropImage">Сохранить</button>
        </div>
        <cropper ref="cropper" class="cropper" 
        :src="image.src"
        :resizeImage="{ wheel: false }"
        :stencil-component="$options.components.CircleStencil"
        :stencil-props="{
          handlers: {eastSouth: true,},
          movable: true,
          resizable: true,
          aspectRatio: 1/1,
        }"
          image-restriction="stencil" 
        />
    </div>

    <div class="w-full text-center">
      <InputLabel value="Ваш текущий аватар" />
      <div class="flex h-full w-full justify-center items-center">  
      <img class="rounded-full h-1/2"
        :src="'https://nedicom.ru/' + avatarurl"
        alt="Аватар юриста" 
      />
      </div>
    </div>
    
  </div>
</template>
  
  
<script>
  export default {
    name: "imgupld",
    data() {
      return {
        pixels: null,
        image: {
          src: null,
          type: null,        
        },
      };
    },
    components: {
      Cropper, CircleStencil
    },
    methods: {
      cropImage() {
        var avatarget = "/imagepost";
        const {canvas} = this.$refs.cropper.getResult();
        //pixels = $refs.cropper.getResult();
        if (canvas) {
          const avaform = new FormData();
          avaform.append('pagetype', 'profileavatar'); 
          canvas.toBlob(blob => {
            avaform.append('file', blob, 'avatar');
            Inertia.post(avatarget, avaform);
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
.cropper {
  height: 400px;
  width: 100%;
}

.button 
  input {
    display: none;
  }

</style>