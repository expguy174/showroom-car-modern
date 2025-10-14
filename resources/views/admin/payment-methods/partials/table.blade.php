<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 35%;">Phương thức</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 15%;">Loại</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 20%;">Phí</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 15%;">Trạng thái</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 15%;">Thao tác</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($paymentMethods as $method)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            @if($method->logo)
                                <img class="h-10 w-10 rounded-lg object-cover" src="{{ $method->logo }}" alt="{{ $method->name }}">
                            @else
                                <div class="h-10 w-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-credit-card text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">{{ $method->name }}</div>
                            @if($method->provider)
                            <div class="text-xs text-gray-500">{{ $method->provider }}</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst($method->type) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($method->transaction_fee > 0)
                        <div class="text-sm text-gray-900">{{ number_format($method->transaction_fee, 2) }}%</div>
                    @else
                        <span class="text-sm text-green-600">Miễn phí</span>
                    @endif
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
                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-credit-card text-4xl mb-2"></i>
                    <p>Chưa có phương thức thanh toán nào</p>
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
