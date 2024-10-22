<script setup>
import { ref, onMounted } from "vue";

import PhoneForm from "@/Components/PhoneForm.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

const props = defineProps({
  statusonimage: String,
  nameonimage: String,
  secondbannerimgmobile: String,
  secondbannerpc: String,
  metaimage: String,
  phnform: Boolean
});

//preloader img
const loadImage = () => {
  const img = new Image();
  if (screen.width <= 760) {
    img.src = props.secondbannerimgmobile;
  } else {
    img.src = props.secondbannerpc;
  }
  img.onload = () => {
    let bgElement = document.getElementById('secondbanner');
    bgElement.style.backgroundImage = `url(${img.src})`;
  };
};
onMounted(() => {
  loadImage();
});
//preloader img

</script>

<style scoped>
#secondbanner {
  background-image: url('https://www.nedicom.ru/pcpreloader.webp'), linear-gradient(black, white);
}

@media only screen and (max-width: 600px) {
  #secondbanner {
    background-image: url('https://www.nedicom.ru/mobpreloader.webp');
  }
}
</style>

<template>
  <!-- banner -->
  <meta itemprop="image" :content="metaimage" :alt="statusonimage" />
  <div id="secondbanner" class="relative overflow-hidden block md:bg-contain bg-cover w-full md:h-screen h-[40em]">
    <div class="grid grid-cols-1 md:justify-items-end w-full h-full">
      <div style="box-shadow: 0px 0px 40px 40px #fff"
        class="md:w-1/2 w-full h-full md:float-right md:content-center content-end md:bg-white md:shadow-lg">
        <div class="flex items-center justify-center">
          <div class="text-black text-center">
            <div class="m-5">

              <h1 itemprop="name" class="goo text-2xl md:text-4xl font-semibold normal-case">
                {{ statusonimage
                }}</h1>

            </div>

            <!-- Form on main banner-->
            <div v-if="props.phnform" class="flex justify-center">
              <PhoneForm :modalPageTitle="props.statusonimage" />
            </div>
            <!--Form on main banner-->

          </div>
        </div>
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