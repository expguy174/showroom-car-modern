@php
    // Expected variables from controller: $brands, $models, $fuelTypes, $transmissions, $bodyTypes
    // Mapping labels to Vietnamese (support both English and Vietnamese inputs)
    $fuelVi = [
        'gasoline' => 'Xăng', 'petrol' => 'Xăng', 'xang' => 'Xăng', 'xăng' => 'Xăng',
        'diesel' => 'Dầu', 'dau' => 'Dầu', 'dầu' => 'Dầu',
        'hybrid' => 'Hybrid', 'plug-in_hybrid' => 'Hybrid sạc ngoài', 'phev' => 'Hybrid sạc ngoài',
        'electric' => 'Điện', 'ev' => 'Điện', 'dien' => 'Điện', 'điện' => 'Điện',
        'hydrogen' => 'Hydro', 'lpg' => 'Gas', 'cng' => 'CNG', 'ethanol' => 'Ethanol'
    ];
    $transVi = [
        'manual' => 'Số sàn', 'so san' => 'Số sàn', 'số sàn' => 'Số sàn',
        'automatic' => 'Số tự động', 'auto' => 'Số tự động', 'so tu dong' => 'Số tự động', 'số tự động' => 'Số tự động',
        'cvt' => 'CVT', 'dct' => 'DCT', 'amt' => 'AMT', 'semi-automatic' => 'Bán tự động', 'sequential' => 'Tuần tự',
        'don toc do' => 'Đơn tốc độ', 'đơn tốc độ' => 'Đơn tốc độ'
    ];
    $bodyVi = [
        'sedan' => 'Sedan', 'suv' => 'SUV', 'hatchback' => 'Hatchback', 'minivan' => 'Minivan', 'mpv' => 'MPV',
        'pickup' => 'Bán tải', 'truck' => 'Bán tải', 'coupe' => 'Coupe', 'convertible' => 'Mui trần',
        'wagon' => 'Wagon', 'crossover' => 'Crossover'
    ];

    // Accessory category VI map (extend as needed)
    $accCatVi = [
        'interior' => 'Nội thất', 'exterior' => 'Ngoại thất', 'electronics' => 'Điện tử',
        'audio' => 'Âm thanh', 'lighting' => 'Đèn chiếu sáng', 'safety' => 'An toàn',
        'maintenance' => 'Bảo dưỡng', 'care' => 'Chăm sóc xe', 'car_care' => 'Chăm sóc xe', 'car care' => 'Chăm sóc xe',
        'wheels' => 'Mâm/Lốp', 'performance' => 'Hiệu năng', 'camera' => 'Camera', 'dashcam' => 'Camera hành trình',
        'navigation' => 'Dẫn đường', 'cover' => 'Bạt phủ', 'charger' => 'Sạc', 'utility' => 'Tiện ích',
        'phone mount' => 'Giá đỡ điện thoại', 'floor mat' => 'Thảm sàn', 'seat cover' => 'Áo ghế',
    ];

    // Precompute option lists once to avoid scope issues
    $fuelList = [];
    if (isset($fuelOptions) && is_array($fuelOptions)) {
        foreach ($fuelOptions as $opt) {
            $val = is_array($opt) ? ($opt['value'] ?? '') : $opt;
            $lab = is_array($opt) ? ($opt['label'] ?? $val) : $val;
            if ($val !== '') $fuelList[] = ['value' => $val, 'label' => $lab];
        }
    } else {
        $seen = [];
        foreach ((array)($fuelTypes ?? []) as $fRaw) {
            $rawVal = is_array($fRaw) ? ($fRaw['value'] ?? reset($fRaw) ?? '') : $fRaw;
            $rawVal = is_scalar($rawVal) ? (string)$rawVal : '';
            if ($rawVal === '') continue;
            $k = strtolower(trim($rawVal));
            $label = $fuelVi[$k] ?? $rawVal;
            if (!isset($seen[$k])) { $seen[$k] = true; $fuelList[] = ['value' => $rawVal, 'label' => $label]; }
        }
    }

    $transList = [];
    if (isset($transOptions) && is_array($transOptions)) {
        foreach ($transOptions as $opt) {
            $val = is_array($opt) ? ($opt['value'] ?? '') : $opt;
            $lab = is_array($opt) ? ($opt['label'] ?? $val) : $val;
            if ($val !== '') $transList[] = ['value' => $val, 'label' => $lab];
        }
    } else {
        $seenT = [];
        foreach ((array)($transmissions ?? []) as $tRaw) {
            $rawVal = is_array($tRaw) ? ($tRaw['value'] ?? reset($tRaw) ?? '') : $tRaw;
            $rawVal = is_scalar($rawVal) ? (string)$rawVal : '';
            if ($rawVal === '') continue;
            $k = strtolower(trim($rawVal));
            $label = $transVi[$k] ?? $rawVal;
            if (!isset($seenT[$k])) { $seenT[$k] = true; $transList[] = ['value' => $rawVal, 'label' => $label]; }
        }
    }

    $bodyList = [];
    if (isset($bodyOptions) && is_array($bodyOptions)) {
        foreach ($bodyOptions as $opt) {
            $val = is_array($opt) ? ($opt['value'] ?? '') : $opt;
            $lab = is_array($opt) ? ($opt['label'] ?? $val) : $val;
            if ($val !== '') $bodyList[] = ['value' => $val, 'label' => $lab];
        }
    } else {
        $seenB = [];
        foreach ((array)($bodyTypes ?? []) as $bRaw) {
            $rawVal = is_array($bRaw) ? ($bRaw['value'] ?? reset($bRaw) ?? '') : $bRaw;
            $rawVal = is_scalar($rawVal) ? (string)$rawVal : '';
            if ($rawVal === '') continue;
            $k = strtolower(trim($rawVal));
            $label = $bodyVi[$k] ?? $rawVal;
            if (!isset($seenB[$k])) { $seenB[$k] = true; $bodyList[] = ['value' => $rawVal, 'label' => $label]; }
        }
    }
