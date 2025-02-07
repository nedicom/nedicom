<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdateImageForm from "./Partials/UpdateImageForm.vue";
import UpdateAvatarForm from "./Partials/UpdateAvatarForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { ref,watch } from 'vue';

import { ModalsContainer, useModal } from 'vue-final-modal';

let set = defineProps({
  mustVerifyEmail: Boolean,
  status: String,
  uslugi: Array,
  auth: Object,
});

const count = ref(true);


function increaseCount(n) {
  count.value = n
}

const { open, close, patchOptions } = useModal({
  component: UpdateAvatarForm,
  attrs: {
    avatarurl: set.auth.avatar_path,
    onClose() {
      close()
    },
  },
})



watch(() => set.auth.avatar_path, (avatar_path) =>{
  patchOptions({
  attrs: {
    // Overwrite the modal's props
    avatarurl: set.auth.avatar_path,
  }
})
})
</script>

<template>

  <Head title="Профиль" />

  <MainHeader :auth="set.auth" />

  <Body>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">        

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
          <div @click="() => open()" class="flex h-full max-w-xl justify-center items-center cursor-pointer">
          <img class="rounded-full w-24 h-24 opacity-100 hover:opacity-75" :src="'/' + set.auth.avatar_path" alt="Аватар юриста" />
        </div>
          <UpdateProfileInformationForm :must-verify-email="set.mustVerifyEmail" :status="set.status"
            :islawyer="set.auth.lawyer" :uslugi="set.uslugi" :auth="set.auth" @check-lawyer="increaseCount"
            class="max-w-xl" />
        </div>
        
        <div v-if="count" class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
          <UpdateImageForm :imgurl="set.auth.file_path" />
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
          <UpdatePasswordForm class="max-w-xl" />
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
          <DeleteUserForm class="max-w-xl" />
        </div>
      </div>
    </div>
    <ModalsContainer />
  </Body>
  <MainFooter />
</template>
