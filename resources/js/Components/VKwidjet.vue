<script setup>
import { onMounted } from "vue";

let set = defineProps({
  groupid: Number,
});

onMounted(() => {
  VK.Widgets.Group(
    "vk_groups",
    {
      mode: 0,
      width: "auto",
      heigth: "auto",
      no_cover: 1,
      wide: 1,
      color1: "FFFFFF",
      color2: "000000",
      color3: "5181B8",
    },
    set.groupid
  );
});
</script>


<template>
  <div id="vk_groups"></div>
</template>



