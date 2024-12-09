<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Checkbox from '@/Components/Checkbox.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';

import { Link, useForm, usePage } from '@inertiajs/inertia-vue3';

const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
    islawyer: Number,
    uslugi: Array,
    userspec: "String",
    auth: Object,
});

const user = usePage().props.value.user;

let form = useForm({
    name: user.name,
    email: user.email,
    about: user.about,
    lawyer: user.lawyer,
});
</script>

<template>

    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Ваша информация</h2>

            <p class="mt-1 text-sm text-gray-600">
                Имя и email можно поменять
            </p>
        </header>

        <form @submit.prevent="form.patch(route('admin.users.update', {id: user.id}))" class="mt-6 space-y-6">
            <div>
                <InputLabel for="name" value="Имя" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="flex f flex-row"> 
                <Checkbox
                    :islawyer="islawyer"
                    class="mr-2"                    
                    id = "lawyer" 
                    name = "lawyer"             
                    v-model="form.lawyer"
                />
                <InputLabel for="lawyer" value="Я - юрист" />
            </div>
            <div v-if="islawyer == '1'">
                <div class="my-5">
                    <Link
                            :href="route('lawyer', user.id)"
                            class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                            >Моя публичная страница
                    </Link>
                </div>
            </div>



            <div>
                <InputLabel for="about" value="О себе" />

                <TextArea
                    id="about"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.about"
                    autocomplete="about"
                    rows="4"
                />  

                <InputError class="mt-2" :message="form.errors.about" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="email"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="props.mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800">
                    Ваш email не подтвержден. С этим надо что-то делать
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Нажмите здесь, чтобы отправить повторную ссылку на подтверждение email.
                    </Link>
                </p>

                <div
                    v-show="props.status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600"
                >
                    Сыылка для подтверждения была направлена на Ваш email.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Сохранить</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Сохранено</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
