@extends('admin.layout.app')


@section('title')
    اضافة جديدة
    <a href="{{ route('admin.daily-expense.index') }}" class="btn btn-primary">المصاريف اليومية</a>
@endsection


@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.daily-expense.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="">البند</label>
                    <select name="albunud_id" class="form-control">
                        @foreach ($Albunds as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('albunud_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group my-2">
                    <label for="">القيمة المالية</label>
                    <input type="number" name="amount" class="form-control  text-center fw-bold">
                    @error('amount')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="">الوصف</label>
                    <textarea name="description" class="form-control" cols="30" rows="10"></textarea>
                </div>

                <input type="submit" class="btn btn-primary my-2" value="اضف">
            </form>
        </div>
    </div>
@endsection
