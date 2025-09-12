@extends('layouts.app')
@section('title', 'Sửa lịch lái thử')
@section('content')

<div class="max-w-5xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
	<div class="flex items-center justify-between mb-4 sm:mb-6">
		<div>
			<div class="text-xs text-gray-500">Lịch lái thử</div>
			<h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight">Sửa lịch #{{ $testDrive->test_drive_number ?? $testDrive->id }}</h1>
		</div>
		<a href="{{ route('test-drives.show', $testDrive) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-semibold"><i class="fas fa-arrow-left"></i> Quay lại</a>
	</div>

	@if(session('error') || $errors->any())
		<script>
		(function(){
			try{
				if (typeof showMessage === 'function'){
					var err = @json(session('error'));
					if (err) showMessage(err, 'error');
					var errs = @json($errors->all());
					if (Array.isArray(errs)){
						errs.forEach(function(m){ if (m) showMessage(m, 'error'); });
					}
				}
			}catch(e){}
		})();
		</script>
	@endif

	<!-- Summary card -->
	<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-4 sm:mb-6">
		<div class="flex items-start gap-4">
			<div class="w-32 h-20 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0">
				<img src="{{ optional(optional($testDrive->carVariant)->images->first())->image_url ?: 'https://placehold.co/200x120/EEF2FF/3730A3?text=Car' }}" alt="thumb" class="w-full h-full object-cover" />
			</div>
			<div class="min-w-0 flex-1">
				<div class="text-gray-700 text-sm">Mẫu xe</div>
				<div class="font-semibold text-gray-900 truncate">
					<a href="{{ route('car-variants.show', $testDrive->car_variant_id) }}" class="hover:text-indigo-700">
						{{ optional(optional($testDrive->carVariant)->carModel)->name }} {{ optional($testDrive->carVariant)->name ?? '' }}
					</a>
				</div>
				<div class="mt-2 grid grid-cols-2 sm:grid-cols-4 gap-3 text-sm">
					<div class="flex items-center gap-2"><i class="far fa-calendar"></i><span>{{ optional($testDrive->preferred_date)->format('d/m/Y') }}</span></div>
					<div class="flex items-center gap-2"><i class="far fa-clock"></i><span>{{ is_string($testDrive->preferred_time) ? substr($testDrive->preferred_time,0,5) : optional($testDrive->preferred_time)->format('H:i') }}</span></div>
					<div class="flex items-center gap-2"><i class="fas fa-store"></i><span>{{ $testDrive->showroom->name ?? '—' }}</span></div>
					<div class="flex items-center gap-2"><i class="fas fa-hourglass-half"></i><span>{{ $testDrive->duration_minutes ? ($testDrive->duration_minutes.' phút') : '—' }}</span></div>
				</div>
			</div>
		</div>
	</div>

	<form method="POST" action="{{ route('test-drives.update', $testDrive) }}" class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
		@csrf
		@method('PUT')

		<!-- Left column -->
		<div class="lg:col-span-2 space-y-4 sm:space-y-6">
			<div class="bg-white rounded-2xl shadow-sm border border-gray-100">
				<div class="px-4 sm:px-6 py-3 border-b flex items-center gap-2"><i class="fas fa-car text-indigo-600"></i><h2 class="font-semibold">Xe & Showroom</h2></div>
				<div class="p-4 sm:p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
					<div class="sm:col-span-2">
						<label class="block text-sm font-medium text-gray-700 mb-1">Mẫu xe</label>
						<select name="car_variant_id" class="w-full border rounded-lg px-3 py-2">
							@foreach($variants as $v)
								<option value="{{ $v->id }}" @selected(old('car_variant_id', $testDrive->car_variant_id) == $v->id)>{{ trim(((optional(optional($v->carModel)->carBrand)->name) ? optional(optional($v->carModel)->carBrand)->name.' • ' : '') . ((optional($v->carModel)->name) ? optional($v->carModel)->name.' • ' : '') . ($v->name ?? '')) }}</option>
							@endforeach
						</select>
					</div>
					<div class="sm:col-span-2">
						<label class="block text-sm font-medium text-gray-700 mb-1">Showroom</label>
						<select name="showroom_id" class="w-full border rounded-lg px-3 py-2">
							<option value="">— Chọn showroom —</option>
							@foreach($showrooms as $s)
								<option value="{{ $s->id }}" @selected(old('showroom_id', $testDrive->showroom_id) == $s->id)>{{ $s->name }}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>

			<div class="bg-white rounded-2xl shadow-sm border border-gray-100">
				<div class="px-4 sm:px-6 py-3 border-b flex items-center gap-2"><i class="far fa-calendar-alt text-indigo-600"></i><h2 class="font-semibold">Thời gian</h2></div>
				<div class="p-4 sm:p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
					<div>
						<label class="block text-sm font-medium text-gray-700 mb-1">Ngày</label>
						<input type="date" name="preferred_date" value="{{ old('preferred_date', optional($testDrive->preferred_date)->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2">
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700 mb-1">Giờ</label>
						<input type="time" name="preferred_time" value="{{ old('preferred_time', (is_string($testDrive->preferred_time) ? substr($testDrive->preferred_time,0,5) : optional($testDrive->preferred_time)->format('H:i'))) }}" class="w-full border rounded-lg px-3 py-2">
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700 mb-1">Thời lượng (phút)</label>
						<input type="number" min="5" name="duration_minutes" class="w-full border rounded-lg px-3 py-2" value="{{ old('duration_minutes', $testDrive->duration_minutes) }}" />
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700 mb-1">Địa điểm</label>
						<input type="text" name="location" class="w-full border rounded-lg px-3 py-2" value="{{ old('location', $testDrive->location) }}" />
					</div>
				</div>
			</div>

			<div class="bg-white rounded-2xl shadow-sm border border-gray-100">
				<div class="px-4 sm:px-6 py-3 border-b flex items-center gap-2"><i class="fas fa-sticky-note text-indigo-600"></i><h2 class="font-semibold">Ghi chú</h2></div>
				<div class="p-4 sm:p-6">
					<label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
					<textarea name="notes" rows="4" class="w-full border rounded-lg px-3 py-2" placeholder="Thông tin bổ sung cho showroom">{{ old('notes', $testDrive->notes) }}</textarea>
				</div>
			</div>
		</div>

		<!-- Right column -->
		<div class="space-y-4 sm:space-y-6">
			<div class="bg-white rounded-2xl shadow-sm border border-gray-100">
				<div class="px-4 sm:px-6 py-3 border-b flex items-center gap-2"><i class="fas fa-user-check text-indigo-600"></i><h2 class="font-semibold">Sở thích & kinh nghiệm</h2></div>
				<div class="p-4 sm:p-6 space-y-4">
					<div>
						<label class="block text-sm font-medium text-gray-700 mb-1">Loại lịch</label>
						@php($typeMap=['individual'=>'Cá nhân','group'=>'Nhóm','virtual'=>'Trực tuyến'])
						<select name="test_drive_type" class="w-full border rounded-lg px-3 py-2">
							@foreach($typeMap as $k=>$vlabel)
								<option value="{{ $k }}" @selected(old('test_drive_type', $testDrive->test_drive_type) == $k)>{{ $vlabel }}</option>
							@endforeach
						</select>
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700 mb-1">Kinh nghiệm</label>
						@php($expMap=[''=>'— Chọn —','beginner'=>'Mới bắt đầu','intermediate'=>'Trung bình','advanced'=>'Nhiều kinh nghiệm'])
						<select name="experience_level" class="w-full border rounded-lg px-3 py-2">
							@foreach($expMap as $k=>$vlabel)
								<option value="{{ $k }}" @selected(old('experience_level', ($testDrive->experience_level ?? '')) == $k)>{{ $vlabel }}</option>
							@endforeach
						</select>
					</div>
					<label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="has_experience" value="1" class="rounded" @checked(old('has_experience', $testDrive->has_experience))> Đã từng lái xe tương tự</label>
				</div>
			</div>

			<div class="bg-white rounded-2xl shadow-sm border border-gray-100">
				<div class="px-4 sm:px-6 py-3 border-b flex items-center gap-2"><i class="fas fa-list text-indigo-600"></i><h2 class="font-semibold">Yêu cầu đặc biệt</h2></div>
				<div class="p-4 sm:p-6">
					<textarea name="special_requirements" rows="4" class="w-full border rounded-lg px-3 py-2" placeholder="Ví dụ: Yêu cầu tư vấn tính năng an toàn, đường thử cụ thể, ...">{{ old('special_requirements', $testDrive->special_requirements) }}</textarea>
				</div>
			</div>

			<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
				<button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-3 rounded-xl transition"><i class="fas fa-save"></i> Lưu thay đổi</button>
				<div class="mt-2 text-xs text-gray-500 text-center">Thay đổi sẽ được lưu và áp dụng ngay cho lịch của bạn</div>
			</div>
		</div>
	</form>
</div>

@endsection


