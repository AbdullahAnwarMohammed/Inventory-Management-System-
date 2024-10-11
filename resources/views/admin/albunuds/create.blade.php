@extends('admin.layout.app')

@section('title')
    اضافة بند جديد
    <a href="{{route('admin.albunuds.index')}}" class="btn btn-primary">البنود</a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.albunuds.store')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="">اسم البند</label>
                    <input type="text" name="name" class="form-control">
                    @error('name')
                        <div class="text-danger">{{$message}}</div>
                    @enderror
                </div>

                <input type="submit" class="btn btn-success my-3" value="اضافة">
            </form>
        </div>
    </div>
@endsection