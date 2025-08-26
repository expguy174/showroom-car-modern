<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use App\Models\CarVariant;
use App\Models\Accessory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset reviews to avoid duplicates on re-seeding (keep schema integrity)
        DB::table('reviews')->delete();

        $users = User::all();
        $carVariants = CarVariant::where('is_active', 1)->get();
        $accessories = Accessory::where('is_active', 1)->get();

        $reviews = [
            // Toyota Vios G Reviews
            [
                'user_id' => $users->where('email', 'customer1@example.com')->first()->id,
                'reviewable_type' => CarVariant::class,
                'reviewable_id' => $carVariants->where('name', 'Vios G')->first()->id,
                'rating' => 5,
                'title' => 'Xe gia đình hoàn hảo',
                'comment' => 'Toyota Vios G là lựa chọn tuyệt vời cho gia đình. Xe tiết kiệm nhiên liệu, dễ lái và có không gian rộng rãi. Nội thất chất lượng tốt và có nhiều tính năng an toàn.',
                'is_approved' => true,
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(28)
            ],
            [
                'user_id' => $users->where('email', 'customer2@example.com')->first()->id,
                'reviewable_type' => CarVariant::class,
                'reviewable_id' => $carVariants->where('name', 'Vios G')->first()->id,
                'rating' => 4,
                'title' => 'Xe tốt, giá hợp lý',
                'comment' => 'Xe chạy ổn định, tiết kiệm nhiên liệu. Tuy nhiên công suất hơi yếu khi leo dốc. Nhìn chung là xe gia đình tốt.',
                'is_approved' => true,
                'created_at' => now()->subDays(25),
                'updated_at' => now()->subDays(24)
            ],
            // Honda City G Reviews
            [
                'user_id' => $users->where('email', 'vip@example.com')->first()->id,
                'reviewable_type' => CarVariant::class,
                'reviewable_id' => $carVariants->where('name', 'City G')->first()->id,
                'rating' => 5,
                'title' => 'Xe đẹp, hiện đại',
                'comment' => 'Honda City G có thiết kế đẹp và hiện đại. Nội thất sang trọng, nhiều tính năng công nghệ. Động cơ mạnh mẽ và tiết kiệm nhiên liệu.',
                'is_approved' => true,
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(19)
            ],
            [
                'user_id' => $users->where('email', 'customer3@example.com')->first()->id,
                'reviewable_type' => CarVariant::class,
                'reviewable_id' => $carVariants->where('name', 'City G')->first()->id,
                'rating' => 4,
                'title' => 'Xe tốt cho người mới lái',
                'comment' => 'Xe dễ lái, phù hợp cho người mới học lái. Khoang lái thoải mái, tầm nhìn tốt. Giá hơi cao so với đối thủ.',
                'is_approved' => true,
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(14)
            ],
            // Ford Ranger XLT Reviews
            [
                'user_id' => $users->where('email', 'customer1@example.com')->first()->id,
                'reviewable_type' => CarVariant::class,
                'reviewable_id' => $carVariants->where('name', 'Ranger XLT')->first()->id,
                'rating' => 5,
                'title' => 'Xe bán tải mạnh mẽ',
                'comment' => 'Ford Ranger XLT có động cơ mạnh mẽ, phù hợp cho việc chở hàng và đi phượt. Thiết kế nam tính, cabin rộng rãi.',
                'is_approved' => true,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(9)
            ],
            [
                'user_id' => $users->where('email', 'vip@example.com')->first()->id,
                'reviewable_type' => CarVariant::class,
                'reviewable_id' => $carVariants->where('name', 'Ranger XLT')->first()->id,
                'rating' => 4,
                'title' => 'Xe tốt cho công việc',
                'comment' => 'Sử dụng xe cho công việc kinh doanh. Xe chở được nhiều hàng, tiết kiệm nhiên liệu. Tuy nhiên giá hơi cao.',
                'is_approved' => true,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(4)
            ]
        ];

        foreach ($reviews as $review) {
            Review::create($review);
        }

        // ---- Generate more variant reviews dynamically ----
        if ($users->count() > 0 && $carVariants->count() > 0) {
            $sampleComments = [
                'Chạy đầm và êm, cách âm tốt. Rất hài lòng!',
                'Thiết kế đẹp, tiết kiệm nhiên liệu.',
                'Giá hợp lý trong phân khúc, nhiều công nghệ.',
                'Vô lăng nhẹ, dễ lái trong phố.',
                'Nội thất rộng rãi, ghế ngồi thoải mái.',
                'Hệ thống an toàn đầy đủ, yên tâm khi sử dụng.',
            ];

            $carVariants->take(20)->each(function (CarVariant $variant) use ($users, $sampleComments) {
                $numReviews = rand(2, 5);
                $picked = $users->random(min($users->count(), $numReviews));
                $reviewUsers = $picked instanceof \Illuminate\Support\Collection ? $picked : collect([$picked]);

                foreach ($reviewUsers as $user) {
                    Review::create([
                        'user_id' => $user->id,
                        'reviewable_type' => CarVariant::class,
                        'reviewable_id' => $variant->id,
                        'rating' => rand(4, 5),
                        'title' => 'Đánh giá ' . $variant->name,
                        'comment' => $sampleComments[array_rand($sampleComments)],
                        'is_approved' => true,
                        'created_at' => now()->subDays(rand(3, 60)),
                        'updated_at' => now(),
                    ]);
                }

                // Update aggregated rating on variant
                $approved = $variant->approvedReviews()->get();
                $variant->rating_count = $approved->count();
                $variant->average_rating = $approved->avg('rating') ?: 0;
                $variant->save();
            });
        }

        // ---- Generate accessory reviews ----
        if ($users->count() > 0 && $accessories->count() > 0) {
            $accessories->take(15)->each(function (Accessory $acc) use ($users) {
                $numReviews = rand(1, 3);
                $picked = $users->random(min($users->count(), $numReviews));
                $reviewUsers = $picked instanceof \Illuminate\Support\Collection ? $picked : collect([$picked]);

                foreach ($reviewUsers as $user) {
                    Review::create([
                        'user_id' => $user->id,
                        'reviewable_type' => Accessory::class,
                        'reviewable_id' => $acc->id,
                        'rating' => rand(4, 5),
                        'title' => 'Phụ kiện chất lượng',
                        'comment' => 'Hài lòng với chất lượng và độ hoàn thiện. Đáng tiền!',
                        'is_approved' => true,
                        'created_at' => now()->subDays(rand(2, 40)),
                        'updated_at' => now(),
                    ]);
                }

                $approved = $acc->approvedReviews()->get();
                $acc->rating_count = $approved->count();
                $acc->average_rating = $approved->avg('rating') ?: 0;
                $acc->save();
            });
        }
    }
}
