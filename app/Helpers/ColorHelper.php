<?php

namespace App\Helpers;

use App\Models\CarVariantColor;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class ColorHelper
{
    public static function getColorHex($colorName)
    {
        $colorMap = [
            // Cơ bản - chữ thường
            'trắng' => '#FFFFFF',
            'trang' => '#FFFFFF',
            'white' => '#FFFFFF',
            'đen' => '#000000',
            'den' => '#000000',
            'black' => '#000000',
            'xám' => '#808080',
            'xam' => '#808080',
            'gray' => '#808080',
            'bạc' => '#C0C0C0',
            'bac' => '#C0C0C0',
            'silver' => '#C0C0C0',
            'đỏ' => '#FF0000',
            'do' => '#FF0000',
            'red' => '#FF0000',
            'xanh dương' => '#0000FF',
            'xanh duong' => '#0000FF',
            'blue' => '#0000FF',
            'xanh lá' => '#00FF00',
            'xanh la' => '#00FF00',
            'green' => '#00FF00',
            'vàng' => '#FFFF00',
            'vang' => '#FFFF00',
            'yellow' => '#FFFF00',
            'cam' => '#FFA500',
            'orange' => '#FFA500',
            'tím' => '#800080',
            'tim' => '#800080',
            'purple' => '#800080',
            'hồng' => '#FFC0CB',
            'hong' => '#FFC0CB',
            'pink' => '#FFC0CB',
            'nâu' => '#A52A2A',
            'nau' => '#A52A2A',
            'brown' => '#A52A2A',
            
            // Chữ hoa đầu
            'Trắng' => '#FFFFFF',
            'Trang' => '#FFFFFF',
            'Đen' => '#000000',
            'Den' => '#000000',
            'Xám' => '#808080',
            'Xam' => '#808080',
            'Bạc' => '#C0C0C0',
            'Bac' => '#C0C0C0',
            'Đỏ' => '#FF0000',
            'Do' => '#FF0000',
            'Xanh dương' => '#0000FF',
            'Xanh duong' => '#0000FF',
            'Xanh lá' => '#00FF00',
            'Xanh la' => '#00FF00',
            'Vàng' => '#FFFF00',
            'Vang' => '#FFFF00',
            'Cam' => '#FFA500',
            'Tím' => '#800080',
            'Tim' => '#800080',
            'Hồng' => '#FFC0CB',
            'Hong' => '#FFC0CB',
            'Nâu' => '#A52A2A',
            'Nau' => '#A52A2A',
            
            // Biến thể
            'xanh navy' => '#000080',
            'xanh navy' => '#000080',
            'navy' => '#000080',
            'xanh ngọc' => '#00FFFF',
            'xanh ngoc' => '#00FFFF',
            'cyan' => '#00FFFF',
            'xanh lá cây' => '#008000',
            'xanh la cay' => '#008000',
            'xanh dương đậm' => '#00008B',
            'xanh duong dam' => '#00008B',
            'dark blue' => '#00008B',
            'xanh dương nhạt' => '#87CEEB',
            'xanh duong nhat' => '#87CEEB',
            'light blue' => '#87CEEB',
            'xanh lá nhạt' => '#90EE90',
            'xanh la nhat' => '#90EE90',
            'light green' => '#90EE90',
            'đỏ đậm' => '#8B0000',
            'do dam' => '#8B0000',
            'dark red' => '#8B0000',
            'đỏ nhạt' => '#FFB6C1',
            'do nhat' => '#FFB6C1',
            'light red' => '#FFB6C1',
            'vàng đậm' => '#FFD700',
            'vang dam' => '#FFD700',
            'gold' => '#FFD700',
            'vàng nhạt' => '#FFFFE0',
            'vang nhat' => '#FFFFE0',
            'light yellow' => '#FFFFE0',
            'cam đậm' => '#FF8C00',
            'cam dam' => '#FF8C00',
            'dark orange' => '#FF8C00',
            'cam nhạt' => '#FFE4B5',
            'cam nhat' => '#FFE4B5',
            'light orange' => '#FFE4B5',
            'tím đậm' => '#4B0082',
            'tim dam' => '#4B0082',
            'dark purple' => '#4B0082',
            'tím nhạt' => '#DDA0DD',
            'tim nhat' => '#DDA0DD',
            'light purple' => '#DDA0DD',
            'hồng đậm' => '#FF1493',
            'hong dam' => '#FF1493',
            'deep pink' => '#FF1493',
            'hồng nhạt' => '#FFE4E1',
            'hong nhat' => '#FFE4E1',
            'light pink' => '#FFE4E1',
            'nâu đậm' => '#654321',
            'nau dam' => '#654321',
            'dark brown' => '#654321',
            'nâu nhạt' => '#DEB887',
            'nau nhat' => '#DEB887',
            'light brown' => '#DEB887',
            'xám đậm' => '#696969',
            'xam dam' => '#696969',
            'dark gray' => '#696969',
            'xám nhạt' => '#D3D3D3',
            'xam nhat' => '#D3D3D3',
            'light gray' => '#D3D3D3',
            'bạc đậm' => '#A9A9A9',
            'bac dam' => '#A9A9A9',
            'dark silver' => '#A9A9A9',
            'bạc nhạt' => '#F5F5F5',
            'bac nhat' => '#F5F5F5',
            'light silver' => '#F5F5F5',

            // Custom showroom mappings
            'midnight black' => '#0B0B0D', // đen đêm
            'pearl white' => '#F8F8FF',   // trắng ngọc trai (gần ghostwhite)
            'racing red' => '#C00000',    // đỏ đua (đỏ đậm hơn)
            'midnight-black' => '#0B0B0D',
            'pearl-white' => '#F8F8FF',
            'racing-red' => '#C00000'
        ];
        
        // Normalize input
        $normalizedName = strtolower(trim((string) $colorName));
        $normalizedName = preg_replace('/\s+/', ' ', str_replace(['_', '-'], ' ', $normalizedName));

        // 1) Try static map first
        if (isset($colorMap[$normalizedName])) {
            return $colorMap[$normalizedName];
        }

        // 2) Try DB-backed lookup (seeded colors)
        try {
            // Guard when running without table (migrations not run yet)
            if (!Schema::hasTable('car_variant_colors')) {
                return '#CCCCCC';
            }

            $cacheKey = 'color_hex_lookup_' . md5($normalizedName);
            return Cache::remember($cacheKey, now()->addHours(12), function () use ($normalizedName) {
                // Case-insensitive exact match on color_name
                $hex = CarVariantColor::whereRaw('LOWER(color_name) = ?', [$normalizedName])
                    ->whereNotNull('hex_code')
                    ->value('hex_code');

                if ($hex) {
                    return strtoupper($hex);
                }

                // Loose match: contains words in order (fallback)
                $like = '%' . str_replace(' ', '%', $normalizedName) . '%';
                $hex = CarVariantColor::where('color_name', 'LIKE', $like)
                    ->whereNotNull('hex_code')
                    ->value('hex_code');

                return $hex ? strtoupper($hex) : '#CCCCCC';
            });
        } catch (\Throwable $e) {
            // Fallback to default gray on any error
            return '#CCCCCC';
        }
    }
} 