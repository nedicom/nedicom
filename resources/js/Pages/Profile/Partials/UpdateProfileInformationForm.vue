<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Checkbox from "@/Components/Checkbox.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import Tooltip from "@/Components/Tooltip.vue";
import { watch, ref } from 'vue';

import { Link, useForm } from "@inertiajs/inertia-vue3";

const emit = defineEmits(['checkLawyer']);

const props = defineProps({
  mustVerifyEmail: Boolean,
  status: String,
  uslugi: Array,
  auth: Object,
});

const user = props.auth;

let form = useForm({
  name: user.name,
  email: user.email,
  city: user.city,
  phone: user.phone,
  about: user.about,
  lawyer: user.lawyer,
  address: user.address,
});


watch(() => form.lawyer, (lawyer) => {
  emit('checkLawyer', lawyer)
}
)
</script>

<template>
  <section>
    <form @submit.prevent="form.patch(route('profile.update'))" class="mt-6 space-y-6">

      <InputError class="mt-2 text-center" :message="form.errors.city" />
      
      <div class="grid grid-cols-1 gap-4">

        <div class="flex justify-center">
          <div class="w-1/2">
            <InputLabel for="phone" value="Телефон" />

            <TextInput id="phone" type="text" class="mt-1 block w-full" v-model="form.phone" autocomplete="tel" />

            <InputError class="mt-2" :message="form.errors.phone" />
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <InputLabel for="name" value="Имя" />

            <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required />

            <InputError class="mt-2" :message="form.errors.name" />
          </div>

          <div>
            <InputLabel for="email" value="Email" />

            <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
              autocomplete="email" />

            <InputError class="mt-2" :message="form.errors.email" />
          </div>
        </div>

      </div>


      <div class="flex items-center">
        <div class="flex w-full items-center">
          <Checkbox :islawyer="auth.lawyer" class="mr-2" id="lawyer" name="lawyer" v-model="form.lawyer" />

          <InputLabel class="w-full" for="lawyer" value="Хочу стать юристом проекта" />
        </div>
        <Tooltip text="Юристы сайта получают публичную страницу, возможность публиковать объявления 
                о своих услугах и делиться практикой для привлечения клиентов." />
      </div>
      <div v-if="auth.lawyer == '1'">
        <div class="my-5">
          <Link :href="route('lawyer', user.id)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
          Моя публичная страница
          </Link>
        </div>
      </div>

      <div v-if="form.lawyer">
        <InputLabel for="about" value="О себе" />

        <TextArea id="about" type="text" class="mt-1 block w-full" v-model="form.about" autocomplete="about" rows="4" />

        <InputError class="mt-2" :message="form.errors.about" />
      </div>

      <div v-if="form.lawyer">
        <InputLabel for="address" value="Адрес офиса" />

        <TextArea id="address" type="text" class="mt-1 block w-full" v-model="form.address" autocomplete="address" rows="2" />

        <InputError class="mt-2" :message="form.errors.address" />
      </div>



      <div v-if="props.mustVerifyEmail && user.email_verified_at === null">
        <p class="text-sm mt-2 text-gray-800">
          Ваш email не подтвержден. С этим надо что-то делать
          <Link :href="route('verification.send')" method="post" as="button"
            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
          Нажмите здесь, чтобы отправить повторную ссылку на подтверждение
          email.
          </Link>
        </p>

        <div v-show="props.status === 'verification-link-sent'" class="mt-2 font-medium text-sm text-green-600">
          Сыылка для подтверждения была направлена на Ваш email.
        </div>
      </div>

      <div class="flex items-center gap-4">
        <PrimaryButton :disabled="form.processing">Сохранить</PrimaryButton>

        <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
          <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">
            Сохранено
          </p>
        </Transition>
      </div>
    </form>
  </section>
</template>
