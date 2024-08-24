<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<yml_catalog date="{{ date('Y-m-d H:i') }}">

    <shop>
        <name>Мина.Юристы</name>
        <company>Юридическая компания "Мина"</company>
        <url>https://nedicom.ru/</url>
        <email>m6132@yandex.ru</email>
        <currencies>
          <currency id="RUR" rate="1" />
        </currencies>
        <categories>
          @foreach($categories as $category)
              <category id="{{$category->id}}">
                  {{$category->usl_name}}
              </category>
                @if(count($category->hasuslugi) > 0)
                  @foreach($category->hasuslugi as $child)
                    <category id="{{$child->id}}" parentId="{{$child->main_usluga_id}}">
                      {{$child->usl_name}}
                    </category>
                  @endforeach
                @endif
          @endforeach
        </categories>

        <sets>
            @foreach($sets as $set)   
            <set id="s{{$set->id}}">
              <name>{{$set->title}} @if($set->city){{$set->city->title}}@endif</name>
              <url>@php echo url('/')@endphp/offer/{{$set->id}}</url>
            </set>
            @endforeach
        </sets>

        <offers>
            @foreach($offers as $offer)
            <offer id="offerid{{$offer->usluga_id}}">
              <name>{{$offer->name}}</name>
              <url> @php echo url('/') @endphp/{{$offer->usluga_url}}</url>
              <price>1000</price>
              <currencyId>RUR</currencyId>
              <sales_notes>за услугу</sales_notes>
              <categoryId>{{$offer->main_usluga_id}}</categoryId>
              <set-ids>s{{$offer->offer_id}}</set-ids>
              <picture>@php echo url('/') @endphp/{{$offer->file_path}}</picture>
              <description>{{$offer->usluga_name}}</description>
              <adult>false</adult>
              <expiry>P5Y</expiry>
              <param name="Рейтинг">5.0</param>
              <param name="Число отзывов">17</param>
              <param name="Годы опыта">11</param>
              <param name="Регион">{{$offer->city_title}}</param>
              <param name="Конверсия">1</param>
              <param name="Наличный расчет">да</param>
              <param name="Безналичный расчет">да</param>
            </offer>      
            @endforeach
        </offers>

    </shop>



</yml_catalog>
