<script setup>
import { usePage } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { reactive } from "vue";
import AuthRegisterPropose from "@/Components/AuthRegisterPropose.vue";

let set = defineProps({
    question: "Array",
    placeholder: String,
    answerid: Number,
    answerclass: String,
    authid: "Number",
});


let form = reactive({
    body: "",
    questions_id: set.question.id,
    url: set.question.id,
    answer_id: set.answerid,
});

let user = null;

if (set.authid) {
    user = 1;
}

let comment = "чтобы дать ответ или оставить комментарий";

let placeholder = "Ответ / Комментарий";


//window.scrollTo(0, document.body.scrollHeight);
let submit = (x) => {
    Inertia.post(route("answer.post"), form, {
        preserveState: false,
        preserveScroll: true,
        onFinish: visit => {
            if (!x) {
                window.scrollTo(0, document.body.scrollHeight);
            }
        },
    });

};
</script>

<template>{{ set.answerid }}
    <form v-if="user" @submit.prevent="submit(set.answerid)" class="mx-5" :class="answerclass">
        <textarea v-model="form.body" @input="onInput" spellcheck="true" :maxlength="maxlength" :disabled="!set.authid"
            required
            class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
            id="" rows="4" :placeholder="placeholder"></textarea>
        <div class="my-1 w-full bg-gray-200 rounded-full h-1 dark:bg-gray-700">
            <div class="bg-blue-600 h-1 rounded-full" :style="{ width: progresswidth + '%' }"></div>
        </div>
        <p class="text-xs text-gray-900 dark:text-white">
            Символов: {{ wordscounter }}
        </p>

        <div class="text-center">
            <button type="submit" :disabled="!set.authid" class="my-5 inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg 
                focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800">
                Ответить
            </button>
        </div>
    </form>

    <AuthRegisterPropose v-else :comment="comment" />

    <div class="overflow-y-auto" scroll-region>
        <!-- Your page content -->
    </div>
</template>

<script>
export default {
    data() {
        return {
            maxlength: 2000,
            progresswidth: 0,
            wordscounter: 0,
        };
    },
    methods: {
        onInput(e) {
            this.wordscounter = e.target.value.length;
            if (e.target.value.length <= this.maxlength) {
                this.progresswidth =
                    (100 * e.target.value.length) / this.maxlength;
            }
        },
    },
};
</script>
