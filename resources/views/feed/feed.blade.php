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
          <category id="{{$mainusluga->id}}">{{$mainusluga->usl_name}}</category>
          @foreach($uslugi as $usluga)   
          <category id="{{$usluga->id}}" parentId="{{$usluga->main_usluga_id}}">{{$usluga->usl_name}}</category>
          @endforeach
        </categories>

        <sets>
            @foreach($uslugi as $usluga)   
            <set id="s{{$usluga->id}}">
              <name>{{$usluga->usl_name}} по г. Симферополь</name>
              <url>https://nedicom.ru/{{$usluga->url}}</url>
            </set>
            @endforeach
        </sets>

        <offers>
            @foreach($users as $user)
            <offer id="user{{$user->id}}">
                <name>{{$user->name}}</name>
                <url>https://nedicom.ru/{{$user->id}}</url>
                <price from="true">1000</price>
                <currencyId>RUR</currencyId>
                <sales_notes>за консультацию</sales_notes>
                <categoryId>{{$user->data}}</categoryId>
                <set-ids>{$user->data}}
                </set-ids><!--Можно указать и несколько сетов через запятую-->
                <picture>https://nedicom.ru/{{$user->avatar_path}}</picture>
                <description></description>
                <adult>false</adult>
                <expiry>P5Y</expiry>
                <!--Этот набор параметров обязателен-->
                <param name="Рейтинг">5.0</param>
                <param name="Число отзывов">17</param>
                <param name="Годы опыта">10+</param>
                <param name="Регион">Симферополь</param>
                <param name="Конверсия">1.935</param><!--Это произвольное число, оно не обязано быть от 0 до 1-->
                <!--Этот набор параметров необязателен-->
                <param name="Ссылка на телефон">https://worker.sample.s3.yandex.net/profile/DmitrijA-923950?occupationId=%2Fremont-i-stroitel_stvo&amp;specId=%2Fremont-i-stroitel_stvo%2Felektromontajnye-raboty&amp;serviceId=%2Fremont-i-stroitel_stvo%2Felektromontajnye-raboty%2Fprokladka-kabelya&amp;action=openPhone</param>
                <param name="Выезд на дом">нет</param>
                <param name="Работа по договору">да</param>
                <param name="Наличный расчет">да</param>
                <param name="Безналичный расчет">да</param>
              </offer>            
            @endforeach
        </offers>

    </shop>



</yml_catalog>
