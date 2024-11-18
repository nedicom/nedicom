<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { ref, computed, onBeforeMount } from 'vue'

defineProps({
    auth: Object,
});

let questions = [{
    question: `Должен ли юрист заключать договор с клиентом?`,
    answers: [{
        answer: 'Конечно должен. Договор - это моя гарантия!',
        correct: true,
    }, {
        answer: 'Не должен, но лучше, заключить'
    }, {
        answer: 'Зачем нужен договор? Юрист знает, что делать'
    }]
}, {
    question: `Можно ли юристу платить наличными?`,
    answers: [{
        answer: 'Обязательна квитанция с указанием услуг, за которые получены деньги',
        correct: true,
    }, {
        answer: 'Конечно, пусть берет, справки не нужны'
    }, {
        answer: 'Можно, но пусть пишет расписку'
    }]
}, {
    question: `Что должно быть в предмете договора с юристом?`,
    answers: [{
        answer: 'Обязательно указание услуги (услуг) и ее (их) стоимости',
        correct: true,
    }, {
        answer: 'Там должно быть что-то написано'
    }, {
        answer: 'Пусть пишет что хочет, я такое все равно не понимаю'
    }]
}, {
    question: `Должен ли юрист понимать какие обстоятельтсва нужно доказывать в суде?`,
    answers: [{
        answer: 'Конечно. Юрист должен это знать в первую очередь и объяснить мне.',
        correct: true,
    }, {
        answer: 'А что такое обстоятельтсва?'
    }, {
        answer: 'Не знаю. Он же юрист. Ему виднее'
    }]
}, {
    question: `Должен ли юрист Вас информировать о ходе судебного процесса?`,
    answers: [{
        answer: 'Да. Мне нужно объяснить о чем иск, информировать меня до процесса и после.',
        correct: true,
    }, {
        answer: 'Нет. Юрист должен сам все знать и понимать без моего участия'
    }, {
        answer: 'Не знаю, наверное должен, если считает это нужным'
    }]
}, {
    question: `Как лучше платить юристу за услуги?`,
    answers: [{
        answer: 'Буду платить частями. Перед каждым действием.',
        correct: true,
    }, {
        answer: 'Я ничего не заплачу, пока юрист не выиграет дело'
    }, {
        answer: 'Я отдам все деньги сразу. Чтобы была мотивация работать'
    }]
}, {
    question: `Если вы платите по безналу, в какой форме это лучше сделать`,
    answers: [{
        answer: 'Лучше всего СБП переводом по реквизитам в договоре.',
        correct: true,
    }, {
        answer: 'СБП переводом на номер, который укажет юрист'
    }, {
        answer: 'Перекину деньги без договора'
    }]
},];

let state = ref('question');
let currentQuestion = ref(0);
let selectedAnswer = ref(0);
let score = ref(0);

const question = computed(() => {
    return questions[currentQuestion.value];
})

const scorePercentage = computed(() => {
    return score.value * 100 / questions.length;
})

function randomizeOrder() {
    questions.map(question => {
        question.answers.sort((a, b) => Math.random() - 0.5)
        question.answers.map((answer, index) => answer.id = index);
    });
    questions.sort((a, b) => Math.random() - 0.5);
}

function handleResponse(event) {
    const selected = question.value.answers.find(a => a.id == event.target.dataset.id);
    selectedAnswer.value = selected;
    if (selected.correct) {
        score.value++;
    }
    state.value = 'answer';
};

function handleNext(event) {
    currentQuestion.value++;
    if (currentQuestion.value >= questions.length) {
        state.value = 'results';
    } else {
        state.value = 'question';
    }
};

function handleRestart(event) {
    currentQuestion.value = 0;
    score.value = 0;
    state.value = 'question';
    randomizeOrder();
};

onBeforeMount(() => {
    randomizeOrder();
});

</script>

<template>

    <Head>
        <title>Спасибо, что выбрали нас</title>
        <meta name="description" content="Успешно отправлен телефон" />
    </Head>

    <div class="min-h-screen">
        <MainHeader :auth="auth" />

        <Header :modalPageTitle="'Успешно отправленный телефон'" />

        <h1
            class="pt-6 md:pt-24 pb-6 w-full max-w-7xl px-4 md:px-5 lg:px-5 mx-auto text-gray-900 text-4xl font-bold font-manrope leading-normal">
            Телефон отправлен</h1>

        <section class="pb-6 relative">
            <p class="pb-12 w-full max-w-7xl px-4 md:px-5 lg:px-5 mx-auto text-gray-500 text-xl">С Вами свяжется
                профильный юрист либо специалист по работе с клиентами. Обычно это происходит в течение дня.</p>

            <p class="pb-12 w-full max-w-7xl px-4 md:px-5 lg:px-5 mx-auto text-gray-800 text-2xl">А пока мы хотим Вам
                рассказать, как мы работаем с проблемой некачественных юридических услуг. Пройдите тест, чтобы понять о
                чем речь.</p>
            <div id="app" class="pb-12 w-full max-w-7xl px-4 md:px-5 lg:px-5 mx-auto text-gray-800 text-xl">
                <div v-if="state == 'question'" class="text-center grid justify-items-center">
                    <h1 class="text-2xl mb-5">{{ question.question }}</h1>
                    <ul class="list-none w-full md:w-2/3 lg:w-1/3">
                        <li v-for="answer in question.answers">
                            <button
                                class="block border border-black py-2 px-5 mb-3 rounded hover:bg-blue-100 shadow w-full "
                                v-on:click="handleResponse" v-bind:data-id="answer.id">{{
                                    answer.answer }}</button>
                        </li>
                    </ul>
                </div>
                <div v-if="state == 'answer'" class="text-center grid justify-items-center">
                    <h1 class="text-2xl mb-5">{{ question.question }}</h1>
                    <ul class="list-none w-full md:w-2/3 lg:w-1/3">
                        <li v-for="answer in question.answers">
                            <div class="block border border-black py-2 px-5 mb-3 rounded shadow w-full text-center"
                                v-bind:class="{ 'bg-gray-700 text-white': answer.correct, 'bg-red-700 text-white': answer.id == selectedAnswer.id && !answer.correct }">
                                {{ answer.answer }}</div>
                        </li>
                    </ul>
                    <div class="text-center">
                        <p v-if="selectedAnswer.correct" class="mt-8 text-lg">🎉 Правильный ответ! 🎉</p>
                        <p v-else class="mt-8 text-lg">Неправильный ответ</p>
                        <button v-on:click="handleNext"
                            class="inline-block mx-auto rounded border border-black py-2 px-5 shadow mt-5 hover:bg-blue-100">Дальше
                        </button>
                    </div>
                </div>
                <div v-if="state == 'results'" class="text-center grid justify-items-center">
                    <h1 class="text-xl mb-5">Поздравляем, Вы прошли опрос</h1>
                    <p class="my-5 py-3 text-2xl text-center">Вы набрали {{ score }} очков из {{
                        questions.length }}</p>
                    <p v-if="scorePercentage > 90">Вы настоящий знаток темы опроса</p>
                    <p v-else-if="scorePercentage >= 50">Вы кое - что понимаете в нашем опросе</p>
                    <p v-else>Вам нужно внимательнее относиться к теме опроса</p>
                    <div class="text-center">
                        <button v-on:click="handleRestart"
                            class="inline-block mx-auto rounded border border-black py-2 px-5 shadow mt-10 hover:bg-blue-100">пройти
                            снова</button>
                    </div>
                </div>
            </div>
        </section>

        <MainFooter />

    </div>
</template>