<script setup>
import { reactive, ref } from "vue";
import { Inertia } from "@inertiajs/inertia";


const props = defineProps({
    statusonimage: String,
    nameonimage: String,
    secondbannerimg: String,    
});

/*
const p = new Promise((resolve, reject) =>{ // Promise (class) - class, resolve - function done, reject - function error
    console.log('setTimeout started')
    setTimeout(() => { // timer
        console.log('setTimeout ended')
        resolve({status: 'ok'}) //return object in {} to then function
        reject({status: 'ok'}) 
    }, 2000)
})
    p
    .then((data) => {
        console.log(data)
    })
    .catch(error => console.error(error))
    .finally(() => console.log('finally')) //function

    */

let form = reactive({
  phone: "",
  //token: 'null',
  token: 'token',
  url: location.href,
});

const loading = ref(true);

/*function startCaptcha(){
        grecaptcha.ready(function() {                                  
            grecaptcha.execute('6Lf0-tAZAAAAAIxKP1YOtKrCfqSm_yl3QF-IzglK', {action: 'submit'}).then(function(token) {            
        form.token = token                   
        });
    });       
}*/

/*
async function postPhone() {
  const response = await fetch(`https://swapi.dev/api/films/${id}/`)
  console.log("ответ получен", response) // *1
  return response.json()
}


function recaptcha () {
    //alert('false');
    loading.value = false; 
    alert('123');
}
*/


function recaptcha() { 
    loading.value = false;
    async function f() {
        let promise = new Promise((resolve, reject) => {
            Inertia.post("/phone/send", form, {preserveScroll: true,});
            setTimeout(() => resolve(true), 1000)
        });
        loading.value = await promise; 
        }
    f();
}
/*
const recaptcha = async () => { 
    loading = false;    
    Inertia.post("/phone/send", form, {preserveScroll: true,});
}*/
    
</script>

<template>
<!-- main banner -->
    <div
    class="
        relative 
        overflow-hidden 
        bg-cover 
        bg-transparent 
        bg-no-repeat
        bg-md-top
        w-full
        h-screen        
        "
        
    :style="{ backgroundImage: `url(${secondbannerimg})`}"
    >
    <img itemprop="image" :src="secondbannerimg" :alt="nameonimage" class="hidden"/>
        <div
            class="grid grid-cols-1 md:justify-items-end md:content-center content-end py-5 w-full h-full"
           >

            <div class="md:w-1/2 md:float-right">
                <div class="flex  items-center justify-center">            
                
                    <div class="text-black text-center">
                        
                        <div class="m-5">
                            <span class="goo text-4xl font-semibold">{{ nameonimage }}  {{ statusonimage }}</span>
                        </div>
                        <div  class="m-5">
                            <span class="goo text-xl font-semibold bg-white rounded-lg py-1 px-4">Запись на консультацию</span>
                        </div>

                        <!-- Form on main banner-->                        
                        <div class="flex justify-center">
                            <form @submit.prevent="recaptcha" class="w-80 space-y-6">
                                <div>                
                                    <input v-model="form.phone" v-on:click="startCaptcha" type="number" name="phone" id="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="+7" required>
                                    <span class="text-red-500 bg-white rounded-lg px-2 py-1 font-semibold text-sm animate-spin-pulse" v-if="$page.props.errors['phone']">{{ $page.props.errors['phone'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="remember" type="checkbox" value="" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" checked required>
                                        </div>
                                        <label for="remember" class="bg-white px-1 rounded-lg ml-2 text-sm font-medium text-white-900 dark:text-gray-300">Согласен с политикой сайта</label>
                                    </div>
                                    <a href="/policy" class="text-sm bg-white px-1 rounded-lg text-blue-700 hover:underline dark:text-blue-500">Подробнее</a>
                                </div>

                                <button type="submit" :disabled="form.processing"
                                    class="md:w-full w-full bg-blue-700 hover:bg-blue-800 text-white focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 inline-flex items-center"
                                > 
                                    <div class="text-center w-full">
                                        <!-- <span>отправить</span> -->  
                                       <span v-if="loading">отправить</span>                                    
                                        <svg v-else aria-hidden="true" role="status" class="inline w-3/4 h-4 mr-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                                        </svg>
                                    </div>
                                </button>  
                                
                               
                                    <div class="text-red-500 bg-white rounded-lg px-2 py-1 font-semibold text-sm animate-spin-pulse" v-if="$page.props.errors['token']">{{ $page.props.errors['token'] }}</div>
                                </form>
                        </div>
                        <!-- Form on main banner-->

                    </div>
                </div>
            </div>            
        </div>         
    </div>
    <!-- main banner -->

    <!-- Filter: https://css-tricks.com/gooey-effect/ -->
    <svg style="visibility: hidden; position: absolute;" width="0" height="0" xmlns="http://www.w3.org/2000/svg" version="1.1">
        <defs>
            <filter id="goo">
                <feGaussianBlur in="SourceGraphic" stdDeviation="5" result="blur" />    
                <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo" />
                <feComposite in="SourceGraphic" in2="goo" operator="atop"/>
            </filter>
        </defs>
    </svg>
    
</template>

<script>
/*
import { useForm } from '@inertiajs/inertia-vue3'

export default {
    name: 'form-loading-spinner-example',
    data(){  
        return{
            success: false,
            error: false,
            loading: true,
            form : {
                phone: "",
                token: null,
                url: '',//location.href,                
            },
        }
    },
    methods:{
            recaptcha(){
                this.loading = false; 

                setTimeout(() => {
                    this.loading = true;
                }, 1500);
                               
                let form = this.form;                    
                grecaptcha.ready(function() {
                    grecaptcha.execute('6Lf0-tAZAAAAAIxKP1YOtKrCfqSm_yl3QF-IzglK', {action: 'submit'}).then(function(token) {                                                                
                        form.token =  token;
                        Inertia.post("/phone/send", form, {
                            preserveScroll: true,
                        })
                    })                     
                })  
            },   
    }
}*/

</script>

<style lang="scss">
    :root {
    --color-highlight: #fff;
    --font: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
}

.goo {
    line-height: 1.35;
    display: inline;
    box-decoration-break: clone;
    background: var(--color-highlight);
    padding: 0.2rem 1rem;
    filter: url('#goo');
}

.goo:focus {
    outline: 0;
}


</style>