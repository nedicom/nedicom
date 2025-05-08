<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/AllLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { onMounted } from 'vue';

let props = defineProps({
    canResetPassword: Boolean,
    status: String,
    redirect: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
    redirect: props.redirect,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};


onMounted(() => {
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
            redirect_uri: 'https://nedicom.ru/yandexoauth'
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
            // Отправляем код на бэкенд
            router.post(route('welcome'), { code: data.code });
        })
        .catch(console.error);
};

// Безопасный обработчик сообщений
window.addEventListener("message", (e) => {
    if (e.origin !== window.location.origin) return;
    if (e.data === "oauthSuccess") {
        router.reload();
    }
});
</script>

<template>
    <GuestLayout>

        <Head title="Вход" />

        <div id="yandex-auth-container"></div>

        <div v-if="props.status" class="mb-4 font-medium text-sm text-green-600">
            {{ props.status }}
        </div>

        <form @submit.prevent="submit">
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

                <Link :href="route('register')"
                    class="underline ml-5 text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Регистрация
                </Link>

                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Войти
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
