<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 16%;">Ngân hàng / Gói vay</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 10%;">Lãi suất</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 12%;">Phí xử lý</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 13%;">Trả trước TT</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 13%;">Kỳ hạn</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 14%;">Hạn mức vay</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 12%;">Trạng thái</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 10%;">Thao tác</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($financeOptions as $option)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">{{ $option->bank_name }}</div>
                    <div class="text-xs text-gray-500">{{ $option->name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-semibold text-blue-600">{{ number_format($option->interest_rate, 2) }}%</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($option->processing_fee > 0)
                        <div class="text-sm text-gray-900">{{ number_format($option->processing_fee, 0, ',', '.') }}đ</div>
                    @else
                        <span class="text-sm text-green-600">Miễn phí</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">Tối thiểu {{ number_format($option->min_down_payment, 0) }}%</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ $option->min_tenure }} - {{ $option->max_tenure }} tháng</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">
                        @php
                            $minAmount = $option->min_loan_amount / 1000000; // triệu
                            $maxAmount = $option->max_loan_amount / 1000000; // triệu
                            
                            // Format min amount
                            if ($minAmount >= 1000) {
                                $minInBillion = $minAmount / 1000;
                                $minDisplay = ($minInBillion == floor($minInBillion)) 
                                    ? number_format($minInBillion, 0) . ' tỷ'
                                    : number_format($minInBillion, 1) . ' tỷ';
                            } else {
                                $minDisplay = number_format($minAmount, 0) . 'tr';
                            }
                            
                            // Format max amount
                            if ($maxAmount >= 1000) {
                                $maxInBillion = $maxAmount / 1000;
                                $maxDisplay = ($maxInBillion == floor($maxInBillion)) 
                                    ? number_format($maxInBillion, 0) . ' tỷ'
                                    : number_format($maxInBillion, 1) . ' tỷ';
                            } else {
                                $maxDisplay = number_format($maxAmount, 0) . 'tr';
                            }
                        @endphp
                        {{ $minDisplay }} - {{ $maxDisplay }}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <x-admin.status-toggle 
                        :item-id="$option->id"
                        :current-status="$option->is_active"
                        entity-type="finance" />
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                    <x-admin.table-actions 
                        :item="$option"
                        edit-route="admin.finance-options.edit"
                        delete-route="admin.finance-options.destroy"
                        :has-toggle="true"
                        :delete-data="[
                            'finance-bank' => $option->bank_name,
                            'finance-program' => $option->name
                        ]" />
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-calculator text-4xl mb-2"></i>
                    <p>Chưa có gói trả góp nào</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    {{-- Pagination --}}
    @if($financeOptions->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        <x-admin.pagination :paginator="$financeOptions" />
    </div>
    @endif
</div>
