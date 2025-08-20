<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\LanguageService;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->segment(1);
        
        if (array_key_exists($locale, LanguageService::SUPPORTED_LANGUAGES)) {
            LanguageService::setLanguage($locale);
        } else {
            LanguageService::setLanguage(LanguageService::DEFAULT_LANGUAGE);
        }
        
        return $next($request);
    }
} 