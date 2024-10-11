@extends('admin.layout.app')

@section('title')
    تعديل ايداع | {{$transaction->wallet->customer->name}}
    <a href="{{route('admin.transaction.find', $transaction->wallet->customer_id)}}" class="btn btn-primary">للخلف</a>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">القيمة الحالية لعملية الايداع</p>
                            <h4 class="mb-0">{{$transaction->amount}}</h4>
                        </div>

                        <div class="flex-shrink-0 align-self-center">
                            <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class="bx bx-purchase-tag-alt font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">المحفظة الخاصة ب العميل</p>
                            <h4 class="mb-0">{{$transaction->wallet->balance}}</h4>
                        </div>

                        <div class="flex-shrink-0 align-self-center">
                            <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class="bx bx-purchase-tag-alt font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.transaction.update', $transaction->id)}}" method="POST">
                @csrf
                @method('PUT')
                
              

                <div class="row">
                    <div class="col">
                        <label for="">اضافة ام سحب</label>
                        <select name="operation_type" class="form-control" id="operation_type">
                            <option value="deposit">اضافة</option>
                            <option value="withdrawal">سحب</option>
                        </select>
                        @error('operation_type')
                            <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <label for="">القيمة المضافة او المسحوبة</label>
                        <input type="number" required step="0.01" class="form-control" name="operation_amount" id="operation_amount">
                        
                    </div>
                </div>
                
                <div class="row my-2">
                    <div class="col">
                        <label for="">القيمة الجديدة</label>
                        <input type="hidden" id="current_amount" value="{{$transaction->amount}}">
                        <input type="text" required class="form-control" name="new_amount" id="new_amount" readonly>
                    </div>
                </div>

                <div class="row my-3">
                    <div class="col">
                        <label for="">الانشاء</label>
                        <input type="text" readonly class="form-control" value="{{$transaction->created_at}}">
                    </div>
                    <div class="col">
                        <label for="">التعديل</label>
                        <input type="text" readonly class="form-control" value="{{$transaction->updated_at}}">
                    </div>
                </div>
                
                <input type="submit" class="btn btn-primary" value="تعديل">
            </form>
        </div>
    </div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#operation_amount, #operation_type').on('input change', function() {
            let currentAmount = parseFloat($('#current_amount').val());
            let operationAmount = parseFloat($('#operation_amount').val());
            let operationType = $('#operation_type').val();
            let newAmount = currentAmount;

            if (operationType === 'deposit') {
                newAmount += operationAmount;  // إضافة للقيمة الحالية
            } else if (operationType === 'withdrawal') {
                newAmount -= operationAmount;  // خصم من القيمة الحالية
            }

            $('#new_amount').val(newAmount.toFixed(2)); // تحديث القيمة الجديدة في الحقل
        });
    });
</script>
@endpush
