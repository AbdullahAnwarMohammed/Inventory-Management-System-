@extends('admin.layout.app')

@section('title')
    العمليات الخاصة بـ ({{ $SubProduct->name }})
    <a href="{{route('admin.sub-products.index')}}" class="btn btn-primary">الانواع</a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table text-center table-bordered table-hover" id="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>كـ المضافة</th>
                        <th>سـ الوحدة</th>
                        <th>المجموع</th>
                        <th>التاريخ</th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($SubProduct->quantities()->orderBy('id', 'desc')->get() as $item)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->price }}</td>
                            <td>{{ $item->total }}</td>
                            <td>{{ $item->date }}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endsection
