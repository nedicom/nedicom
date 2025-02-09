<?php 
echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($questions as $item)
        <url>
            <loc>https://nedicom.ru/questions/{{ $item->url }}</loc>
            <lastmod>{{ \Carbon\Carbon::createFromTimestamp(strtotime($item->getRawOriginal('created_at')))->format('Y-m-d\Th:i:sT') }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach
</urlset>