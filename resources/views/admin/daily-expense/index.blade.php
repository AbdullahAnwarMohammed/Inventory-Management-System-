@extends('admin.layout.app')

@section('title')
    المصاريف
    <a href="{{ route('admin.daily-expense.create') }}" class="btn btn-primary">اضافة</a>
@endsection
@push('css')
    <style>
        td:nth-child(3) {
            background: #2a3042;
            color: #fff;
            font-weight: bold;
        }
       
    </style>
@endpush
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- عرض المجموع -->
                <h5 class="bg-primary text-white py-2 text-center rounded-2">إجمالي المبلغ: <span id="totalAmount">0</span>
                    جنية</h5>

            </div>
        </div>

        <div class="row">
            <div class="col">
                <label for="year">السنة:</label>
                <select id="year" class="form-select" aria-label="Select Year">
                    <option value="">اختر سنة</option>
                    @for ($i = date('Y'); $i >= 2024; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="col">
                <label for="month">الشهر:</label>
                <select id="month" class="form-select" aria-label="Select Month">
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

        <div class="card">
            <div class="card-body">
                <!-- فلترة الفئة -->
                <div class="form-group my-2">
                    <label for="category_filter">فلترة حسب الفئة:</label>
                    <select id="category_filter" class="form-control">
                        <option value="">اختر الفئة</option>
                        @foreach (\App\Models\Albunud::all() as $albunds)
                            <option value="{{ $albunds->id }}">{{ $albunds->name }}</option>
                        @endforeach
                    </select>
                </div>



                <table id="dailyExpensesTable" class="table table-bordered table-striped text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>المبلغ</th>
                            <th>الفئة</th>
                            <th>التاريخ</th>

                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            let table = $('#dailyExpensesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.daily-expense.index') }}",
                    data: function(d) {
                        d.albunud_id = $('#category_filter').val(); // إرسال ID الفئة مع الطلب
                        d.year = $('#year').val();
                        d.month = $('#month').val();
                        d.time_range = $('.filter-time.active').data('range'); // وقت الفلترة النشط

                    },
                    dataSrc: function(json) {
                        console.log(json);
                        // تحديث قيمة المجموع بناءً على الاستجابة
                        $('#totalAmount').text(json.totalAmount);
                        return json.data;
                    }
                },
                pageLength: 10, // تحديد عدد الصفوف الافتراضي في الصفحة
                lengthMenu: [5, 10, 25, 50, 100], // تحديد الخيارات المتاحة لعدد الصفوف


                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'category_name',
                        name: 'category_name'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data) {
                            return moment(data).format('YYYY-MM-DD HH:mm:ss');
                        }
                    },

                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            // إعادة تحميل الجدول عند تغيير الفئة
            $('#year, #month,#category_filter').change(function() {
                table.draw();
            });

            // إضافة active class عند الضغط على زر الفلترة بالوقت
            $('.filter-time').click(function() {
                $('.filter-time').removeClass('active');
                $(this).addClass('active');
                table.draw(); // إعادة تحميل البيانات مع الفلترة الجديدة
            });
        });
    </script>
@endpush
