<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CarBrand;

class BrandStatsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:brands {--active : Chỉ tính các model/variant active}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'In thống kê số dòng xe (models) và phiên bản (variants) theo từng hãng';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $activeOnly = (bool) $this->option('active');

        $brands = CarBrand::with(['carModels.variants'])->orderBy('name')->get();

        $rows = [];
        foreach ($brands as $brand) {
            $models = $brand->carModels;
            if ($activeOnly) {
                $models = $models->where('is_active', true)->values();
            }
            $modelCount = $models->count();
            $variantCount = $models->sum(function ($m) use ($activeOnly) {
                $v = $m->variants;
                return $activeOnly ? $v->where('is_active', true)->count() : $v->count();
            });
            $rows[] = [
                'brand' => $brand->name,
                'models' => $modelCount,
                'variants' => $variantCount,
            ];
        }

        $this->table(['Hãng', 'Số dòng xe', 'Số phiên bản'], $rows);

        return Command::SUCCESS;
    }
}


