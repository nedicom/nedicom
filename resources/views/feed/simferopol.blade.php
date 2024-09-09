<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<yml_catalog date="{{ date('Y-m-d H:i') }}">

    <shop>
        <name>Юристы (Симферополь)</name>
        <company>ИП Мина О. В.</company>
        <url>https://nedicom.ru/</url>
        <email>m6132@yandex.ru</email>
        <currencies>
            <currency id="RUR" rate="1" />
        </currencies>
        <categories>
            @foreach ($categories as $category)
            <category id="{{ $category->id }}">
                {{ $category->usl_name }}
            </category>
            @if (count($category->hasuslugi) > 0)
            @foreach ($category->hasuslugi as $child)
            <category id="{{ $child->id }}" parentId="{{ $child->main_usluga_id }}">
                {{ $child->usl_name }}
            </category>
            @endforeach
            @endif
            @endforeach
        </categories>

        <sets>
            @foreach ($sets as $set)
            <set id="s{{ $set->id }}">
                <name>{{ $set->usl_name }} в Симферополе</name>
                <url>@php echo url('/')@endphp/offers/simferopol/{{ $set->url }}</url>
            </set>

            @if (count($set->hasuslugi) > 0)
            @foreach ($set->hasuslugi as $child)
            <set id="s{{ $child->id }}">
                <name>{{ $child->usl_name }}</name>
                <url>@php echo url('/')@endphp/offers/simferopol/{{ $set->url }}/{{ $child->url }}</url>
            </set>
            @endforeach
            @endif
            @endforeach
        </sets>

        <offers>
            @foreach ($offers as $offer)

            @php
            if($offer->second) {$second = '/'.$offer->second->url;}
            else {$second = '/second';}
            @endphp

            <offer id="offer{{ $offer->id }}">
                <name>{{ $offer->user->name }}</name>
                <url> @php echo url('/') @endphp/offers/{{$offer->cities->url}}/{{$offer->main->url}}@php echo ($second) @endphp/{{ $offer->url }}
                </url>
                <price>1000</price>
                <currencyId>RUR</currencyId>
                <sales_notes>за услугу</sales_notes>
                <categoryId>{{ $offer->main_usluga_id }}</categoryId>
                <set-ids>s{{ $offer->main_usluga_id }}
                    @if (count($offer->mainwithsecond) > 0)
                    @foreach ($offer->mainwithsecond as $child)
                    , s{{ $child->id }}
                    @endforeach
                    @endif
                </set-ids>
                <picture>@php echo url('/') @endphp/{{ $offer->user->avatar_path }}</picture>
                <description>{{ $offer->usl_name }}</description>
                <adult>false</adult>
                <expiry>P5Y</expiry>
                {{$offer}}
                @if(!is_null($offer->avg_review))
                <param name="Рейтинг">{{ round($offer->avg_review, 1) }}</param>
                @else<param name="Рейтинг">0</param>
                @endif
                <param name="Число отзывов">{{ $offer->count_review }}</param>
                <param name="Годы опыта">{{ $offer->expirience }}</param>
                <param name="Регион">{{ $offer->cities->title }}</param>
                <param name="Конверсия">1</param>
                <param name="Наличный расчет">да</param>
                <param name="Безналичный расчет">да</param>
                <param name="Исполнитель проверен">да</param>
            </offer>
            @endforeach
        </offers>

    </shop>



</yml_catalog>