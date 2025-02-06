<template>

    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
            <div class="mx-auto max-w-screen-sm text-center">


                <div class="flex flex-col justify-center">
                    <div>
                        <img src="/404.svg" />
                    </div>
                    <div class="flex justify-center">
                        <ul
                        class="w-2/3 md:w-2/3 text-lg list-none text-gray-900 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <li class="w-full px-4 py-2 rounded-t-lg dark:border-gray-600"><a class="border-b border-gray-200 hover:border-none"
                                href="https://nedicom.ru/">главная</a>
                        </li>
                        <li class="w-full px-4 py-2 rounded-t-lg dark:border-gray-600"><a class="border-b border-gray-200 hover:border-none"
                                href="https://nedicom.ru/popular">лента</a>
                        </li>
                        <li class="w-full px-4 py-2 rounded-t-lg dark:border-gray-600"><a class="border-b border-gray-200 hover:border-none"
                                href="https://nedicom.ru/questions/add">вопрос юристу</a>
                        </li>
                        <li class="w-full px-4 py-2 rounded-t-lg dark:border-gray-600"><a class="border-b border-gray-200 hover:border-none"
                                href="https://nedicom.ru/uslugi">услуги</a>
                        </li>
                    </ul>
                    </div>
                   
                </div>
                <p class="mb-4 text-3xl tracking-tight font-bold text-gray-900 md:text-4xl dark:text-white mt-10">{{ title }}
                </p>
                <p class="mb-4 text-lg font-light text-gray-500 dark:text-gray-400">{{ description }}</p>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    props: {
        status: Number,
    },
    computed: {
        title() {
            return {
                503: '503: Сервис недоступен',
                500: '500: Ошибка сервера',                
                404: '404: Страница не найдена',
                403: '403: Запрещено',
                410: '410: Страница удалена',
            }[this.status]
        },
        description() {
            return {
                503: 'Извините, мы проводим техническое обслуживание. Пожалуйста, попробуйте позже.',
                500: 'Упс, что-то пошло не так на наших серверах.',
                404: 'К сожалению, не удалось найти страницу, которую Вы ищете. Посмотрите еще что-нибудь на сайте.',
                403: 'К сожалению, доступ к этой странице запрещен. Посмотрите еще что-нибудь на сайте.',
                410: 'Страница была удалена. Такое бывает.',
            }[this.status]
        },
    },
}
</script>