<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageService
{
    const SUPPORTED_LANGUAGES = [
        'vi' => 'Tiếng Việt',
        'en' => 'English',
        'zh' => '中文'
    ];

    const DEFAULT_LANGUAGE = 'vi';

    public static function getCurrentLanguage()
    {
        return Session::get('locale', self::DEFAULT_LANGUAGE);
    }

    public static function setLanguage($language)
    {
        if (array_key_exists($language, self::SUPPORTED_LANGUAGES)) {
            Session::put('locale', $language);
            App::setLocale($language);
            return true;
        }
        return false;
    }

    public static function getSupportedLanguages()
    {
        return self::SUPPORTED_LANGUAGES;
    }

    public static function translate($key, $params = [])
    {
        $translation = __($key);
        
        if (!empty($params)) {
            foreach ($params as $param => $value) {
                $translation = str_replace(':' . $param, $value, $translation);
            }
        }
        
        return $translation;
    }

    public static function getLanguageFlag($language)
    {
        $flags = [
            'vi' => '🇻🇳',
            'en' => '🇺🇸',
            'zh' => '🇨🇳'
        ];
        
        return $flags[$language] ?? '🌐';
    }

    public static function getLocalizedUrl($url, $language = null)
    {
        $language = $language ?: self::getCurrentLanguage();
        
        if ($language === self::DEFAULT_LANGUAGE) {
            return $url;
        }
        
        return '/' . $language . $url;
    }

    public static function getLocalizedRoute($routeName, $parameters = [], $language = null)
    {
        $language = $language ?: self::getCurrentLanguage();
        
        if ($language === self::DEFAULT_LANGUAGE) {
            return route($routeName, $parameters);
        }
        
        return route($routeName, array_merge($parameters, ['locale' => $language]));
    }
} 