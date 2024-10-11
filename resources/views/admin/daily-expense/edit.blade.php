@extends('admin.layout.app')


@section('title')
    تعديل البيانات
    <a href="{{ route('admin.daily-expense.index') }}" class="btn btn-primary">المصاريف اليومية</a>
@endsection


@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.daily-expense.update',$Item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="">البند</label>
                    <select name="albunud_id" class="form-control">
                        @foreach (\App\Models\Albunud::all() as $item)
                            <option value="{{ $item->id }}" @selected($item->id == $Item->albunud_id)>{{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('albunud_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group my-2">
                    <label for="">القيمة المالية</label>
                    <input type="number" value="{{$Item->amount}}" name="amount" class="form-control  text-center fw-bold">
                    @error('amount')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="">الوصف</label>
                    <textarea name="description" class="form-control" cols="30" rows="10">{{$Item->description}}</textarea>
                </div>

                <input type="submit" class="btn btn-success my-2" value="تعديل البيانات">
            </form>
        </div>
    </div>
@endsection
