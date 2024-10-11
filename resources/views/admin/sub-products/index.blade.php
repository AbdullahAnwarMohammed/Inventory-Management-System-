@extends('admin.layout.app')

@section('title')
    <div>
        الانواع <span class="badge bg-success">{{ $SubProducts->count() }}</span>
    </div>
    <a class="btn btn-primary" href="{{ route('admin.sub-products.create') }}"><i class="bx bx-add-to-queue"></i> نوع جديد</a>
@endsection

@push('css')
    <style>
        td:nth-child(5){
            background: #2a3042;
            color:#fff;
            font-weight: bold;
        }
    </style>
@endpush

@section('content')
    <div class="row">


        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <!-- إضافة قائمة منسدلة للفلترة -->
                    <div class="mb-3">
                        <label for="filter">فلترة حسب:</label>
                        <select id="filter" class="form-control">
                            <option value="">جميع الأنواع</option>
                            <option value="most_sold">الأكثر مبيعًا</option>
                            <option value="least_sold">الأقل مبيعًا</option>
                        </select>
                    </div>

                    <div class="table-responsive">
                        <table class="table text-center table-hover table-bordered" id="sub-products-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الصنف</th>
                                    <th>الكمية</th>
                                    <th>السعر</th>
                                    <th>النوع</th>
                                    <th>الحدث</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- سيتم تعبئة البيانات باستخدام DataTables -->
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
            var table = $('#sub-products-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.sub-products.index') }}',
                    data: function(d) {
                        d.filter = $('#filter').val(); // إضافة الفلترة مع الطلب
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'product_name',
                        name: 'product_name'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // تحديث الجدول عند تغيير الفلتر
            $('#filter').change(function() {
                table.draw();
            });
        });
    </script>
@endpush
