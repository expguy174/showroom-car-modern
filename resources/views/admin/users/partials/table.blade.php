{{-- Users Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 20%;">
                        Thông tin người dùng
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 20%;">
                        Vai trò
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 20%;">
                        Thông tin nhân viên
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 15%;">
                        Trạng thái
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 10%;">
                        Ngày tạo
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 15%;">
                        Thao tác
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    {{-- User Info --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            {{-- Avatar --}}
                            <div class="flex-shrink-0">
                                @if($user->userProfile && $user->userProfile->avatar_path)
                                    <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-200" 
                                         src="{{ Storage::url($user->userProfile->avatar_path) }}" 
                                         alt="{{ $user->userProfile->name ?? $user->email }}">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">
                                            {{ strtoupper(mb_substr($user->userProfile->name ?? $user->email, 0, 2, 'UTF-8')) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            
                            {{-- User Details --}}
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900 truncate">
                                    {{ $user->userProfile->name ?? 'Chưa có tên' }}
                                </div>
                                <div class="text-sm text-gray-500 truncate">
                                    <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                    {{ $user->email }}
                                </div>
                                @if($user->userProfile && $user->userProfile->phone)
                                <div class="text-sm text-gray-500 mt-1 truncate">
                                    <i class="fas fa-phone text-gray-400 mr-1"></i>
                                    {{ $user->userProfile->phone }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Role --}}
                    <td class="px-6 py-4 whitespace-nowrap text-left">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->getRoleColor() }}">
                            {{ $user->getRoleLabel() }}
                        </span>
                    </td>

                    {{-- Employee Info --}}
                    <td class="px-6 py-4">
                        @if($user->role === 'user')
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-600">
                                <i class="fas fa-user mr-1.5"></i>
                                Khách hàng
                            </span>
                        @else
                            @if($user->employee_id)
                                <div class="text-sm text-gray-900">
                                    <i class="fas fa-id-card text-gray-400 mr-1"></i>
                                    {{ $user->employee_id }}
                                </div>
                            @endif
                            @if($user->department)
                                <div class="text-sm text-gray-500 mt-1">
                                    <i class="fas fa-building text-gray-400 mr-1"></i>
                                    {{ $user->department }}
                                </div>
                            @endif
                            @if($user->position)
                                <div class="text-sm text-gray-500 mt-1">
                                    <i class="fas fa-user-tie text-gray-400 mr-1"></i>
                                    {{ $user->position }}
                                </div>
                            @endif
                            @if(!$user->employee_id && !$user->department && !$user->position)
                                <span class="text-sm text-gray-400 italic">Chưa cập nhật</span>
                            @endif
                        @endif
                    </td>

                    {{-- Status --}}
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex flex-col items-center gap-1">
                            <x-admin.status-toggle
                                :item-id="$user->id"
                                :current-status="$user->is_active"
                                entity-type="user" />
                            
                            @if($user->email_verified || $user->email_verified_at)
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-check-circle mr-1"></i>Đã xác thực
                                </span>
                            @else
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                    <i class="fas fa-exclamation-circle mr-1"></i>Chưa xác thực
                                </span>
                            @endif
                        </div>
                    </td>

                    {{-- Created Date --}}
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="text-sm text-gray-900">{{ $user->created_at->format('d/m/Y') }}</div>
                        <div class="text-xs text-gray-400">{{ $user->created_at->format('H:i') }}</div>
                    </td>

                    {{-- Actions --}}
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <x-admin.table-actions 
                            :item="$user"
                            show-route="admin.users.show"
                            edit-route="admin.users.edit"
                            delete-route="admin.users.destroy"
                            :has-toggle="true" />
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-users text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500 text-lg font-medium">Không tìm thấy người dùng nào</p>
                            <p class="text-gray-400 text-sm mt-1">Thử thay đổi bộ lọc hoặc thêm người dùng mới</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        <x-admin.pagination :paginator="$users" />
    </div>
    @endif
</div>
