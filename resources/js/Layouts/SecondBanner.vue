<script setup>
import PhoneForm from "@/Components/PhoneForm.vue";

const props = defineProps({
  statusonimage: String,
  nameonimage: String,
  secondbannerimgmobile: String,
  secondbannerpc: String,
  metaimage: String,
  phnform: Boolean
});

let spc = ' url(' + props.secondbannerpc + '), linear-gradient(black, white)';
let mspc = ' url(' + props.secondbannerimgmobile + '), linear-gradient(black, white)';
</script>

<style scoped>
#secondbanner {
  background-image: v-bind(spc);
}

@media only screen and (max-width: 600px) {
  #secondbanner {
    background-image: v-bind(mspc);
  }
}
</style>

<template>
  <!-- banner -->
  <meta itemprop="image" :content="props.metaimage" :alt="props.statusonimage" />
  <div id="secondbanner" class="relative overflow-hidden block md:bg-contain bg-cover w-full md:h-screen h-[40em]">
    <div class="grid grid-cols-1 md:justify-items-end w-full h-full">
      <div style="box-shadow: 0px 0px 40px 40px #fff"
        class="md:w-1/2 w-full h-full grid grid-cols-1 text-center md:float-right md:text-left md:content-center content-end md:bg-white md:shadow-lg">
        
        <div class="text-center mb-5">
          <h1 itemprop="name" class="goo m-auto text-2xl md:text-4xl font-semibold normal-case ">
            {{ statusonimage
            }}</h1>
        </div>

        <section v-if="!props.phnform" class="grid grid-cols-2 mb-5 gap-5 px-10">
          <div class="pb-4 pt-2 md:py-8 px-4 bg-gray-100 rounded-lg">
            <h2 class="mb-2 md:text-xl tracking-tight font-bold leading-tight text-gray-900">
              Цены</h2>
            <p class="hidden md:block mb-4 text-gray-900 text-sm">Справедливые цены, качественная работа</p>
            <a href="#prices"
              class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5">смотреть</a>
          </div>

          <div class="pb-4 pt-2 md:py-8 px-4 bg-gray-100 rounded-lg">
            <h2 class="mb-2 md:text-xl tracking-tight font-bold leading-tight text-gray-900">
              Отзывы</h2>
            <p class=" hidden md:block mb-4 text-gray-900 text-sm">Ждем Ваш отзыв в нашей
              копилке</p>
            <a href="#reviews"
              class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5">смотреть</a>
          </div>

          <div class="pb-4 pt-2 md:py-8 px-4 bg-gray-100 rounded-lg">
            <h2 class="mb-2 md:text-xl tracking-tight font-bold leading-tight text-gray-900">
              Достижения</h2>
            <p class="hidden md:block mb-4 text-gray-900 text-sm">Практика юриста в данной категории</p>
            <a href="#prctglr"
              class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5">смотреть</a>
          </div>

          <div class="pb-4 pt-2 md:py-8 px-4 bg-gray-100 rounded-lg">
            <h2 class="mb-2 md:text-xl tracking-tight font-bold leading-tight text-gray-900">
              Вопросы</h2>
            <p class="hidden md:block mb-4 text-gray-900 text-sm">Может Вы ищите то, на что уже ответили</p>
            <a href="#questions"
              class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5">смотреть</a>
          </div>

        </section>

        <!-- Form on main banner-->
        <div v-if="props.phnform" class="flex justify-center">
          <PhoneForm :modalPageTitle="props.statusonimage" />
        </div>
        <!--Form on main banner-->

      </div>
    </div>


  </div>
  <!-- banner -->

  <!-- Filter: https://css-tricks.com/gooey-effect/ -->
  <svg style="visibility: hidden; position: absolute" width="0" height="0" xmlns="http://www.w3.org/2000/svg"
    version="1.1">
    <defs>
      <filter id="goo">
        <feGaussianBlur in="SourceGraphic" stdDeviation="5" result="blur" />
        <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo" />
        <feComposite in="SourceGraphic" in2="goo" operator="atop" />
      </filter>
    </defs>
  </svg>
</template>

<style lang="scss">
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