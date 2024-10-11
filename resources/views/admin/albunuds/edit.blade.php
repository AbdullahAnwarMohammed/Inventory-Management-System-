@extends('admin.layout.app')

@section('title')
    تعديل بند ({{$Albund->name}})
    <a href="{{route('admin.albunuds.index')}}" class="btn btn-primary">البنود</a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.albunuds.update',$Albund->id)}}" method="POST">
                @csrf
                @method("PUT")
                <div class="form-group">
                    <label for="">اسم البند</label>
                    <input value="{{$Albund->name}}" type="text" name="name" class="form-control">
                    @error('name')
                        <div class="text-danger">{{$message}}</div>
                    @enderror
                </div>

                <input type="submit" class="btn btn-primary my-3" value="تعديل البند">
            </form>
        </div>
    </div>
@endsection