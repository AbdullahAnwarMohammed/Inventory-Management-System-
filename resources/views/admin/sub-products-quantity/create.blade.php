@extends('admin.layout.app')
@section('title')
    اضافة كمية اضافية
    <a class="btn btn-primary" href="{{ route('admin.sub-products.index') }}">الانواع</a>
@endsection


@section('content')
    {{-- 
<h5 class="my-2">وحدة القياس ({{$SubProduct->unit == 1 ? "عدد الشكاير" : "الكيلو جرام"}})</h5>
<h5>الصنف ({{$SubProduct->Product->name}})</h5>
 --}}

    <div class="row">
        <div class="col-md-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">{{ $SubProduct->name }}</p>
                            <h5 class="mb-0">الكمية
                                ({{ $SubProduct->quantity > 0 ? $SubProduct->quantity : 'لا يوجد' }}) </h5>
                        </div>

                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                <span class="avatar-title">
                                    <i class="bx bx-copy-alt font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">وحدة القياس</p>
                            <h5 class="mb-0">{{ $SubProduct->unit == 1 ? 'عدد الشكاير' : 'الكيلو جرام' }}</h5>
                        </div>

                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                <span class="avatar-title">
                                    <i class="bx bx-copy-alt font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">الصنف</p>
                            <h5 class="mb-0">{{ $SubProduct->Product->name }}</h5>
                        </div>

                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                <span class="avatar-title">
                                    <i class="bx bx-copy-alt font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">السعر</p>
                            <h5 class="mb-0">{{ !empty($SubProduct->quantities->last()->price) ?  $SubProduct->quantities->last()->price : 'لم يتم الاضافة' }}</h5>
                        </div>

                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                <span class="avatar-title">
                                    <i class="bx bx-copy-alt font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">اضافة كمية</h1>
                <form action="{{ route('admin.subProductsQuantity.store', $SubProduct->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" name="sub_product_id" value="{{ $SubProduct->id }}">
                        <label for="">الكمية المضافة</label>
                        <input type="text" required name="quantity" id="quantity" class="form-control form-control-lg">
                    </div>
                    <div class="form-group">
                        <label for="">السعر</label>
                        <input type="text" required name="price" id="price" class="form-control form-control-lg">
                    </div>
                    <div class="form-group">
                        <label for="">المجموع</label>
                        <input type="text" required name="total" readonly id="total"
                            class="form-control form-control-lg">
                    </div>
                    <input onclick="return confirm('سوف تقوم باضافة الفاتورة ؟ ')" type="submit"
                        class="btn btn-primary my-2" value="اضافة">
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('js')
    <script>
        $('#quantity, #price').on('input', function() {
            const quantity = $('#quantity').val();
            const price = $('#price').val();

            if (quantity && price) {
                const total = quantity * price;
                $('#total').val(total);
            } else {
                $('#total').val('');
            }
        });
    </script>
@endpush
