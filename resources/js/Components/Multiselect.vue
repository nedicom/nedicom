<script setup>
import { reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";
import CrossButton from '@/Components/CrossButton.vue';
import Tooltip from '@/Components/Tooltip.vue';
import { Link } from '@inertiajs/vue3'

defineProps( {
    specdata: Array,
    userspec: "String ",
});
</script>

<template>
    
    <div class="my-5 w-full inline-grid grid-cols-2 gap-4 items-center">
    <input
        @input.prevent="inputusl"
        autocomplete="off"
        id="listid"
        list="uslugi" 
        placeholder="Выбрать услугу"       
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
        />
          <datalist id="uslugi" >
              <option  v-for="key in specdata" :key="key" :data-value="key.id" :value="key?.usl_name"></option>
          </datalist>
      <Link 
          :href="route('uslugi.add')"        
          class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
          >придумать новую услугу
      </Link>

        <Tooltip text="Эти услуги появятся в Вашем профиле. Выбрать можно только основные услуги. 
        Услуги, которые создаете Вы - производные от основных."/>
    </div>  

        <div class="my-3">
          <div v-for="key in JSON.parse(userspec)"  
          :key="key.value" 
          v-show="key.specialization" 
          :id="key.specialization"  
          @click.prevent="remove" 
          :class="invisibility"
          class="mt-3 mr-2 py-2 px-4 inline-flex text-sm font-medium  flex-wrap  justify-between items-center  
            cursor-pointer bg-blue-600 border border-transparent rounded-md 
             text-white  hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2  duration-150">{{key?.value}}
          <CrossButton @click.prevent="removeBtn(key.specialization)" />
          </div>
        </div>
       
</template> 

<script>
export default {
  data() {    
    return {
      invisibility: 'mt-3 mr-2 px-4 py-2',
    };
  },
  methods: {
    inputusl(e) {     
      let element_input = document.getElementById('listid');
      let element_datalist = document.getElementById('uslugi');
      let opSelected = element_datalist.querySelector(`[value="${element_input.value}"]`);      
      let id = opSelected.getAttribute('data-value');
      let val = opSelected.getAttribute('value');
     
      let form = reactive({
          arrayspec: id,
          arrayvalue: val,
      });
      Inertia.patch("/profilespec", form);      
      e.target.value = null;
    },
    remove(e){  
        //alert(e.target.id);
        let btn = document.getElementById(e.target.id);
        btn.classList.add('hidden');
        let form = reactive({
            id: e.target.id,
        });
        Inertia.patch("/deletespec", form);
    },
      removeBtn(val){  
        document.getElementById(val).click();
    },
  },
};
</script>
