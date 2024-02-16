<script setup>
import { ref, reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { usePage } from "@inertiajs/inertia-vue3";

import Rating from '@/Components/Rating.vue';
import VueDatePicker from '@vuepic/vue-datepicker';

const rate = ref('5');

const admin = usePage().props.value.auth.user.isadmin;

let set = defineProps({
    mainuslugaid: Number,
    uslugaid: Number,
    errors: Object,
});
let form = reactive({
    created_at: '',
    description: '',
    fio: '',
    rating: rate,
    mainusl_id: set.mainuslugaid,
    usl_id: set.uslugaid,
});

function sendreview() {
    Inertia.post("/send/review", form, { preserveScroll: true });
}
</script>

<template>
    <span v-if="admin==1">
        <p class="font-semibold mt-5">Вы можете опубликовать отзыв клиента (доступно админу)</p>
        <p class="text-sm mb-3 text-red-600">Перед этим не забудьте сохранить изменения в услуге</p>
        <form @submit.prevent="sendreview" class="grid grid-cols-4 gap-4">

            <div class="mb-5">
                <label for="VueDatePicker" class="block text-sm font-medium leading-6 text-gray-900">дата
                    отзыва</label>
                <VueDatePicker name="VueDatePicker" v-model="form.created_at" :enable-time-picker="false" auto-apply
                    locale="ru" cancelText="отмена" selectText="выбрать"></VueDatePicker>
                <div class="text-xs text-red-600 animate-pulse" v-if="errors.created_at">{{ errors.created_at }}</div>
            </div>

            <div class="col-span-2 mb-5">
                <label for="otzivfio" class="block text-sm font-medium leading-6 text-gray-900">Кто оставляет (фио)</label>
                <textarea v-model="form.fio" spellcheck="true" name="otzivfio" maxlength="155" class="
                form-control
                block
                w-full
                px-3
                py-1.5
                text-base
                font-normal
                text-gray-700
                bg-white bg-clip-padding
                border border-solid border-gray-300
                rounded
                transition
                ease-in-out
                m-0
                
                focus:text-gray-700
                focus:bg-white
                focus:border-blue-600
                focus:outline-none
                " rows="1"></textarea>
                <div class="text-xs text-red-600 animate-pulse" v-if="errors.fio">{{ errors.fio }}</div>
            </div>


            <Rating v-model.capitalize="rate" />

            <div class="col-span-3 mb-5">
                <label for="otzivbody" class="block text-sm font-medium leading-6 text-gray-900">сам отзыв</label>
                <textarea v-model="form.description" spellcheck="true" name="otzivbody" maxlength="155" class="
            form-control
            block
            w-full
            px-3
            py-1.5
            text-base
            font-normal
            text-gray-700
            bg-white bg-clip-padding
            border border-solid border-gray-300
            rounded
            transition
            ease-in-out
            m-0
            
            focus:text-gray-700
            focus:bg-white
            focus:border-blue-600
            focus:outline-none
        " rows="2"></textarea>
                <div class="text-xs text-red-600 animate-pulse" v-if="errors.description">{{ errors.description }}</div>
            </div>


            <div class="flex items-center justify-center">
                <button type="submit" class="
                    my-5
                    inline-flex
                    items-center
                    px-5
                    py-2.5
                    text-sm
                    font-medium
                    text-center text-white
                    bg-blue-700
                    rounded-lg
                    focus:ring-4 focus:ring-blue-200
                    dark:focus:ring-blue-900
                    hover:bg-blue-800
                  ">
                    Создать отзыв
                </button>
            </div>
        </form>
    </span>
</template>