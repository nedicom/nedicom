<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>https://nedicom.ru/uslugi/</loc>
        <lastmod>2023-10-05T13:13:11+00:00</lastmod>
        <changefreq>daily</changefreq>
        <priority>1</priority>
    </url>
    <url>
        <loc>https://nedicom.ru/uslugiadd/</loc>
        <lastmod>2023-10-05T13:13:11+00:00</lastmod>
        <changefreq>monthly</changefreq>
        <priority>1</priority>
    </url>
    @foreach ($uslugi as $usluga)
        <url>
            <loc>https://nedicom.ru/uslugi/{{$usluga}}</loc>
            <lastmod>2023-10-05T13:13:11+00:00</lastmod>
            <changefreq>daily</changefreq>
            <priority>1</priority>
        </url>
    @endforeach
</urlset>