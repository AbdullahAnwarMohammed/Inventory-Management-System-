@extends('admin.layout.app')

@section('title')
   <div>
    المحفظة الخاصة بـ ({{$Customer->name}})
   </div>
   <a href="{{route('admin.customers.index')}}" class="btn btn-primary">قاعدة العملاء</a>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mini-stats-wid">
                <div class="card-body ">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">الحساب الحالي</p>
                            <h4 class="mb-0 ">
                                <span class="{{$Customer->wallet->balance > 0 ? "badge bg-success" : "badge bg-danger"}}">{{$Customer->wallet->balance}}</span>
                            </h4>
                        </div>

                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                <span class="avatar-title">
                                    <i class="bx bx-wallet-alt font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title">اضافة تحويلة مالية</h1>
                    <form action="{{route('admin.wallet.store')}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" name="wallet_id" value="{{$Customer->wallet->id}}">
                            <input type="number" name="amount" required class="text-center form-control form-control-lg" placeholder="المبلغ المراد اضافتة للعميل">
                        </div>
                        <div class="form-group my-2">
                            <textarea name="description" placeholder="تعليق" class="form-control form-control-lg" cols="30" rows="10"></textarea>
                        </div>
                        <input type="submit" class="btn btn-lg my-2 btn-success" value="اضافة الرصيد">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection