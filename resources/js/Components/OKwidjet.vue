<script setup>
import { onMounted } from "vue";

let set = defineProps({
  groupid: Number,
});

onMounted(() => {
  !(function (d, id, did, st) {
    var js = d.createElement("script");
    js.src = "https://connect.ok.ru/connect.js";
    js.onload = js.onreadystatechange = function () {
      if (
        !this.readyState ||
        this.readyState == "loaded" ||
        this.readyState == "complete"
      ) {
        if (!this.executed) {
          this.executed = true;
          setTimeout(function () {
            OK.CONNECT.insertGroupWidget(id, did, st);
          }, 0);
        }
      }
    };
    d.documentElement.appendChild(js);
  })(document, "ok_group_widget", set.groupid, '{"width": 600, "height":285, "action":"friendship"}');
});
</script>
<style scoped>
#__okGroup0 {
  max-width: 100% !important;
  width: 100% !important;
}
</style>


<template>
  <div id="ok_group_widget" class="flex justify-center overflow-hidden"></div>
</template>



