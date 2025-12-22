<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/AllLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { onMounted, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

let props = defineProps({
    canResetPassword: Boolean,
    status: String,
    redirect: String,
});

const getCookie = (name) => {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
  return null;
};

const openForm = ref(false);
const isChecked = ref(false);
const showHint = ref(false);

const handleClick = (event) => {
    console.log('Клик по документу:', event.target);
    if (!isChecked.value) {
        showHint.value = true;
        setTimeout(() => showHint.value = false, 3000);
    }
};

const form = useForm({
    email: '',
    password: '',
    remember: false,
    redirect: props.redirect,
    _ym_uid: null,
    _ga: null,
    _nedicoo: null,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

onMounted(() => {
    form._ym_uid = getCookie('_ym_uid');
    form._ga = getCookie('_ga');
    form._nedicoo = getCookie('_nedicoo');
    const script = document.createElement('script');
    script.src = 'https://yastatic.net/s3/passport-sdk/autofill/v1/sdk-suggest-with-polyfills-latest.js';
    script.onload = initYandexAuth;
    document.head.appendChild(script);
});

const initYandexAuth = () => {
    window.YaAuthSuggest.init(
        {
            client_id: 'aee386867bdb4be6a5c47d9bf43d5070',
            response_type: 'code',
            redirect_uri: 'https://nedicom.ru/suggest/token'
        },
        'https://nedicom.ru',
        {
            view: 'button',
            parentId: 'yandex-auth-container',
            buttonView: 'main',
            buttonTheme: 'light',
            buttonSize: 'm'
        }
    )
        .then(({ handler }) => handler())
        .then(data => {
            // data - объект с токеном
            console.log('Токен Яндекса:', data);
            Inertia.post('/yandexoauth', { code: data.code }, {
                onSuccess: (page) => {
                    // Если сервер возвращает редирект или успех, можно сделать так:
                    //window.location.href = props.redirect;
                },
                onError: (errors) => {
                    alert('Ошибка авторизации');
                }
            });
        })
        .catch(console.error);
};
</script>

<template>
    <GuestLayout>
       <Head title="Вход" />
        <div v-if="$page.props.error"
            class="mx-auto max-w-md mt-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r text-red-700 flex items-start">
            <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd" />
            </svg>
            <div>
                <p class="font-medium">Ошибка</p>
                <p>{{ $page.props.error }}</p>
            </div>
        </div>
        <div class="flex items-center my-5">
            <div class="w-full">
                <div class="rounded-xl w-full overflow-hidden" @click="handleClick">
                    <div id="yandex-auth-container" class="w-full h-full"
                        :class="{ 'pointer-events-none': !isChecked }"></div>
                </div>
            </div>
        </div>

        <div v-if="props.status" class="mb-4 font-medium text-sm text-green-600">
            {{ props.status }}
        </div>

        <div class="flex items-start my-5">
            <div class="flex items-center h-5">
                <input id="remember" type="checkbox" v-model="isChecked" :class="{
                    'ring-2 ring-red-500 animate-pulse': showHint,
                    'ring-2 ring-green-500': isChecked
                }" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800"
                    required />
            </div>
            <label for="remember" class="ml-2 text-sm font-medium bg-white rounded-lg px-1 text-gray-900">Даю
                согласие на обработку
                <Link href="/policy"
                    class="underline text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                персональных данных
                </Link>
            </label>
        </div>
        <div class="flex justify-between gap-5">
            <div @click="openForm = !openForm"
                class="hover:underline cursor-pointer text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Войти через форму
            </div>

            <Link :href="route('register')"
                class="hover:underline cursor-pointer text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Регистрация
            </Link>
        </div>


        <form @submit.prevent="submit" v-if="openForm">

            <div>
                <InputLabel for="email" value="Ваш email" />

                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus
                    autocomplete="username" />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Пароль" />

                <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                    autocomplete="current-password" />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ml-2 text-sm text-gray-600">Запомнить меня</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link v-if="props.canResetPassword" :href="route('password.request')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Забыли пароль?
                </Link>

                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Войти
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
