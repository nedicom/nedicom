<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($uslugi as $usluga)
        @if ($usluga->second)
            <url>
                <loc>
                    https://nedicom.ru/uslugi/{{ $usluga->cities->url }}/{{ $usluga->main->url }}/{{ $usluga->second->url }}/{{ $usluga->url }}
                </loc>
                <lastmod>{{ $usluga->updated_at->tz('UTC')->toAtomString() }}</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.9</priority>
            </url>
            @else
            <url>
                <loc>
                    https://nedicom.ru/uslugi/{{ $usluga->cities->url }}/{{ $usluga->main->url }}/second/{{ $usluga->url }}
                </loc>
                <lastmod>{{ $usluga->updated_at->tz('UTC')->toAtomString() }}</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.9</priority>
            </url>
        @endif
    @endforeach
</urlset>
