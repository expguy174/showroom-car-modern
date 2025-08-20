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
        @if($currentType === 'car')
        <!-- Car panel -->
        <div class="filter-panel" data-panel="car">
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
                        @if(isset($fuelOptions))
                            @foreach($fuelOptions as $opt)
                                <option value="{{ $opt['value'] }}" {{ request('fuel_type')===$opt['value'] ? 'selected' : '' }}>{{ $opt['label'] }}</option>
                            @endforeach
                        @else
                            @foreach($fuelTypes as $f)
                                @php($k = strtolower(trim($f)))
                                @php($label = ($fuelVi[$k] ?? $f))
                                <option value="{{ $f }}" {{ request('fuel_type')===$f ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hộp số</label>
                    <select name="transmission" class="w-full border-gray-200 rounded-lg text-sm">
                        <option value="">Tất cả</option>
                        @if(isset($transOptions))
                            @foreach($transOptions as $opt)
                                <option value="{{ $opt['value'] }}" {{ request('transmission')===$opt['value'] ? 'selected' : '' }}>{{ $opt['label'] }}</option>
                            @endforeach
                        @else
                            @foreach($transmissions as $t)
                                @php($k = strtolower(trim($t)))
                                @php($label = ($transVi[$k] ?? $t))
                                <option value="{{ $t }}" {{ request('transmission')===$t ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kiểu dáng</label>
                    <select name="body_type" class="w-full border-gray-200 rounded-lg text-sm">
                        <option value="">Tất cả</option>
                        @if(isset($bodyOptions))
                            @foreach($bodyOptions as $opt)
                                <option value="{{ $opt['value'] }}" {{ request('body_type')===$opt['value'] ? 'selected' : '' }}>{{ $opt['label'] }}</option>
                            @endforeach
                        @else
                            @foreach($bodyTypes as $b)
                                @php($k = strtolower(trim($b)))
                                @php($label = ($bodyVi[$k] ?? $b))
                                <option value="{{ $b }}" {{ request('body_type')===$b ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        @endif
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
                    <button name="price_quick" value="0-500000000" class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">≤ 500tr</button>
                    <button name="price_quick" value="500000000-1000000000" class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">500tr - 1tỷ</button>
                    <button name="price_quick" value="1000000000-2000000000" class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">1 - 2 tỷ</button>
                    <button name="price_quick" value="2000000000-" class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">≥ 2 tỷ</button>
                </div>
            </div>
        </div>
        @elseif($currentType === 'accessory')
        <!-- Accessory panel -->
        <div class="filter-panel" data-panel="accessory">
            <div class="grid grid-cols-1 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Thương hiệu phụ kiện</label>
                    <select name="acc_brand" class="w-full border-gray-200 rounded-lg text-sm">
                        <option value="">Tất cả</option>
                        @foreach(($accBrands ?? []) as $ab)
                            <option value="{{ $ab }}" {{ request('acc_brand')===$ab ? 'selected' : '' }}>{{ $ab }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Danh mục phụ kiện</label>
                    <select name="acc_category" class="w-full border-gray-200 rounded-lg text-sm">
                        <option value="">Tất cả</option>
                        @foreach(($accessoryCategories ?? []) as $cat)
                            <option value="{{ $cat }}" {{ request('acc_category')===$cat ? 'selected' : '' }}>{{ $cat }}</option>
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
                    <button name="price_quick" value="0-500000000" class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">≤ 500tr</button>
                    <button name="price_quick" value="500000000-1000000000" class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">500tr - 1tỷ</button>
                    <button name="price_quick" value="1000000000-2000000000" class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">1 - 2 tỷ</button>
                    <button name="price_quick" value="2000000000-" class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">≥ 2 tỷ</button>
                </div>
            </div>
        </div>
        @else
        <!-- All panel (minimal) -->
        <div class="filter-panel" data-panel="all">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Khoảng giá (VNĐ)</label>
                <div class="grid grid-cols-2 gap-2">
                    <input type="number" inputmode="numeric" name="price_min" value="{{ request('price_min') }}" placeholder="Tối thiểu" class="border-gray-200 rounded-lg text-sm" />
                    <input type="number" inputmode="numeric" name="price_max" value="{{ request('price_max') }}" placeholder="Tối đa" class="border-gray-200 rounded-lg text-sm" />
                </div>
                <div class="mt-2 grid grid-cols-2 gap-2 text-xs">
                    <button name="price_quick" value="0-500000000" class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">≤ 500tr</button>
                    <button name="price_quick" value="500000000-1000000000" class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">500tr - 1tỷ</button>
                    <button name="price_quick" value="1000000000-2000000000" class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">1 - 2 tỷ</button>
                    <button name="price_quick" value="2000000000-" class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">≥ 2 tỷ</button>
                </div>
            </div>
        </div>
        @endif
    </div>

</form>



