@extends('admin.layout.app')


@section('title')
    عرض البيانات
    <a href="{{ route('admin.daily-expense.index') }}" class="btn btn-primary">المصاريف اليومية</a>
@endsection


@section('content')
    <div class="card">
        <div class="card-body">
            <form action="#" method="POST">
                <div class="form-group">
                    <label for="">البند</label>
                    <select name="albunud_id" readonly class="form-control">
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
                    <input type="number" readonly value="{{$Item->amount}}" name="amount" class="form-control  text-center fw-bold">
                    @error('amount')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="">الوصف</label>
                    <textarea name="description" readonly class="form-control" cols="30" rows="10">{{$Item->description}}</textarea>
                </div>

            </form>
        </div>
    </div>
@endsection
