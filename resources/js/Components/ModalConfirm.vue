<script setup>
import { VueFinalModal } from "vue-final-modal";
import PhoneForm from "@/Components/PhoneForm.vue";
import { reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";

let set = defineProps({
    modalPageTitle: String,
});

const emit = defineEmits({
    e: "confirm",
});
/*
let form = reactive({
    one: "",
    two: "",
    three: "",
    four: "",
    five: "",
    six: "",
    seven: "",
    eight: "",
    nine: "",
    ten: "",
    url: set.modalPageTitle,
});

let submit = () => {
    Inertia.post("/phone/send", form), emit('confirm');
};
*/
</script>

<template>
    <VueFinalModal class="flex justify-center items-center"
        content-class="flex flex-col max-w-xl mx-4 p-4 bg-white dark:bg-gray-900 border dark:border-gray-700 rounded-lg space-y-2">
        <div class="relative">
            <button type="button" @click="emit('confirm')"
                class="absolute top-0 right-0 bg-white rounded-md p-2 inline-flex items-end justify-end text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                <span class="sr-only">Close menu</span>
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="px-6 py-6 lg:px-8">
            <h3 class="mb-4 text-xl font-medium text-center text-gray-900 dark:text-white">
                {{ set.modalPageTitle }}
                Ваш номер телефона для связи
            </h3>

            <PhoneForm :modalPageTitle = "set.modalPageTitle"/>
            <!--<form @submit.prevent="submit" class="space-y-6">
                <div class="flex justify-center">
                    <div class="flex mb-2 space-x-0.5 md:space-x-1 rtl:space-x-reverse">
                        <div>
                            <input type="text" value="+7" disabled
                                class="block w-5 h-6 px-0 md:w-7 md:h-10 md:text-2xl font-extrabold text-center text-gray-900 bg-white border-none " />
                        </div>
                        <div>
                            <input type="text" value="(" disabled
                                class="block w-2 h-6 px-0 md:w-2 md:h-10 md:text-2xl font-extrabold text-center text-gray-900 bg-white border-none" />
                        </div>

                        <div>
                            <input type="text" maxlength="1" name="one" id="one" v-model="form.one"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57" data-focus-input-init
                                data-focus-input-next="two"
                                class="block w-6 h-6 px-0 md:w-10 md:h-10 md:text-2xl font-extrabold text-center text-gray-900 bg-white border border-gray-300 rounded-lg "
                                required />
                        </div>
                        <div>
                            <input type="text" maxlength="1" name="two" id="two" v-model="form.two"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57" data-focus-input-init
                                data-focus-input-next="three" data-focus-input-prev="one"
                                class="block w-6 h-6 px-0 md:w-10 md:h-10 md:text-2xl font-extrabold text-center text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required />
                        </div>
                        <div>
                            <input type="text" maxlength="1" name="three" id="three" v-model="form.three"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57" data-focus-input-init
                                data-focus-input-next="four" data-focus-input-prev="two"
                                class="block w-6 h-6 px-0 md:w-10 md:h-10 md:text-2xl font-extrabold text-center text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required />
                        </div>
                        <div>
                            <input type="text" value=")" disabled
                                class="block w-2 h-6 px-0 md:w-2 md:h-10 md:text-2xl font-extrabold text-center text-gray-900 bg-white border-none" />
                        </div>
                        <div>
                            <input type="text" maxlength="1" name="four" id="four" v-model="form.four"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57" data-focus-input-init
                                data-focus-input-next="five" data-focus-input-prev="three"
                                class="block w-6 h-6 px-0 md:w-10 md:h-10 md:text-2xl font-extrabold text-center text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required />
                        </div>
                        <div>
                            <input type="text" maxlength="1" name="five" id="five" v-model="form.five"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57" data-focus-input-init
                                data-focus-input-next="six" data-focus-input-prev="four"
                                class="block w-6 h-6 px-0 md:w-10 md:h-10 md:text-2xl font-extrabold text-center text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required />
                        </div>
                        <div>
                            <input type="text" maxlength="1" name="six" id="six" v-model="form.six"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57" data-focus-input-init
                                data-focus-input-next="seven" data-focus-input-prev="five"
                                class="block w-6 h-6 px-0 md:w-10 md:h-10 md:text-2xl py-1 mx-0  font-extrabold text-center text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required />
                        </div>
                        <div>
                            <input type="text" maxlength="1" name="seven" id="seven" v-model="form.seven"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57" data-focus-input-init
                                data-focus-input-next="eight" data-focus-input-prev="six"
                                class="block w-6 h-6 px-0 md:w-10 md:h-10 md:text-2xl font-extrabold text-center text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required />
                        </div>
                        <div>
                            <input type="text" maxlength="1" name="eight" id="eight" v-model="form.eight"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57" data-focus-input-init
                                data-focus-input-next="nine" data-focus-input-prev="seven"
                                class="block w-6 h-6 px-0 md:w-10 md:h-10 md:text-2xl font-extrabold text-center text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required />
                        </div>
                        <div>
                            <input type="text" maxlength="1" name="nine" id="nine" v-model="form.nine"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57" data-focus-input-init
                                data-focus-input-next="ten" data-focus-input-prev="eight"
                                class="block w-6 h-6 px-0 md:w-10 md:h-10 md:text-2xl font-extrabold text-center text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required />
                        </div>
                        <div>
                            <input type="text" maxlength="1" name="ten" id="ten" v-model="form.ten"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57" data-focus-input-init
                                data-focus-input-prev="nine"
                                class="block w-6 h-6 px-0 md:w-10 md:h-10 md:text-2xl font-extrabold text-center text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required />
                        </div>
                    </div>
                </div>
            


                <div class="flex justify-between">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="remember" type="checkbox" value="" checked
                                class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800"
                                required />
                        </div>
                        <label for="remember" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Согласен
                            с политикой сайта</label>
                    </div>
                    <a href="/policy" class="text-sm text-blue-700 hover:underline dark:text-blue-500">Подробнее</a>
                </div>
                <button type="submit"
                    class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Отправить
                </button>
            </form>
            -->
        </div>
    </VueFinalModal>
</template>

<style>
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>

<script>
/*
export default {
    mounted() {
        function focusNextInput(el, prevId, nextId) {
            if (el.value.length === 0) {
                if (prevId) {
                    let prev = document.getElementById(prevId);
                    prev.focus();
                    prev.select();
                }
            } else {
                if (nextId) {
                    let next = document.getElementById(nextId);
                    next.focus();
                    next.select();
                }
            }
        }

        document.querySelectorAll('[data-focus-input-init]').forEach(function (element) {
            element.addEventListener('keyup', function () {
                const prevId = this.getAttribute('data-focus-input-prev');
                const nextId = this.getAttribute('data-focus-input-next');
                focusNextInput(this, prevId, nextId);
            });
            element.addEventListener('click', function () {
                this.select();
            });
        });
    }
}
*/
</script>