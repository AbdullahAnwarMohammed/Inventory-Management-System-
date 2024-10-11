@extends('admin.layout.app')

@section('title')
    اضافة نوع جديد <a href="{{ route('admin.sub-products.index') }}" class="btn btn-primary">الانواع</a>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-body">

                    <form action="{{ route('admin.sub-products.update',$SubProduct->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">

                            <div class="col">
                                <input type="text" value="{{$SubProduct->name}}" name="name" required class="form-control form-control-lg"
                                    placeholder=" اسم النوع  (اجباري)">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col">
                                <select name="product_id" class="form-control form-control-lg">
                                    <option value="" selected disabled>اختر الصنف</option>

                                    @foreach ($Products as $Item)
                                        <option  @selected($Item->id == $SubProduct->product->id) value="{{ $Item->id }}">{{ $Item->name }}</option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col">
                                <select name="unit" id="unit" class="form-control form-control-lg">
                                    <option value="" selected disabled>وحدة القيام</option>
                                    <option value="0" @selected($SubProduct->unit == 0)>كيلو جرام</option>
                                    <option value="1"  @selected($SubProduct->unit == 1)>عدد الشكاير</option>
                                </select>
                                @error('unit')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                       



                        <input type="submit" class="btn btn-lg btn-success btn-lg my-2" value="تعديل البيانات">
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            $('#unit').change(function() {
                const unit = $(this).val();

                if (unit == 0) {
                    $('#quantity').attr('placeholder', 'أدخل الكمية بالكيلو جرام');
                    $('#price').attr('placeholder', 'أدخل سعر الكيلو جرام');
                }
                if (unit == 1) {
                    $('#quantity').attr('placeholder', 'أدخل عدد الشكاير');
                    $('#price').attr('placeholder', 'أدخل سعر الشكارة');
                }
            });

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
        });
    </script>
@endpush
