@extends('admin.layout.app')

@section('title')
    البنود
    <a href="{{route('admin.albunuds.create')}}" class="btn btn-primary ">اضافة بند</a>
@endsection

@section('content')
  <div class="card">
    <div class="card-body">
        <div class="card-title">جميع البنود</div>
        <table id="table" class="table text-center table-bordered ">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>
                        الحدث
                    </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i=1;
                @endphp
                @foreach ($albunds as $item)
                    <tr>
                        <td>{{$i++}}</td>
                        <td>{{$item->name}}</td>
                        <td>
                            <a href="{{route('admin.albunuds.edit',$item->id)}}" class="btn btn-sm btn-success">تعديل</a>
                            <form  class="d-inline " action="{{route('admin.albunuds.destroy',$item->id)}}" method="POST" onclick="return confirm('سوف تقوم بالحذف')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm ">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
  </div>
@endsection