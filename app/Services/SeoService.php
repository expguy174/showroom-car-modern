<?php

namespace App\Services;

use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\CarVariant;
use App\Models\Blog;
use Illuminate\Support\Str;

class SeoService
{
    public static function generateMetaTags($type, $data = [])
    {
        switch ($type) {
            case 'home':
                return [
                    'title' => 'Showroom Car - Mua bán xe hơi uy tín',
                    'description' => 'Showroom Car chuyên cung cấp các loại xe hơi chất lượng cao từ các thương hiệu nổi tiếng. Dịch vụ mua bán xe trả góp, bảo hành chính hãng.',
                    'keywords' => 'xe hơi, showroom xe, mua xe, bán xe, xe trả góp, BMW, Mercedes, Audi, Toyota',
                    'og_title' => 'Showroom Car - Mua bán xe hơi uy tín',
                    'og_description' => 'Showroom Car chuyên cung cấp các loại xe hơi chất lượng cao từ các thương hiệu nổi tiếng.',
                    'og_image' => asset('images/logo.png'),
                    'canonical' => url('/'),
                ];

            case 'car_detail':
                $car = $data['car'] ?? null;
                if (!$car) return self::generateMetaTags('home');

                return [
                    'title' => $car->name . ' - ' . $car->carModel->name . ' | Showroom Car',
                    'description' => 'Khám phá ' . $car->name . ' ' . $car->carModel->name . ' với giá ' . number_format($car->price) . ' VNĐ.',
                    'keywords' => $car->name . ', ' . $car->carModel->name . ', xe hơi, showroom xe' . ($car->fuel_type ? (', ' . $car->fuel_type) : ''),
                    'og_title' => $car->name . ' ' . $car->carModel->name,
                    'og_description' => 'Khám phá ' . $car->name . ' ' . $car->carModel->name . ' với giá ' . number_format($car->price) . ' VNĐ.',
                    'og_image' => $car->images->first() ? asset('storage/' . $car->images->first()->image_path) : asset('images/logo.png'),
                    'canonical' => url('/cars/' . $car->id),
                ];

            case 'brand':
                $brand = $data['brand'] ?? null;
                if (!$brand) return self::generateMetaTags('home');

                return [
                    'title' => 'Xe ' . $brand->name . ' - Showroom Car',
                    'description' => 'Khám phá các dòng xe ' . $brand->name . ' tại Showroom Car. Đa dạng mẫu mã, giá cả hợp lý, dịch vụ hậu mãi tốt.',
                    'keywords' => 'xe ' . $brand->name . ', showroom ' . $brand->name . ', mua xe ' . $brand->name,
                    'og_title' => 'Xe ' . $brand->name . ' - Showroom Car',
                    'og_description' => 'Khám phá các dòng xe ' . $brand->name . ' tại Showroom Car.',
                    'og_image' => asset('images/brands/' . Str::slug($brand->name) . '.png'),
                    'canonical' => url('/car-brands/' . $brand->id),
                ];

            case 'blog':
                $blog = $data['blog'] ?? null;
                if (!$blog) return self::generateMetaTags('home');

                return [
                    'title' => $blog->title . ' - Showroom Car Blog',
                    'description' => Str::limit(strip_tags($blog->content), 160),
                    'keywords' => $blog->tags ?? 'xe hơi, tin tức xe, showroom car',
                    'og_title' => $blog->title,
                    'og_description' => Str::limit(strip_tags($blog->content), 160),
                    'og_image' => $blog->image ? asset('storage/' . $blog->image) : asset('images/logo.png'),
                    'canonical' => url('/blogs/' . $blog->id),
                ];

            default:
                return self::generateMetaTags('home');
        }
    }

    public static function generateSitemap()
    {
        $sitemap = [
            'home' => [
                'url' => url('/'),
                'priority' => '1.0',
                'changefreq' => 'daily'
            ],
            'cars' => [
                'url' => url('/cars'),
                'priority' => '0.9',
                'changefreq' => 'weekly'
            ],
            'brands' => [
                'url' => url('/car-brands'),
                'priority' => '0.8',
                'changefreq' => 'weekly'
            ],
            'blogs' => [
                'url' => url('/blogs'),
                'priority' => '0.7',
                'changefreq' => 'weekly'
            ],
            'contact' => [
                'url' => url('/contact'),
                'priority' => '0.6',
                'changefreq' => 'monthly'
            ]
        ];

        // Add car variants
        $carVariants = CarVariant::where('is_active', 1)->get();
        foreach ($carVariants as $variant) {
            $sitemap['car_' . $variant->id] = [
                'url' => url('/cars/' . $variant->id),
                'priority' => '0.8',
                'changefreq' => 'weekly'
            ];
        }

        // Add blogs
        $blogs = Blog::where('is_active', 1)->get();
        foreach ($blogs as $blog) {
            $sitemap['blog_' . $blog->id] = [
                'url' => url('/blogs/' . $blog->id),
                'priority' => '0.6',
                'changefreq' => 'monthly'
            ];
        }

        return $sitemap;
    }

