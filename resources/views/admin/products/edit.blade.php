@extends('admin.layout.app')

@section('title')
    تعديل صنف || {{$Product->name}}
@endsection

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.products.update',$Product->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <input type="text" value="{{$Product->name}}" name="name" required class="form-control form-control-lg"
                            placeholder=" اسم الصنف  (اجباري)">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group my-2">
                        <textarea name="description" class="form-control form-control-lg" cols="30" rows="10"
                            placeholder="وصف الصنف (اختياري)">{{$Product->description}}</textarea>
                    </div>
                    <input type="submit" class="btn btn-lg btn-success btn-lg my-2" value="تعديل البيانات">
                </form>
            </div>
        </div>
    </div>
@endsection