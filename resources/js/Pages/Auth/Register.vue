<script setup>
import GuestLayout from "@/Layouts/AllLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Checkbox from "@/Components/Checkbox.vue";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { onMounted } from "vue";

let props = defineProps({
  redirect: String,
});

const getCookie = (name) => {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(";").shift();
  return null;
};

const form = useForm({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
  lawyer: false,
  terms: false,
  _ym_uid: null,
  _ga: null,
  _nedicoo: null,
  redirect: props.redirect,
});

onMounted(() => {
  form._ym_uid = getCookie("_ym_uid");
  form._ga = getCookie("_ga");
  form._nedicoo = getCookie("_nedicoo");
});
//form.redirect = usePage().props.value.redirect;

const submit = () => {
  form.post(route("register"), {
    onFinish: () => form.reset("password", "password_confirmation"),
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Регистрация" />

    <form @submit.prevent="submit">
      <div>
        <InputLabel for="name" value="Имя" />

        <TextInput
          id="name"
          type="text"
          class="mt-1 block w-full"
          v-model="form.name"
          required
          autofocus
          autocomplete="name"
        />

        <InputError class="mt-2" :message="form.errors.name" />
      </div>

      <div class="mt-4">
        <InputLabel for="email" value="Email" />

        <TextInput
          id="email"
          type="email"
          class="mt-1 block w-full"
          v-model="form.email"
          required
          autocomplete="username"
        />

        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <div class="mt-4">
        <InputLabel for="password" value="Пароль" />

        <TextInput
          id="password"
          type="password"
          class="mt-1 block w-full"
          v-model="form.password"
          required
          autocomplete="new-password"
        />

        <InputError class="mt-2" :message="form.errors.password" />
      </div>

      <div class="mt-4">
        <InputLabel for="password_confirmation" value="Повторить пароль" />

        <TextInput
          id="password_confirmation"
          type="password"
          class="mt-1 block w-full"
          v-model="form.password_confirmation"
          required
          autocomplete="new-password"
        />

        <InputError class="mt-2" :message="form.errors.password_confirmation" />

        <div class="flex items-start my-5">
          <div class="flex items-center h-5">
            <input
              id="consent"
              type="checkbox"
              v-model="form.consent"
              class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800"
              required
            />
          </div>
          <div class="ml-2">
            <label
              for="consent"
              class="text-sm font-medium bg-white rounded-lg px-1 text-gray-900"
            >
              Даю
              <Link
                href="/personal"
                target="_blank"
                class="underline text-blue-600 hover:text-blue-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                согласие на обработку персональных данных
              </Link>
            </label>
          </div>
        </div>
      </div>

      <div class="flex items-center justify-end mt-4">
        <Link
          :href="route('login')"
          class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          Уже зарегистрированы?
        </Link>

        <PrimaryButton
          class="ml-4"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Регистрация
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>
