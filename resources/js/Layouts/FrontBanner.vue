<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  statusonimage: String,
  secondbannerimgmobile: String,
  secondbannerpc: String,
  metaimage: String,
  phnform: Boolean
});

let spc = ' url(' + props.secondbannerpc + ') ';
let mspc = ' url(' + props.secondbannerimgmobile + ') ';

const card = ref(false);

function opencard() {
  card.value = !card.value;
}

</script>

<template>
  <!-- banner -->
  <div class="card-container" @click="opencard">
    <div class="card" :class="{ 'is-opened': card }">
      <div class="card-page cart-page-front">
        <div class="card-page cart-page-outside"></div>
        <div class="card-page cart-page-inside">
          <span class="merry-christmas">
            <span class="flex justify-center"><img alt="С Новым Годом"
                src="https://nedicom.ru/storage/images/landing/main/coockie.png" /></span>
            <span class="goo text-xs md:text-xl">
              промокод: "2025"<br>
              *скажите его <br>перед консультацией</span>
          </span>
        </div>
      </div>
      <div class="card-page cart-page-bottom text-center px-3">
        <div class="">
          <span class="goo font-semibold bg-white rounded-lg">
            Приветствую, меня зовут Марк Мина. Я работаю над сервисом качественных юридических услуг</span><br><br>
          <span class="goo font-semibold bg-white rounded-lg py-1 px-4">
            Держите в подарок 2000 рублей,
            которые можно потратить на наши услуги
          </span>
        </div>
      </div>
    </div>
  </div>

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

.card-container * {
  box-sizing: border-box;
}

.card-container {
  background-image: url("https://nedicom.ru/storage/images/landing/main/nmainpc.webp");
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center top;
  height: 740px;
  overflow: hidden;
  position: relative;
  margin: 20px 0;
}

.card {
  perspective: 1500px;
  position: absolute;
  left: calc(50% - 175px);
  top: calc(50% - 250px);
  transition-property: transform;
  transition-duration: 1s;
  transition-timing-function: cubic-bezier(0.68, -0.55, 0.265, 1.55);
  transition-delay: initial;
  transform: rotate(-10deg) translate(0, 0);
  animation: cardIn 1s alternate cubic-bezier(0.87, -0.41, 0.19, 1.44);
}

.card:before {
  content: "";
  display: block;
  position: absolute;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  box-shadow: 0 0 50px #000;
  transition: all 0.5s ease-in-out;
}

.card.is-opened {
  transform: rotate(0deg) translate(175px, 0);
}

.card.is-opened .cart-page-front {
  transform: rotateY(-180deg);
}

@keyframes cardIn {
  0% {
    opacity: 0;
    transform: rotate(-10deg) translate(0, -100%);
  }

  25% {
    opacity: 1;
  }

  100% {
    opacity: 1;
    transform: rotate(-10deg) translate(0, 0);
  }
}

.card,
.card-page {
  width: 350px;
  height: 500px;
}

.card-page {
  transition: transform 1s ease-in-out;
  cursor: pointer;
  position: absolute;
  outline: 1px solid transparent;
}

.cart-page-front {
  transform-origin: 0 50% 0;
  transform-style: preserve-3d;
  transform: rotateY(-20deg);
  z-index: 2;
}

.cart-page-outside,
.cart-page-inside {
  position: absolute;
  -webkit-backface-visibility: hidden;
}

.cart-page-outside {
  border: 10px solid #fbfbfb;
  background-color: #c72320;
  background-image: url("https://nedicom.ru/storage/images/landing/main/xmas.png");
  background-position: center center;
  background-repeat: no-repeat;
  background-size: cover;
  width: 100%;
  height: 100%;
}

@media (max-width: 767px) {
  .cart-page-outside {
    border: 5px solid #fbfbfb;
  }
}

.cart-page-inside,
.cart-page-bottom {
  background-color: #d4d1d0;
  background-image: url("https://nedicom.ru/storage/images/landing/main/pattern.png");
  display: flex;
  flex-flow: column;
  justify-content: center;
  align-items: center;
  text-align: center;
}

.cart-page-inside {
  transform: rotateY(-180deg);
  border-right: none !important;
  background-position: 0px 80px;
}

.cart-page-bottom {
  z-index: 1;
  border-left: none !important;
  background-position: -22px 80px;
  font-size: 18px;
}

.merry-christmas {
  transform: rotate(-10deg);
}

.merry-christmas img {
  width: 200px;
  height: auto;
}

@media (max-width: 1024px) {
  .card-container {
    height: 400px;
  }

  .card {
    left: calc(50% - 115px);
    top: calc(50% - 164.5px);
  }

  .card.is-opened {
    transform: rotate(0deg) translate(115px, 0);
  }

  .card,
  .card-page {
    width: 230px;
    height: 329px;
  }

  .cart-page-inside,
  .cart-page-bottom {
    border: 10px solid #d4d1d0;
  }

  .cart-page-inside {
    background-position: 0px 30px;
  }

  .cart-page-bottom {
    background-position: 85px 30px;
    font-size: 11px;
  }

  .merry-christmas img {
    width: 120px;
  }
}

@media (max-width: 479px) {
  .card-container {
    height: 300px;
  }

  .card {
    left: calc(50% - 75px);
    top: calc(50% - 107px);
  }

  .card.is-opened {
    transform: rotate(0deg) translate(75px, 0);
  }

  .card,
  .card-page {
    width: 150px;
    height: 214px;
  }

  .cart-page-inside {
    background-position: 0px 30px;
  }

  .cart-page-bottom p {
    line-height: 1;
  }

  .cart-page-bottom {
    background-position: 0px 30px;
    font-size: 10px;
  }

  .merry-christmas img {
    width: 100px;
  }
}
</style>