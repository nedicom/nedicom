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

<img class="h-auto max-w-full" :src="props.mainbannerpc" alt="Мина и партенры. Юристы">

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