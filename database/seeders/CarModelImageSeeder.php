<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarModel;
use App\Models\CarModelImage;

class CarModelImageSeeder extends Seeder
{
    public function run(): void
    {
        foreach (CarModel::all() as $model) {
            $images = [
                [
                    'car_model_id' => $model->id,
                    'image_url' => 'https://placehold.co/1200x800?text=' . urlencode($model->name),
                    'alt_text' => $model->name,
                    'title' => $model->name . ' - Gallery',
                    'description' => null,
                    'image_type' => 'gallery',
                    'is_main' => true,
                    'is_active' => true,
                    'sort_order' => 1,
                ],
                [
                    'car_model_id' => $model->id,
                    'image_url' => 'https://placehold.co/1200x800?text=' . urlencode($model->name . ' Exterior'),
                    'alt_text' => $model->name,
                    'title' => $model->name . ' - Exterior',
                    'description' => null,
                    'image_type' => 'gallery',
                    'is_main' => false,
                    'is_active' => true,
                    'sort_order' => 2,
                ],
                [
                    'car_model_id' => $model->id,
                    'image_url' => 'https://placehold.co/1200x800?text=' . urlencode($model->name . ' Interior'),
                    'alt_text' => $model->name,
                    'title' => $model->name . ' - Interior',
                    'description' => null,
                    'image_type' => 'gallery',
                    'is_main' => false,
                    'is_active' => true,
                    'sort_order' => 3,
                ],
                [
                    'car_model_id' => $model->id,
                    'image_url' => 'https://placehold.co/1200x800?text=' . urlencode($model->name . ' Rear'),
                    'alt_text' => $model->name,
                    'title' => $model->name . ' - Rear',
                    'description' => null,
                    'image_type' => 'gallery',
                    'is_main' => false,
                    'is_active' => true,
                    'sort_order' => 4,
                ],
                [
                    'car_model_id' => $model->id,
                    'image_url' => 'https://placehold.co/1200x800?text=' . urlencode($model->name . ' Side'),
                    'alt_text' => $model->name,
                    'title' => $model->name . ' - Side',
                    'description' => null,
                    'image_type' => 'gallery',
                    'is_main' => false,
                    'is_active' => true,
                    'sort_order' => 5,
                ],
                [
                    'car_model_id' => $model->id,
                    'image_url' => 'https://placehold.co/1200x800?text=' . urlencode($model->name . ' Detail'),
                    'alt_text' => $model->name,
                    'title' => $model->name . ' - Detail',
                    'description' => null,
                    'image_type' => 'gallery',
                    'is_main' => false,
                    'is_active' => true,
                    'sort_order' => 6,
                ],
            ];
            foreach ($images as $img) {
                CarModelImage::updateOrCreate([
                    'car_model_id' => $img['car_model_id'],
                    'image_url' => $img['image_url'],
                ], $img);
            }
        }
    }
}


