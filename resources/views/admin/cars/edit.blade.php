@extends('admin.layouts.app')

@section('title', 'Cập nhật hãng xe')

@section('content')
<div class="card shadow mb-4 max-w-4xl mx-auto">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Cập nhật hãng xe</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.cars.update', $car) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <h6 class="font-weight-bold text-primary mb-3">Thông tin cơ bản</h6>
                    
                    <div class="form-group">
                        <label for="name">Tên hãng xe <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $car->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="country">Quốc gia</label>
                        <input type="text" name="country" id="country" class="form-control @error('country') is-invalid @enderror" value="{{ old('country', $car->country) }}">
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="founded_year">Năm thành lập</label>
                        <input type="number" name="founded_year" id="founded_year" class="form-control @error('founded_year') is-invalid @enderror" value="{{ old('founded_year', $car->founded_year) }}" min="1800" max="{{ date('Y') + 1 }}">
                        @error('founded_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Mô tả</label>
                        <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $car->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="logo_path">Logo (ảnh)</label>
                        <input type="file" name="logo_path" id="logo_path" class="form-control-file @error('logo_path') is-invalid @enderror" onchange="previewLogo(event)" accept="image/*">
                        @error('logo_path')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        {{-- Hiển thị logo hiện tại --}}
                        @if($car->logo_path)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $car->logo_path) }}" alt="Logo hiện tại" width="100" class="img-thumbnail">
                                <small class="text-muted d-block">Logo hiện tại</small>
                            </div>
                        @endif

                        {{-- Preview ảnh mới nếu có chọn --}}
                        <div class="mt-2">
                            <img id="logoPreview" src="#" alt="Preview ảnh mới" style="display:none; max-height: 100px;" class="img-thumbnail">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <h6 class="font-weight-bold text-primary mb-3">Thông tin liên hệ</h6>
                    
                    <div class="form-group">
                        <label for="website">Website</label>
                        <input type="url" name="website" id="website" class="form-control @error('website') is-invalid @enderror" value="{{ old('website', $car->website) }}" placeholder="https://example.com">
                        @error('website')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">Điện thoại</label>
                        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $car->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $car->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address">Địa chỉ</label>
                        <textarea name="address" id="address" rows="2" class="form-control @error('address') is-invalid @enderror">{{ old('address', $car->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <h6 class="font-weight-bold text-primary mb-3 mt-4">SEO & Marketing</h6>
                    
                    <div class="form-group">
                        <label for="meta_title">Meta Title</label>
                        <input type="text" name="meta_title" id="meta_title" class="form-control @error('meta_title') is-invalid @enderror" value="{{ old('meta_title', $car->meta_title) }}">
                        @error('meta_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="meta_description">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" rows="2" class="form-control @error('meta_description') is-invalid @enderror">{{ old('meta_description', $car->meta_description) }}</textarea>
                        @error('meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="keywords">Từ khóa</label>
                        <input type="text" name="keywords" id="keywords" class="form-control @error('keywords') is-invalid @enderror" value="{{ old('keywords', $car->keywords) }}" placeholder="từ khóa 1, từ khóa 2, từ khóa 3">
                        @error('keywords')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <h6 class="font-weight-bold text-primary mb-3 mt-4">Trạng thái & Hiển thị</h6>
                    
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $car->is_active) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Kích hoạt</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $car->is_featured) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_featured">Nổi bật</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sort_order">Thứ tự sắp xếp</label>
                        <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $car->sort_order) }}" min="0">
                        @error('sort_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Thống kê</label>
                        <div class="form-control-plaintext">
                            <small class="text-muted">
                                Dòng xe: {{ $car->total_models ?? 0 }} | 
                                Phiên bản: {{ $car->total_variants ?? 0 }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-lg px-5">
                    <i class="fas fa-save mr-2"></i>Cập nhật
                </button>
                <a href="{{ route('admin.cars.index') }}" class="btn btn-secondary btn-lg px-5 ml-2">
                    <i class="fas fa-arrow-left mr-2"></i>Quay lại
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Script preview ảnh mới --}}
<script>
    function previewLogo(event) {
        const input = event.target;
        const preview = document.getElementById('logoPreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection