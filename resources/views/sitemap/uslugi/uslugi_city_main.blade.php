<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($cities as $city)
        @foreach ($mains as $main)
            <url>
                <loc>https://nedicom.ru/uslugi/{{$city}}/{{$main->url}}</loc>
                <lastmod>{{ $main->updated_at->tz('UTC')->toAtomString() }}</lastmod>
                <changefreq>daily</changefreq>
                <priority>1</priority>
            </url>
        @endforeach
    @endforeach
</urlset>