    public static function generateStructuredData($type, $data = [])
    {
        switch ($type) {
            case 'organization':
                return [
                    '@context' => 'https://schema.org',
                    '@type' => 'Organization',
                    'name' => 'Showroom Car',
                    'url' => url('/'),
                    'logo' => asset('images/logo.png'),
                    'description' => 'Showroom Car chuyên cung cấp các loại xe hơi chất lượng cao',
                    'address' => [
                        '@type' => 'PostalAddress',
                        'streetAddress' => '123 Đường ABC',
                        'addressLocality' => 'Hà Nội',
                        'addressRegion' => 'Hà Nội',
                        'postalCode' => '100000',
                        'addressCountry' => 'VN'
                    ],
                    'contactPoint' => [
                        '@type' => 'ContactPoint',
                        'telephone' => '+84-123-456-789',
                        'contactType' => 'customer service'
                    ]
                ];

            case 'product':
                $car = $data['car'] ?? null;
                if (!$car) return null;

                return [
                    '@context' => 'https://schema.org',
                    '@type' => 'Product',
                    'name' => $car->name . ' ' . $car->carModel->name,
                    'description' => $car->description,
                    'brand' => [
                        '@type' => 'Brand',
                        'name' => $car->carModel->carBrand->name
                    ],
                    'offers' => [
                        '@type' => 'Offer',
                        'price' => $car->price,
                        'priceCurrency' => 'VND',
                        'availability' => 'https://schema.org/InStock',
                        'seller' => [
                            '@type' => 'Organization',
                            'name' => 'Showroom Car'
                        ]
                    ],
                    'image' => $car->images->first() ? asset('storage/' . $car->images->first()->image_path) : null
                ];

            case 'article':
                $blog = $data['blog'] ?? null;
                if (!$blog) return null;

                return [
                    '@context' => 'https://schema.org',
                    '@type' => 'Article',
                    'headline' => $blog->title,
                    'description' => Str::limit(strip_tags($blog->content), 200),
                    'image' => $blog->image ? asset('storage/' . $blog->image) : null,
                    'author' => [
                        '@type' => 'Organization',
                        'name' => 'Showroom Car'
                    ],
                    'publisher' => [
                        '@type' => 'Organization',
                        'name' => 'Showroom Car',
                        'logo' => [
                            '@type' => 'ImageObject',
                            'url' => asset('images/logo.png')
                        ]
                    ],
                    'datePublished' => $blog->created_at->toISOString(),
                    'dateModified' => $blog->updated_at->toISOString()
                ];

            default:
                return null;
        }
    }

    public static function optimizeUrl($title)
    {
        return Str::slug($title, '-');
    }

    public static function generateBreadcrumbs($type, $data = [])
    {
        $breadcrumbs = [
            [
                'name' => 'Trang chủ',
                'url' => url('/')
            ]
        ];

        switch ($type) {
            case 'car_detail':
                $car = $data['car'] ?? null;
                if ($car) {
                    $breadcrumbs[] = [
                        'name' => 'Xe ' . $car->carModel->carBrand->name,
                        'url' => url('/car-brands/' . $car->carModel->carBrand->id)
                    ];
                    $breadcrumbs[] = [
                        'name' => $car->carModel->name,
                        'url' => url('/models/' . $car->carModel->id)
                    ];
                    $breadcrumbs[] = [
                        'name' => $car->name,
                        'url' => url('/cars/' . $car->id)
                    ];
                }
                break;

            case 'brand':
                $brand = $data['brand'] ?? null;
                if ($brand) {
                    $breadcrumbs[] = [
                        'name' => 'Xe ' . $brand->name,
                        'url' => url('/car-brands/' . $brand->id)
                    ];
                }
                break;

            case 'blog':
                $blog = $data['blog'] ?? null;
                if ($blog) {
                    $breadcrumbs[] = [
                        'name' => 'Tin tức',
                        'url' => url('/blogs')
                    ];
                    $breadcrumbs[] = [
                        'name' => $blog->title,
                        'url' => url('/blogs/' . $blog->id)
                    ];
                }
                break;
        }

        return $breadcrumbs;
    }
} 