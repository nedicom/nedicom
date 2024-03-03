<script setup>
import InputLabel from '@/Components/InputLabel.vue';

import { Inertia } from "@inertiajs/inertia";
import { Cropper } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';

defineProps({
  usluga: String,
  id: Number,
});

</script>

<template>
    <div class="p-5">

        <h2 class="text-lg font-medium text-gray-900">Фото на баннер</h2>
        
        <div class="w-full grid md:grid-cols-2 grid-cols-1 gap-4">
            
          <div id="crop" class="">        
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
                  aspectRatio: 2/3,
              }"
                  image-restriction="stencil" 
              />
          </div>

          <div class="">
            На ПК
            <div class="">
              <InputLabel value="Текущее изображение" />
              <div class="w-full"> 
                <img class=""
                :src='"/"+usluga.mob_file_path'
                />              
              </div>
            </div>
          </div>

        </div>

</div>
</template>
  
  
<script>
  export default {
    name: "imgmobileupld",
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
        if (canvas) {
            const id = this.usluga.id;
            const form = new FormData();
            form.append('id', id);
            form.append('pagetype', 'mobileusluga');   
            canvas.toBlob(blob => {
            form.append('file', blob, 'uslimg');                        
            Inertia.post("/uslimagepost", form);
          });
        }
      },
      uploadImage(event) {              
        const { files } = event.target;
        if (files && files[0]) {
          if (this.image.src) {
            URL.revokeObjectURL(this.image.src);
          }
          const blob = URL.createObjectURL(files[0]);
          this.image = {
            src: blob,
            type: files[0].type,          
          };
        }
      },
    },
    destroyed() {
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