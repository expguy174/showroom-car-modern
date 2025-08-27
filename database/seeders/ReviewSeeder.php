<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CarVariant;
use App\Models\Accessory;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        if ($users->isEmpty()) return;

        $variantIds = CarVariant::pluck('id');
        $accessoryIds = Accessory::pluck('id');
        if ($variantIds->isEmpty() && $accessoryIds->isEmpty()) return;

        $titlesCar = ['Rất hài lòng','Ổn trong tầm giá','Đáng tiền','Êm ái, tiết kiệm','Thiết kế đẹp'];
        $titlesAcc = ['Hài lòng','Ổn áp','Đáng mua','Đúng mô tả','Chất lượng tốt'];
        $commentsCar = [
            'Vận hành ổn định, cách âm khá.',
            'Tiết kiệm nhiên liệu, phù hợp đi phố.',
            'Ghế ngồi thoải mái, treo êm.',
            'Công nghệ an toàn hữu ích.',
            'Ngoại hình hiện đại, giá hợp lý.',
        ];
        $commentsAcc = [
            'Phụ kiện đúng mô tả, lắp đặt dễ.',
            'Đóng gói chắc chắn, giao nhanh.',
            'Dùng ổn, bền bỉ theo thời gian.',
            'Tương thích tốt với xe của tôi.',
            'Giá hợp lý so với chất lượng.',
        ];

        $totalReviews = 300; // tổng số review mong muốn
        $variantReviewCount = (int) round($totalReviews * 0.6); // 60% cho xe
        $accessoryReviewCount = $totalReviews - $variantReviewCount; // 40% cho phụ kiện

        // Phân phối rating nghiêng về 4★
        $weightedRating = function (): int {
            $r = rand(1,100);
            if ($r <= 5) return 2;       // 5%
            if ($r <= 30) return 3;      // +25% = 30%
            if ($r <= 85) return 4;      // +55% = 85%
            return 5;                    // 15%
        };

        for ($i = 0; $i < $variantReviewCount; $i++) {
            if ($variantIds->isEmpty()) break;
            $author = $users->random();
            $variantId = $variantIds->random();
            Review::create([
                'user_id' => $author->id,
                'reviewable_type' => \App\Models\CarVariant::class,
                'reviewable_id' => $variantId,
                'rating' => $weightedRating(),
                'title' => $titlesCar[array_rand($titlesCar)],
                'comment' => $commentsCar[array_rand($commentsCar)],
                'is_approved' => rand(1,100) <= 70,
            ]);
        }

        for ($i = 0; $i < $accessoryReviewCount; $i++) {
            if ($accessoryIds->isEmpty()) break;
            $author = $users->random();
            $accId = $accessoryIds->random();
            Review::create([
                'user_id' => $author->id,
                'reviewable_type' => \App\Models\Accessory::class,
                'reviewable_id' => $accId,
                'rating' => $weightedRating(),
                'title' => $titlesAcc[array_rand($titlesAcc)],
                'comment' => $commentsAcc[array_rand($commentsAcc)],
                'is_approved' => rand(1,100) <= 70,
            ]);
        }
    }
}


