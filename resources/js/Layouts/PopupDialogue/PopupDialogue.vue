<script setup>
import Question from "@/Layouts/PopupDialogue/Question.vue";
import Answer from "@/Layouts/PopupDialogue/Answer.vue";
import { ref, reactive } from "vue";
import { usePage } from "@inertiajs/inertia-vue3";

const props = defineProps({
    question: Object,
});

const message = ref(null);
let jsonresp = ref(null);
let vision = ref(false);
/*
if (usePage().props.value.dialogue.message) {
    jsonresp = JSON.parse(usePage().props.value.dialogue.message.json);
}
*/

let processing = ref(false);
let printing = ref(false);
let empty = ref(false);

let btncls = "w-14 h-14";
let rotate = "";
let block;


let form = reactive({
    mess: "message.value.innerText",
    //location: usePage().props.value.ziggy.url,
    //location_header: usePage().props.value.ziggy.url,
    location: 'nedicom',
    location_header: 'test',
});

/* delay
setTimeout(() => {
    vision.value = true;
}, 60000);*/

const opendialogue = function(){
   if (vision.value == false) {
        btncls = "w-8 h-8";
        rotate = "rotate-45";
        vision.value = true;
    } else {
        btncls = "w-14 h-14";
        rotate = "rotate-0";
        vision.value = false;
    }
};

const send = async function () {
    if (message.value.innerText == "") {
        empty.value = true;
    }
    else {
        empty.value = false;
        processing.value = true;
        form.mess = message.value.innerText;
        message.value.innerText = "";

        (async () => {
            let resp = await fetch(route("message"), {
                method: "POST",
                headers: {
                    "Content-Type": "application/json;charset=utf-8",
                    "Access-Control-Allow-Origin": "*",
                    "Access-Control-Allow-Credentials": true,
                },
                body: JSON.stringify(form),
            });

            if (resp.ok) {
                jsonresp = await resp.json();
            } else {
                alert("Ошибка HTTP: ");
            }
            processing.value = false;
        })();

        block = document.getElementById("scrollparent");
        block.scrollTop += 9999;

        await new Promise((resolve, reject) => {
            setTimeout(() => resolve((printing.value = true)), 5000);
        });

        block.scrollTop += 9999;

        (async () => {
            let resptoai = await fetch(route("message.sent"), {
                method: "POST",
                headers: {
                    "Content-Type": "application/json;charset=utf-8",
                    "Access-Control-Allow-Origin": "*",
                    "Access-Control-Allow-Credentials": true,
                },
                body: JSON.stringify(form),
            });
            if (resptoai.ok) {
                jsonresp = await resptoai.json();
                block.scrollTop += block.scrollHeight;
                printing.value = false;
                processing.value = false;
            } else {
                alert("Пользователь занят. Попробуйте позже");
            }
        })();
    }
};
</script>

<template>
    <div class="fixed w-full md:w-96 md:right-1 right-1 bottom-0 z-50">
        <div class="flex flex-col mr-0 ml-2">
            <!-- dialogue button -->
            <div class="flex justify-end mb-5 mr-5">
                <button @click="opendialogue" type="button" id="hellobutton"
                    class="z-50 flex items-center justify-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800"
                    :class="btncls">
                    <svg class="w-5 h-5 transition-transform delay-300" :class="rotate" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 1v16M1 9h16" />
                    </svg>
                </button>
            </div>
            <!-- dialogue button -->

            <div v-if="vision"
                class="h-[32rem] flex flex-col items-start bg-white rounded-t-2xl border-t-8 border-x-4 border-gray-900">
                <div class="sticky top-0 w-full my-1 mr-3 pt-1 flex px-2">
                    <img class="w-8 h-8 rounded-full"
                        src="https://nedicom.ru/storage/usr/94/avatar/1728156724avatar.webp" alt="юрист сайта" />
                    <div class="grid grid-cols-1 ml-3 content-end">
                        <div class="text-xs font-bold">Андрей Дмитриевич</div>
                        <div class="text-xs font-semibold text-gray-500">
                            сейчас на связи
                        </div>
                    </div>
                </div>
                <!-- dialogue -->

                <div class="h-full w-full bg-[url('https://nedicom.ru/storage/default/wabg.jpg')] 
                    bg-cover overflow-y-auto scroll-smooth rounded-t-md py-5" id="scrollparent">
                    <div v-if="!jsonresp"
                        class="mx-5 h-full text-center  grid grid-cols-1 content-center font-semibold">
                        <div class="inline-block  mb-3"><span class="bg-white py-2 px-3 rounded-lg">Юрист на
                                связи!</span></div>
                        <div class="inline-block mt-2 mb-3"><span class="bg-white  py-2 px-2 rounded-lg">Задайте
                                вопрос,</span></div>
                        <div class="inline-block mt-2"><span class="bg-white  py-2 px-2 rounded-lg">который Вас
                                интересует</span></div>
                    </div>
                    <div class="grid grid-cols-1 content-end" id="scrollchild">
                        <div v-for="question in jsonresp" :key="question">
                            <Question v-if="question.user_message" :question="question.user_message" />
                            <Answer v-if="question.ai_message" :answer="question.ai_message" />
                        </div>
                        <Answer v-if="printing">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="animate-pulse bi bi-three-dots" viewBox="0 0 16 16">
                                <path
                                    d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z" />
                            </svg>
                        </Answer>
                    </div>
                </div>

                <!-- message input @keyup.enter="send"-->
                <div class="sticky top-[100vh] w-full my-1 flex px-2">
                    <div ref="message" contenteditable="true"
                        class="w-full rounded-md p-1 max-h-20 overflow-y-auto scroll-smooth text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 focus:text-gray-700 focus:bg-white  focus:outline-none"
                        :class="{ 'border-2 border-rose-600': empty }"></div>
                    <!-- send button -->
                    <div class="grid grid-cols-1 content-center ml-1">
                        <button :disabled="processing" :onclick="send" type="button"
                            class="flex items-center justify-center text-white bg-blue-700 rounded-lg w-7 h-7 hover:bg-blue-800">
                            <svg v-if="processing" aria-hidden="true" role="status"
                                class="animate-spin inline w-4 h-4 me-3 mr-1 text-gray-200 dark:text-gray-600"
                                viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                    fill="currentColor" />
                            </svg>

                            <svg v-else aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                fill="none" viewBox="0 0 18 18">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1"
                                    d="M17.218,2.268L2.477,8.388C2.13,8.535,2.164,9.05,2.542,9.134L9.33,10.67l1.535,6.787c0.083,0.377,0.602,0.415,0.745,0.065l6.123-14.74C17.866,2.46,17.539,2.134,17.218,2.268 M3.92,8.641l11.772-4.89L9.535,9.909L3.92,8.641z M11.358,16.078l-1.268-5.613l6.157-6.157L11.358,16.078z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    background: #888;
    border-radius: 5px;
}

/* Handle */
::-webkit-scrollbar-thumb {
    background: #000;
    border-radius: 5px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