@endphp

<form id="{{ $formId ?? 'filter-form' }}" method="GET" action="{{ route($routeName ?? 'products.index') }}" class="inventory-filter-form space-y-5">
    @if(request('debug') === 'options')
    <details class="mb-3 text-xs text-gray-600 bg-gray-50 border border-gray-200 rounded p-2">
        <summary class="cursor-pointer font-semibold">Debug filter options</summary>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 mt-2">
            <div>
                <div class="font-medium">Fuel options</div>
                <pre class="whitespace-pre-wrap">@json($fuelOptions ?? [])</pre>
            </div>
            <div>
                <div class="font-medium">Transmission options</div>
                <pre class="whitespace-pre-wrap">@json($transOptions ?? [])</pre>
            </div>
            <div>
                <div class="font-medium">Body options</div>
                <pre class="whitespace-pre-wrap">@json($bodyOptions ?? [])</pre>
            </div>
        </div>
    </details>
    @endif
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Loại</label>
        <div class="inline-flex items-center gap-1 p-1 rounded-lg bg-gray-100" role="tablist" aria-label="Bộ lọc loại">
            @php($currentType = request('type','all'))
            <button type="button" class="js-type-tab px-3 py-1.5 rounded-md text-sm {{ $currentType==='all' ? 'bg-white text-gray-900 shadow' : 'text-gray-600 hover:text-gray-900' }}" data-value="all" role="tab" aria-selected="{{ $currentType==='all' ? 'true' : 'false' }}">Tất cả</button>
            <button type="button" class="js-type-tab px-3 py-1.5 rounded-md text-sm {{ $currentType==='car' ? 'bg-white text-gray-900 shadow' : 'text-gray-600 hover:text-gray-900' }}" data-value="car" role="tab" aria-selected="{{ $currentType==='car' ? 'true' : 'false' }}">Xe hơi</button>
            <button type="button" class="js-type-tab px-3 py-1.5 rounded-md text-sm {{ $currentType==='accessory' ? 'bg-white text-gray-900 shadow' : 'text-gray-600 hover:text-gray-900' }}" data-value="accessory" role="tab" aria-selected="{{ $currentType==='accessory' ? 'true' : 'false' }}">Phụ kiện</button>
        </div>
        <div class="js-filter-loading filter-loading hidden"><div class="filter-loading-bar"></div></div>
        <input type="hidden" name="type" value="{{ $currentType }}">
    </div>

    <div class="space-y-5">
        @php($currentType = request('type','all'))
        <!-- Car panel -->
        <div class="filter-panel {{ $currentType==='car' ? '' : 'hidden' }}" data-panel="car">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hãng xe</label>
                <select name="brand" class="w-full border-gray-200 rounded-lg text-sm">
                    <option value="">Tất cả</option>
                    @foreach($brands as $b)
                        <option value="{{ $b->id }}" {{ (string)request('brand') === (string)$b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dòng xe</label>
                <select name="model" class="w-full border-gray-200 rounded-lg text-sm">
                    <option value="">Tất cả</option>
                    @foreach($models as $m)
                        <option value="{{ $m->id }}" {{ (string)request('model') === (string)$m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nhiên liệu</label>
                    <select name="fuel_type" class="w-full border-gray-200 rounded-lg text-sm">
                        <option value="">Tất cả</option>
                        @foreach($fuelList as $opt)
                            <option value="{{ $opt['value'] }}" {{ request('fuel_type')===$opt['value'] ? 'selected' : '' }}>{{ $opt['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hộp số</label>
                    <select name="transmission" class="w-full border-gray-200 rounded-lg text-sm">
                        <option value="">Tất cả</option>
                        @foreach($transList as $opt)
                            <option value="{{ $opt['value'] }}" {{ request('transmission')===$opt['value'] ? 'selected' : '' }}>{{ $opt['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kiểu dáng</label>
                    <select name="body_type" class="w-full border-gray-200 rounded-lg text-sm">
                        <option value="">Tất cả</option>
                        @foreach($bodyList as $opt)
                            <option value="{{ $opt['value'] }}" {{ request('body_type')===$opt['value'] ? 'selected' : '' }}>{{ $opt['label'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Khoảng giá (VNĐ)</label>
                <div class="grid grid-cols-2 gap-2">
                    <input type="number" inputmode="numeric" name="price_min" value="{{ request('price_min') }}" placeholder="Tối thiểu" class="border-gray-200 rounded-lg text-sm" />
                    <input type="number" inputmode="numeric" name="price_max" value="{{ request('price_max') }}" placeholder="Tối đa" class="border-gray-200 rounded-lg text-sm" />
                </div>
                <div class="mt-2 grid grid-cols-2 gap-2 text-xs">
                    <button type="button" name="price_quick" value="0-500000000" class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">≤ 500tr</button>
                    <button type="button" name="price_quick" value="500000000-1000000000" class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">500tr - 1tỷ</button>
                    <button type="button" name="price_quick" value="1000000000-2000000000" class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">1 - 2 tỷ</button>
                    <button type="button" name="price_quick" value="2000000000-" class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">≥ 2 tỷ</button>
                </div>
            </div>
        </div>
        <!-- Accessory panel -->
        <div class="filter-panel {{ $currentType==='accessory' ? '' : 'hidden' }}" data-panel="accessory">
            <div class="grid grid-cols-1 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Danh mục phụ kiện</label>
                    <select name="acc_category" class="w-full border-gray-200 rounded-lg text-sm">
                        <option value="">Tất cả</option>
                        @foreach(($accessoryCategories ?? []) as $cat)
                            @php($catKey = is_scalar($cat) ? strtolower(trim((string)$cat)) : '')
                            @php($catLabel = $accCatVi[$catKey] ?? (string)$cat)
                            <option value="{{ $cat }}" {{ request('acc_category')===$cat ? 'selected' : '' }}>{{ $catLabel }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tồn kho</label>
                    <select name="stock_status" class="w-full border-gray-200 rounded-lg text-sm">
                        <option value="">Tất cả</option>
                        <option value="in_stock" {{ request('stock_status')==='in_stock' ? 'selected' : '' }}>Còn hàng</option>
                        <option value="low_stock" {{ request('stock_status')==='low_stock' ? 'selected' : '' }}>Sắp hết</option>
                        <option value="out_of_stock" {{ request('stock_status')==='out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                        <option value="discontinued" {{ request('stock_status')==='discontinued' ? 'selected' : '' }}>Ngừng bán</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Khoảng giá (VNĐ)</label>
                <div class="grid grid-cols-2 gap-2">
                    <input type="number" inputmode="numeric" name="price_min" value="{{ request('price_min') }}" placeholder="Tối thiểu" class="border-gray-200 rounded-lg text-sm" />
                    <input type="number" inputmode="numeric" name="price_max" value="{{ request('price_max') }}" placeholder="Tối đa" class="border-gray-200 rounded-lg text-sm" />
                </div>
                <div class="mt-2 grid grid-cols-2 gap-2 text-xs">
                    <button type="button" name="price_quick" value="0-500000000" class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">≤ 500tr</button>
                    <button type="button" name="price_quick" value="500000000-1000000000" class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">500tr - 1tỷ</button>
                    <button type="button" name="price_quick" value="1000000000-2000000000" class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">1 - 2 tỷ</button>
                    <button type="button" name="price_quick" value="2000000000-" class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">≥ 2 tỷ</button>
                </div>
            </div>
        </div>
        <!-- All panel (minimal) -->
        <div class="filter-panel {{ $currentType==='all' ? '' : 'hidden' }}" data-panel="all">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Khoảng giá (VNĐ)</label>
                <div class="grid grid-cols-2 gap-2">
                    <input type="number" inputmode="numeric" name="price_min" value="{{ request('price_min') }}" placeholder="Tối thiểu" class="border-gray-200 rounded-lg text-sm" />
                    <input type="number" inputmode="numeric" name="price_max" value="{{ request('price_max') }}" placeholder="Tối đa" class="border-gray-200 rounded-lg text-sm" />
                </div>
                <div class="mt-2 grid grid-cols-2 gap-2 text-xs">
                    <button type="button" name="price_quick" value="0-500000000" class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">≤ 500tr</button>
                    <button type="button" name="price_quick" value="500000000-1000000000" class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">500tr - 1tỷ</button>
                    <button type="button" name="price_quick" value="1000000000-2000000000" class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">1 - 2 tỷ</button>
                    <button type="button" name="price_quick" value="2000000000-" class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">≥ 2 tỷ</button>
                </div>
            </div>
        </div>
    </div>

</form>



