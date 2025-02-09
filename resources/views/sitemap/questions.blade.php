<?php
echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>https://nedicom.ru/questions/add</loc>
        <lastmod>2023-12-08T16:26+03:00</lastmod>
        <changefreq>monthly</changefreq>
        <priority>1</priority>
    </url>
    @foreach ($main as $usluga)
    <url>
        <loc>https://nedicom.ru/questions/{{ $usluga->url }}</loc>
        <lastmod>{{ \Carbon\Carbon::createFromTimestamp(strtotime($usluga->getRawOriginal('updated_at')))->format('Y-m-d\TH:i') }}+03:00</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach
    @foreach ($questions as $item)
    <url>
        <loc>https://nedicom.ru/questions/{{ $item->url }}</loc>
        <lastmod>{{ \Carbon\Carbon::createFromTimestamp(strtotime($item->getRawOriginal('updated_at')))->format('Y-m-d\TH:i') }}+03:00</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
</urlset>