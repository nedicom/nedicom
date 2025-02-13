<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($cities as $city)
        @foreach ($mains as $main)
            @if (count($main->mainhassecond) != 0)
                @foreach ($main->mainhassecond as $second)
                    <url>
                        <loc>https://nedicom.ru/uslugi/{{ $city }}/{{ $main->url }}/{{ $second->url }}</loc>
                        <lastmod>{{ $main->updated_at->tz('UTC')->toAtomString() }}</lastmod>
                        <changefreq>dayly</changefreq>
                        <priority>0.9</priority>
                    </url>
                @endforeach
            @endif
        @endforeach
    @endforeach
</urlset>
