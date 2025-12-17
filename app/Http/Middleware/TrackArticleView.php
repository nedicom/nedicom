<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\ArticleViewService;

class TrackArticleView
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Только GET запросы к статьям
        if ($request->isMethod('GET')) {
            $articleId = $this->extractArticleId($request->path());
            
            if ($articleId) {
                // Запускаем в фоне, чтобы не замедлять ответ
                dispatch(function () use ($articleId) {
                    app(ArticleViewService::class)->countView($articleId);
                })->afterResponse();
            }
        }
        
        return $response;
    }
    
    private function extractArticleId(string $path): ?int
    {
        // Ищем /articles/123 или /blog/123
        if (preg_match('/\/(?:articles?|blog|posts)\/(\d+)/', $path, $matches)) {
            return (int) $matches[1];
        }
        
        return null;
    }
}