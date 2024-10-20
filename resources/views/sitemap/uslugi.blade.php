<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($uslugi as $usluga)
        <url>
            <loc>https://nedicom.ru/uslugi/{{ $usluga->url }}</loc>
            <lastmod>{{ $usluga->created_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.9</priority>
        </url>
        {{$usluga}}
    @endforeach
</urlset>