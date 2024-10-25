<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdateImageForm from "./Partials/UpdateImageForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import { Head } from "@inertiajs/inertia-vue3";

defineProps({
  mustVerifyEmail: Boolean,
  status: String,
  imgurl: String,
  islawyer: String,
  uslugi: Array,
  auth: Object,
});
</script>

<template>
  <Head title="Профиль" />

  <MainHeader :auth="auth" />

  <Body>
    <div class="py-12">{{ auth }}
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
          <UpdateProfileInformationForm
            :must-verify-email="mustVerifyEmail"
            :status="status"
            :islawyer="islawyer"
            :uslugi="uslugi"
            :auth="auth"   
            class="max-w-xl"
          />
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
          <UpdateImageForm 
            :imgurl="imgurl"
            class="w-full"
            />
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
          <UpdatePasswordForm class="max-w-xl" />
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
          <DeleteUserForm class="max-w-xl" />
        </div>
      </div>
    </div>
  </Body>
  <MainFooter />
</template>
