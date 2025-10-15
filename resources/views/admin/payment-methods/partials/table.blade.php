<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 15%;">Phương thức</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 10%;">Loại</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 10%;">Phí giao dịch</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 20%;">Cấu hình</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 20%;">Ghi chú</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 10%;">Trạng thái</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 15%;">Thao tác</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($paymentMethods as $method)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div>
                        <div class="text-sm font-medium text-gray-900">{{ $method->name }}</div>
                        <div class="flex items-center space-x-2 mt-1">
                            @if($method->code)
                            <span class="text-xs text-gray-500 font-mono bg-gray-100 px-2 py-0.5 rounded">{{ $method->code }}</span>
                            @endif
                            @if($method->provider)
                            <span class="text-xs text-gray-500">{{ $method->provider }}</span>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                        {{ $method->type === 'online' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $method->type === 'online' ? 'Trực tuyến' : 'Trực tiếp' }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm">
                        @if($method->fee_flat > 0 && $method->fee_percent > 0)
                            <div class="text-gray-900">{{ number_format($method->fee_flat, 0, ',', '.') }}đ + {{ number_format($method->fee_percent, 2) }}%</div>
                        @elseif($method->fee_flat > 0)
                            <div class="text-gray-900">{{ number_format($method->fee_flat, 0, ',', '.') }}đ</div>
                        @elseif($method->fee_percent > 0)
                            <div class="text-gray-900">{{ number_format($method->fee_percent, 2) }}%</div>
                        @else
                            <span class="text-green-600">Miễn phí</span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm">
                        @if($method->config)
                            @php
                                $config = is_string($method->config) ? json_decode($method->config, true) : $method->config;
                            @endphp
                            @if($config && is_array($config))
                                <div class="flex flex-wrap gap-1">
                                    @foreach($config as $key => $value)
                                        @if(is_string($value) || is_numeric($value))
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-800">
                                                {{ $key }}: {{ $value }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <span class="text-xs text-gray-400 italic">Chưa cấu hình</span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm">
                        @if($method->notes)
                            <p class="text-xs text-gray-700">{{ Str::limit($method->notes, 80) }}</p>
                        @else
                            <span class="text-xs text-gray-400 italic">Chưa có ghi chú</span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <x-admin.status-toggle 
                        :item-id="$method->id"
                        :current-status="$method->is_active"
                        entity-type="payment" />
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <x-admin.table-actions 
                        :item="$method"
                        edit-route="admin.payment-methods.edit"
                        delete-route="admin.payment-methods.destroy"
                        :has-toggle="true" />
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <i class="fas fa-credit-card text-gray-400 text-5xl mb-4"></i>
                        <p class="text-gray-500 text-lg font-medium">Không có phương thức thanh toán nào</p>
                        <p class="text-gray-400 text-sm mt-1">Thử thay đổi bộ lọc để xem kết quả khác</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    {{-- Pagination --}}
    @if($paymentMethods->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        <x-admin.pagination :paginator="$paymentMethods" />
    </div>
    @endif
</div>
