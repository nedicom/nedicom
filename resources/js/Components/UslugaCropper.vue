<script setup>
import InputLabel from '@/Components/InputLabel.vue';

import { Inertia } from "@inertiajs/inertia";
import { Cropper } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';

defineProps({
  usluga: String,
});

</script>

<template>
    <div class="p-5">

        <h2 class="text-lg font-medium text-gray-900">Фото на баннер</h2>
        
        <div class="w-full inline-grid grid-cols-1 md:grid-cols-2 gap-4">
            
          <div id="crop" class="w-full">        
              <InputLabel value="Ваше новое изображение" />
              <div class="h-12 w-full mt-3">
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
                      @click="cropImage">Сохранить
                  </button>
              </div>
              <cropper ref="cropper" class="cropper" 
              
              :src="image.src"
              :resizeImage="{ wheel: false }"
              :stencil-props="{
                  handlers: {eastSouth: true,},
                  movable: true,
                  resizable: true,
                  aspectRatio: 3/4,
              }"
                  image-restriction="stencil" 
              />
          </div>

          <div class="w-full">
              <InputLabel value="Текущее изображение" />
              <div class="h-12 w-full mt-3"></div>
              <div class="w-full cropper"> 
                <img class="h-full"
                :src='"/"+usluga.file_path'
                alt="Avatar" 
                />
              
              </div>
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
        const {canvas} = this.$refs.cropper.getResult();
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
.cropper {
  height: 800px;
  width: 100%;
}

.button 
  input {
    display: none;
  }

</style>