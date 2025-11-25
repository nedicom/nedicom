<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($lawyers as $lawyer)
        <url>
            <loc>https://nedicom.ru/lawyers/{{ $lawyer->id }}</loc>
            @if ($lawyer->created_at)
                <lastmod>{{ $lawyer->created_at->tz('UTC')->toAtomString() }}</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.9</priority>
            @else
                <changefreq>monthly</changefreq>
                <priority>0.2</priority>
            @endif            
        </url>
    @endforeach
</urlset>
