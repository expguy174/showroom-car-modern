@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa phiên bản xe')

@section('content')
<div class="card shadow mb-4 mx-3">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">✏️ Cập nhật phiên bản xe</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.carvariants.update', $carvariant->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Mẫu xe --}}
            <div class="form-group">
                <label for="car_model_id">Mẫu xe</label>
                <select name="car_model_id" id="car_model_id" class="form-control" required>
                    @foreach ($carModels as $model)
                        <option value="{{ $model->id }}" {{ old('car_model_id', $carvariant->car_model_id) == $model->id ? 'selected' : '' }}>
                            {{ $model->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tên phiên bản --}}
            <div class="form-group">
                <label for="name">Tên phiên bản</label>
                <input type="text" name="name" value="{{ old('name', $carvariant->name) }}" class="form-control" required>
            </div>

            {{-- Giá --}}
            <div class="form-group">
                <label for="price">Giá (VNĐ)</label>
                <input type="number" name="price" value="{{ old('price', $carvariant->price ?? 0) }}" class="form-control" required>
            </div>

            {{-- Mô tả --}}
            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $carvariant->description) }}</textarea>
            </div>

            {{-- Tính năng --}}
            <div class="form-group">
                <label for="features">Tính năng</label>
                <textarea name="features" class="form-control" rows="3">{{ old('features', $carvariant->features) }}</textarea>
            </div>

            {{-- Trạng thái --}}
            <div class="form-group form-check">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                       {{ old('is_active', $carvariant->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Hiển thị</label>
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div>
</div>
@endsection