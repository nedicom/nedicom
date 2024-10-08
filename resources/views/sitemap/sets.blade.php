<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($sets as $set)
        <url>
            <loc>https://nedicom.ru/uslugi/simferopol/{{ $set->url }}</loc>
            <changefreq>daily</changefreq>
            <priority>1</priority>
        </url>
        @if($set->mainhassecond)
        
            @foreach ($set->mainhassecond as $secondset)
                <url>
                    <loc>https://nedicom.ru/uslugi/simferopol/{{ $set->url }}/{{ $secondset->url }}</loc>
                    <changefreq>daily</changefreq>
                    <priority>1</priority>
                </url>
            @endforeach

        @endif            
    @endforeach
</urlset>