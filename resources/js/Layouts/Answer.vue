<script setup>
import { Inertia } from "@inertiajs/inertia";
import { reactive, ref } from "vue";
import AuthRegisterPropose from "@/Components/AuthRegisterPropose.vue";

let set = defineProps({
    article_id: Number,
    question: Object,
    placeholder: String,
    answerid: Number,
    answerclass: String,
    authid: Number,
    type: String,
    subcomments: Boolean,
});


let form = reactive({
    body: "",
    article_id: set.article_id,
    article_body: set.question.description,
    questions_id: set.question.id,
    url: set.question.id,
    answer_id: set.answerid,
    type: set.type,
    comment_type: "",
});

let user = null;

if (set.authid) {
    user = 1;
}

let comment = "чтобы дать ответ или оставить комментарий";

let placeholder = "Ответ / Комментарий";
let disableBtn = ref(false);

//window.scrollTo(0, document.body.scrollHeight);
let submit = (x) => {
    disableBtn.value = true;
    Inertia.post(route("answer.post"), form, {
        preserveState: false,
        preserveScroll: true,
        onFinish: visit => {
            if (!x) {
                window.scrollTo(0, document.body.scrollHeight);
            }
            disableBtn.value = false;
        },
    });

};

let aicomment = () => {
    Inertia.post(route("article.comment.post"), {form}, {
        preserveState: false,
        preserveScroll: true,
        onFinish: visit => {
                window.scrollTo(0, document.body.scrollHeight);
        },
    });

};
</script>

<template>
    <!-- ai answer for article -->
    <form v-if="set.authid == 95 || set.authid == 1 && set.type == 'article' && set.subcomments" @submit.prevent="aicomment()" class="mx-5 text-center"
        :class="answerclass">
        <p class="">комментарий юриста</p>
        <div class="flex justify-center gap-4">
            <p class="text-center w-48"> <input required list="commenttype" v-model="form.comment_type"
                    class="bg-gray-50 items-center border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </p>
            <datalist id="commenttype">
                <option>Благодарный</option>
                <option>Грустный</option>
                <option>Токсичный</option>
                <option>Вопросительный</option>
                <option>Позитивный</option>
                <option>Приветливый</option>
                <option>Язвительный</option>
                <option>Нейтральный</option>
                <option>Юридический</option>
            </datalist>

            <button type="submit" class="w-48 inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg 
                focus:ring-4 focus:ring-blue-200">
                Комментировать
            </button>
        </div>

    </form>
    <!-- ai answer for article -->

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
            <button type="submit" :disabled="!set.authid || disableBtn == true" class="my-5 inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg 
                focus:ring-4 focus:ring-blue-200 hover:bg-blue-800">
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
