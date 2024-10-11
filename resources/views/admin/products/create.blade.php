@extends('admin.layout.app')

@section('title')
اضافة صنف جديد  <a href="{{route('admin.products.index')}}" class="btn btn-primary">الاصناف</a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
     
        <div class="card">
            <div class="card-body">
                
                <form action="{{ route('admin.products.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="name" required class="form-control form-control-lg"
                            placeholder=" اسم الصنف  (اجباري)">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group my-2">
                        <textarea name="description" class="form-control form-control-lg" cols="30" rows="10"
                            placeholder="وصف الصنف (اختياري)"></textarea>
                    </div>
                    <input type="submit" class="btn btn-lg btn-success my-2" value="اضافة">
                </form>
    
            </div>
        </div>
    </div>
</div>
@endsection