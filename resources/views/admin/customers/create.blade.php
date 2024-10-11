@extends('admin.layout.app')

@section('title')
اضافة عميل جديد <a href="{{route('admin.customers.index')}}" class="btn btn-primary">قاعدة العملاء</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{route("admin.customers.store")}}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <label for="">الاسم</label>
                    <input type="text" name="name" class="form-control form-control-lg">
                    @error('name')
                        <div class="text-danger">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="">الهاتف</label>
                    <input type="text" name="phone" class="form-control form-control-lg">
                </div>
                
            </div>
            <div class="row my-3">
                <div class="col-md-6">
                    <label for="">البريد</label>
                    <input type="text" name="email" class="form-control form-control-lg">
                </div>
                <div class="col-md-6">
                    <label for="">العنوان</label>
                    <input type="text" name="address" class="form-control form-control-lg">
                </div>
            </div>
            <input type="submit" class="btn btn-primary" value="اضافة">
        </form>
    </div>
</div>
@endsection