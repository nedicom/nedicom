<script setup>
const props = defineProps({
  statusonimage: String,
  nameonimage: String,
  mainbannerpc: String,
  mainbannerimgmobile: String,
});
</script>

<template>
  <!-- main banner -->
  <div
    id="mainbanner"
    class="relative overflow-hidden bg-cover bg-transparent bg-no-repeat bg-md-top w-full h-screen"
  >
    <div class="grid grid-cols-1 justify-items-center py-5 w-full h-full">
      <div class="flex items-end">
        <div class="text-black text-center">
          <div class="m-5">
            <span
              class="goo text-xl font-semibold bg-white rounded-lg py-1 px-4"
              >„быть юристом - помогать людям“</span
            >
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- main banner -->

  <!-- Filter: https://css-tricks.com/gooey-effect/ -->
  <svg
    style="visibility: hidden; position: absolute"
    width="0"
    height="0"
    xmlns="http://www.w3.org/2000/svg"
    version="1.1"
  >
    <defs>
      <filter id="goo">
        <feGaussianBlur in="SourceGraphic" stdDeviation="5" result="blur" />
        <feColorMatrix
          in="blur"
          mode="matrix"
          values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9"
          result="goo"
        />
        <feComposite in="SourceGraphic" in2="goo" operator="atop" />
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
#mainbanner {
    background-image: v-bind(mainbannerpc);
}

@media only screen and (max-width: 600px) {
    #mainbanner {
        background-image: v-bind(mainbannerimgmobile);
    }
}

:root {
  --color-highlight: #fff;
  --font: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica,
    Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
}

.goo {
  line-height: 1.35;
  display: inline;
  box-decoration-break: clone;
  background: var(--color-highlight);
  padding: 0.2rem 1rem;
  filter: url("#goo");
}

.goo:focus {
  outline: 0;
}
</style>