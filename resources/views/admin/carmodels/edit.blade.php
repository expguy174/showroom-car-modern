@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@extends('admin.layouts.app')

@section('title', 'Cập nhật mẫu xe')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Cập nhật mẫu xe</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.carmodels.update', $carModel) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="car_id">Hãng xe</label>
                <select name="car_id" id="car_id" class="form-control" required>
                    @foreach($cars as $car)
                        <option value="{{ $car->id }}" {{ $carModel->car_brand_id == $car->id ? 'selected' : '' }}>{{ $car->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="name">Tên mẫu xe</label>
                <input type="text" name="name" id="name" value="{{ $carModel->name }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea name="description" id="description" rows="4" class="form-control">{{ $carModel->description }}</textarea>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" {{ $carModel->is_active ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Hiển thị</label>
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div>
</div>
@endsection