<!-- resources/views/admin/invoices/show.blade.php -->

@extends('admin.layout.app')

@section('title')
    تفاصيل الفاتورة <a href="{{route('admin.invoiceItem.create',$invoice->id)}}" class="btn btn-primary">اضافة منتج اخر</a>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">رقم الفاتورة <span >({{ $invoice->invoice_number }})</span></div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5>العميل:</h5>
                            <p>{{ $invoice->customer->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>التاريخ:</h5>
                            <p>{{ $invoice->created_at->format('Y-m-d') }}</p>
                        </div>
                    </div>
                    
                    <h5>تفاصيل الفاتورة:</h5>
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>المنتج</th>
                                <th>الكمية</th>
                                <th>السعر</th>
                                <th>الإجمالي</th>
                                <th>
                                    الاحداث
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoice->invoiceItems as $item)
                                <tr>
                                    <td>{{ $item->subProduct->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->price, 2) }} </td>
                                    <td>{{ number_format($item->price * $item->quantity, 2) }} </td>
                                    <td>
                                        <form class="d-inline" onclick="return confirm('سوف تقوم بعملية الحذف')" action="{{route('admin.invoiceItem.destory',$item->id)}}" method="POST">
                                            @csrf 
                                            @method('DELETE')
                                            
                                            <input type="hidden" name="invoice_id" value="{{$item->invoice->id}}">
                                            <input type="hidden" name="created_at" value="{{$invoice->created_at}}">
                                            <input type="hidden" name="customer_id" value="{{$invoice->customer->id}}">
                                            <input type="hidden" name="total" value="{{number_format($item->price * $item->quantity, 2)}}">
                                            <button class="btn btn-danger btn-sm">حذف</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <h5>المجموع الكلي:</h5>
                    <p>{{ number_format($invoice->total, 2) }} </p>

                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-primary">العودة</a>
                    {{-- <a href="{{route('admin.invoices.edit.invoiceItem')}}" class="btn btn-success">تعديل</a> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
