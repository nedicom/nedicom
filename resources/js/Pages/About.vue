<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import Modal from "@/Components/Modal.vue";
import { Head } from "@inertiajs/inertia-vue3";

defineProps({
  auth: Object,
});

</script>

<template>

    <Head>
        <title>О компании</title>
        <meta name="description" content="Компания Мина - юристы всех категорий по Республике Крым и городу Москва." />
    </Head>

    <div class="min-h-screen">
        <MainHeader :auth="auth" />

        <Header :modalPageTitle="'О компании'" />

        <h1
            class="pt-24 pb-12 w-full max-w-7xl px-4 md:px-5 lg:px-5 mx-auto text-gray-900 text-4xl font-bold font-manrope leading-normal">
            Контакты</h1>


        <section class="pb-24 relative">
            <div class="w-full max-w-7xl px-4 md:px-5 lg:px-5 mx-auto">
                <div itemscope itemtype="https://schema.org/Organization" class="w-full justify-start items-center gap-8 grid lg:grid-cols-2 grid-cols-1">
                    <img itemprop="image" class="lg:mx-0 mx-auto h-full rounded-3xl object-cover"
                        src="https://pagedone.io/asset/uploads/1717751272.png" alt="наследственный юрист Москва" />
                    <div class="w-full flex-col justify-start lg:items-start items-center gap-10 inline-flex">
                        <div class="w-full flex-col justify-start lg:items-start items-center gap-4 flex">
                            <div
                                class="text-gray-500 text-base font-normal leading-relaxed lg:text-start text-center">
                                <h2 itemprop="name"
                                    class="text-gray-900 text-3xl font-bold font-manrope leading-normal lg:text-start text-center">
                                    Юридическая компания "Мина"</h2>
                                <p
                                    class="text-gray-500 text-base font-normal leading-relaxed lg:text-start text-center mb-2">
                                    Миссией компании является изменение подхода к формированию рынка юридических услуг.
                                    Для клиента в первую очередь важно исключить риск мошенничества при получении
                                    услуги. К сожалению на рынке юр. услуг в РФ это явление в избытке.
                                    Связана такая тенденция с низким уровнем образования, которое дают ВУЗы, с высокой
                                    себестоимостью услуги, большим количеством мошеннических предложений по низкой цене
                                    и отсутсвием государственного регулирования.
                                    Второй критерий важный для клиента - это возможность оценки перспектив по делу.
                                    Большинство "нетиповых" дел мы берем в работу, подготавливая правовую позицию для
                                    клиента.
                                    Третий важный элемент комфортной работы - отчетность юриста перед судебными
                                    заседаниями, после и совместная подготовка к процессам.</p>

                                <p itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                                    Адрес (Москва):
                                    <span itemprop="streetAddress">Каланчевская улица, 16, строение 1 </span>
                                    <span itemprop="postalCode">129090 </span>
                                    <span itemprop="addressLocality">Москва, Россия</span>
                                </p>
                                телефон:<p itemprop="telephone"> 8 (985) 558 21 70 </p>
                                e-mail: <p itemprop="email">m6132@yandex.ru</p>
                                ГРН: 317910200050560
                                <meta itemprop="iso6523Code" content="317910200050560" />
                                ИНН: 910305709385
                                <meta itemprop="iso6523Code" content="910305709385" />
                                <p>
                                    С уважением, <span itemprop="alumni" itemscope itemtype="https://schema.org/Person">
                                        <span itemprop="name">Ольга Мина</span>
                                    </span>,
                                    <span itemprop="alumni" itemscope itemtype="https://schema.org/Person">
                                        <span itemprop="name">Марк Мина</span>, 
                                    </span>
                                    <span itemprop="alumni" itemscope itemtype="https://schema.org/Person">
                                        <span itemprop="name">Москаленко Дмитрий</span>, 
                                    </span>
                                    <span itemprop="alumni" itemscope itemtype="https://schema.org/Person">
                                        <span itemprop="name">Добренький Андрей</span>
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-center">
                            <div class="mt-5 md:w-1/2 ">

                                <Modal :ModalBtnText="'записаться'" :secondtext="'на консультацию'" />

                                <a href='tel:89855582170' type="button" class="my-5 w-full inline-flex items-center justify-center text-white mr-2 bg-blue-600 hover:bg-blue-700                     
    font-medium rounded-lg py-2.5
  ">
                                    <svg class="mr-2 w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                    </svg>
                                    <span class="flex flex-col">
                                        <span class="">
                                            8 (985) 558 2170
                                        </span>
                                        <span class="text-xs">
                                            обратиться по телефону
                                        </span>
                                    </span>
                                </a>

                                <a :href="'https://wa.me/79855582170?text=Здравствуйте.'" type="button" aria-label="Calltowhatsapp" class="mb-5 w-full inline-flex items-center justify-center text-white mr-2 bg-emerald-700 hover:bg-emerald-800                     
    font-medium rounded-lg py-2.5
  ">
                                    <svg class="mr-2 w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                    </svg>

                                    <span class="flex flex-col">
                                        <span class="">
                                            8 (985) 558 2170
                                        </span>
                                        <span class="text-xs">
                                            написать в whatsapp
                                        </span>
                                    </span>
                                </a>

                                <a href="https://yandex.ru/maps/-/CDXo5AzK"
                                    class="w-full inline-flex items-center justify-center py-2.5 mr-2 text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 inline-flex items-center focus:ring-4 focus:ring-primary-300 font-medium rounded-lg px-5 py-2.5 text-center dark:focus:ring-primary-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                    </svg>
                                    <span class="flex flex-col">
                                        проложить маршрут
                                        <span class="text-xs">
                                            на картах
                                        </span>
                                    </span>
                                </a>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <section class="py-24 relative">
            <div class="w-full max-w-7xl px-4 md:px-5 lg:px-5 mx-auto">
                <div itemscope itemtype="https://schema.org/Organization" class="w-full justify-start items-center gap-8 grid lg:grid-cols-2 grid-cols-1">

                    <div class="w-full flex-col justify-start lg:items-start items-center gap-10 inline-flex">
                        <div class="w-full flex-col justify-start lg:items-start items-center gap-4 flex">
                            <div 
                                class="text-gray-500 text-base font-normal leading-relaxed lg:text-start text-center">
                                <h2 itemprop="name"
                                    class="text-gray-900 text-3xl font-bold font-manrope leading-normal lg:text-start text-center">
                                    Адвокатский кабинет "Мина М. А."</h2>
                                <p
                                    class="text-gray-500 text-base font-normal leading-relaxed lg:text-start text-center mb-2">
                                    Миссией адвоката является расширение перечня дел, подсудных суду присяжных. В РФ, как и в большинстве стран,
                                отправление правосудия отдано профессионалам - судьям. В то же время система статистики правоохрантиельных органов, в народе -
                                 "палочная система" не свидетельствует о преодолении конкретной негативной проблемы общества. Суд профессионалов
                                предполагает возможность предсказывать результата дела еще на этапе досудебного следствия, при этом не требуется того количества доказательств, которое 
                            хотел бы видеть обыватель для установления вины. Суд присяжных позволил бы улучшить качетсво следствия и уменьшить риск ошибки в отношении невиного лица.
                        Такой подход также является основанием для повышения уровня зарплат в правоохранительной системе и изменением системы отчетности.</p>

                                <p itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                                    Адрес (Республика Крым):
                                    <span itemprop="streetAddress">Долгоруковская улица, 5 </span>
                                    <span itemprop="postalCode">295000 </span>
                                    <span itemprop="addressLocality">Симферооль, Республика Крым, Россия</span>
                                </p>
                                телефон:<p itemprop="telephone"> 8 (978) 8838 9780 </p>
                                e-mail: <p itemprop="email">m6132@yandex.ru</p>
                                ГРН: 910209968612
                                <meta itemprop="iso6523Code" content="910209968612" />
                                ИНН: 910209968612
                                <meta itemprop="iso6523Code" content="910209968612" />
                                <p>
                                    С уважением, <span itemprop="alumni" itemscope itemtype="https://schema.org/Person">
                                        <span itemprop="name">Марк Мина</span>
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-center">
                            <div class="mt-5 md:w-1/2 ">

                                <Modal :ModalBtnText="'записаться'" :secondtext="'на консультацию'" />

                                <a href='tel:89788838978' type="button" class="my-5 w-full inline-flex items-center justify-center text-white mr-2 bg-blue-600 hover:bg-blue-700                     
    font-medium rounded-lg py-2.5
  ">
                                    <svg class="mr-2 w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                    </svg>
                                    <span class="flex flex-col">
                                        <span class="">
                                            8 (978) 8838 978
                                        </span>
                                        <span class="text-xs">
                                            обратиться по телефону
                                        </span>
                                    </span>
                                </a>

                                <a :href="'https://wa.me/79788838978?text=Здравствуйте.'" type="button" aria-label="Calltowhatsapp" class="mb-5 w-full inline-flex items-center justify-center text-white mr-2 text-white mr-2 bg-emerald-700 hover:bg-emerald-800                   
    font-medium rounded-lg py-2.5
  ">
                                    <svg class="mr-2 w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                    </svg>

                                    <span class="flex flex-col">
                                        <span class="">
                                            8 (978) 8838 978
                                        </span>
                                        <span class="text-xs">
                                            написать в whatsapp
                                        </span>
                                    </span>
                                </a>

                                <a href="https://yandex.ru/maps/-/CDdIMD8m"
                                    class="w-full inline-flex items-center justify-center py-2.5 mr-2 text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 inline-flex items-center focus:ring-4 focus:ring-primary-300 font-medium rounded-lg px-5 py-2.5 text-center dark:focus:ring-primary-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                    </svg>
                                    <span class="flex flex-col">
                                        проложить маршрут
                                        <span class="text-xs">
                                            на картах
                                        </span>
                                    </span>
                                </a>

                            </div>
                        </div>

                    </div>

                    <img itemprop="image" class="lg:mx-0 mx-auto h-full rounded-3xl object-cover"
                    src="https://nedicom.ru/storage/images/landing/main/secondm.webp" alt="Адвокат Мина Крым Симферополь" />

                </div>
            </div>
        </section>

        <MainFooter />

    </div>
</template>