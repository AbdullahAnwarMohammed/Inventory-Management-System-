@extends('admin.layout.app')

@section('title')
    الفواتير
    <div>
        <a class="btn btn-primary" href="{{ route('admin.invoices.create') }}">
            <i class="bx bx-add-to-queue"></i> فاتورة جديدة
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">جميع الفواتير</div>
                    <div class="row">
                        <div class="col">
                            <label for="product">المنتج الرئيسي:</label>
                            <select id="product" class="form-select">
                                <option value="">اختر المنتج الرئيسي</option>
                                @foreach (\App\Models\Product::all() as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col">
                            <label for="subProduct">المنتج الفرعي:</label>
                            <select id="subProduct" class="form-select">
                                <option value="">اختر المنتج الفرعي</option>
                                <!-- سيتم ملء المنتجات الفرعية عبر JavaScript -->
                            </select>
                        </div>

                    </div>
                    <!-- فلترة الفواتير -->
                    <div class="row">
                        <div class="col">
                            <label for="year">السنة:</label>
                            <select id="year" class="form-select">
                                <option value="">اختر سنة</option>
                                @for ($i = date('Y'); $i >= 2024; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="col">
                            <label for="month">الشهر:</label>
                            <select id="month" class="form-select">
                                <option value="">اختر شهر</option>
                                @foreach (range(1, 12) as $month)
                                    <option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 1)) }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-12 my-3 text-center">
                            <label for="time_filter">فلترة الوقت:</label>
                            <button class="btn btn-secondary filter-time" data-range="today">اليوم</button>
                            <button class="btn btn-secondary filter-time" data-range="week">الأسبوع الأخير</button>
                            <button class="btn btn-secondary filter-time" data-range="four_weeks">آخر 4 أسابيع</button>
                            <button class="btn btn-secondary filter-time" data-range="thirty_days">آخر 31 يومًا</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table text-center table-hover table-bordered" id="invoices-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>العميل</th>
                                    <th>المبلغ</th>
                                    <th>التاريخ</th>
                                    <th>رقم الفاتورة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- سيتم ملء البيانات باستخدام DataTables -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // تهيئة DataTables
            var table = $('#invoices-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.invoices.index') }}',
                    data: function(d) {
                        d.year = $('#year').val();
                        d.month = $('#month').val();
                        d.product_id = $('#product').val();
                        d.sub_product_id = $('#subProduct').val();
                        d.time_filter = $('.filter-time.active').data('range');
                        d.search = $('input[type="search"]').val(); // إضافة البحث
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data) {
                            return moment(data).format('YYYY-MM-DD HH:mm:ss');
                        }
                    },
                    {
                        data: 'invoice_number',
                        name: 'invoice_number'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // حدث عند تغيير المنتج الرئيسي
            $('#product').change(function() {
                var productId = $(this).val();
                $.ajax({
                    url: `/products/${productId}/sub-products`,
                    data: {
                        product_id: productId
                    },
                    success: function(subProducts) {
                        var options = '<option value="">اختر المنتج الفرعي</option>';
                        $.each(subProducts, function(index, subProduct) {
                            options +=
                                `<option value="${subProduct.id}">${subProduct.name}</option>`;
                        });
                        $('#subProduct').html(options);
                    }
                });
                table.ajax.reload(); // إعادة تحميل الفواتير بعد اختيار المنتج الرئيسي
            });

            // حدث عند تغيير المنتج الفرعي
            $('#subProduct').change(function() {
                table.ajax.reload(); // إعادة تحميل الفواتير بعد اختيار المنتج الفرعي
            });

            // حدث عند تغيير السنة أو الشهر
            $('#year, #month').change(function() {
                table.ajax.reload();
            });

            // حدث عند الضغط على فلاتر الوقت
            $('.filter-time').click(function() {
                $('.filter-time').removeClass('active');
                $(this).addClass('active');
                table.ajax.reload();
            });



         
        });
    </script>
@endpush
