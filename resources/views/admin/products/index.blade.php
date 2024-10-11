@extends('admin.layout.app')

@section('title')
    الاصناف <a class="btn btn-primary" href="{{ route('admin.products.create') }}"><i class="bx bx-add-to-queue"></i> اضافة صنف</a>
@endsection



@section('content')
    <div class="row">

        @if (Session::has('Success'))
            <div class="alert alert-success">{{ Session::get('Success') }}</div>
        @endif
        @if (Session::has('Error'))
            <div class="alert alert-danger">{{ Session::get('Error') }}</div>
        @endif

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">جميع الاصناف <span class="badge bg-success">{{ $Products->count() }}</span></div>
                    <div class="table-responsive">
                        <table id="table" class="table text-center   table-hover table-bordered "  id="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الصنف</th>
                                    <th>الانواع</th>
                                    <th>الحدث</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($Products as $Item)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $Item->name }}</td>
                                        <td><span class="badge bg-success">{{ $Item->subProducts->count() }}</span></td>
                                        <td>
                                            <a href="{{ route('admin.products.edit', $Item->id) }}"
                                                class="btn btn-sm btn-success"> <i class="bx bx-edit"></i> تعديل</a>
                                            <form onclick="return confirm('سوف تقوم بعملية الحذف !!')" class="d-inline"
                                                action="{{route('admin.products.destroy',$Item->id)}}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"> <i class="bx bx-trash-alt"></i> حذف</button>
                                            </form>
                                        </td>
                                    </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection


