@extends('admin.layout.app')

@section('title')
    العمليات المالية الخاصة ب {{ $customer->name }}

    <a href="{{route('admin.customers.index')}}">العملاء</a>
@endsection


@section('content')
    <div class="filter-container">
        <label for="">ايداع و سحب !</label>
        <select id="transaction_filter" class="form-control">
            <option value="">الجميع</option>
            <option value="deposit">ايداع</option>
            <option value="withdrawal">شراء بضاعة</option>
        </select>
    </div>

    <div class="row">
        <div class="col">
            <label for="">من</label>
            <input type="date" id="start_date" placeholder="Start Date" class="form-control">

        </div>
        <div class="col">
            <label for="">الي</label>
            <input type="date" id="end_date" placeholder="End Date" class="form-control">

        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <table id="transaction_table" class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>النوع</th>
                            <th>المبلغ</th>
                            <th>التاريع</th>
                            <th>الصافي</th> <!-- إضافة عمود المبلغ الصافي -->
                            <th>الصافي</th> <!-- إضافة عمود المبلغ الصافي -->

                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // تهيئة DataTables مع طلب AJAX
            var table = $('#transaction_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.transaction.find', $customer->id) }}',
                    data: function(d) {
                        d.type = $('#transaction_filter').val(); // إرسال قيمة الفلترة
                        d.start_date = $('#start_date').val(); // تاريخ البدء
                        d.end_date = $('#end_date').val(); // تاريخ الانتهاء
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'type',
                        name: 'type',
                        render: function(data, type, row, meta) {
                            // تغيير النص بناءً على نوع المعاملة
                            if (data === 'deposit') {
                                return 'ايداع'; // النص المراد عرضه للودائع
                            } else if (data === 'withdrawal') {
                                return 'شراء بضاعة'; // النص المراد عرضه للسحب
                            } else {
                                return data; // النص الأصلي إذا لم يكن النوع من الأنواع المحددة
                            }
                        }
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },

                    {
                        data: 'net_amount',
                        name: 'net_amount'
                    }, // العمود الجديد للمبلغ الصافي
                    { data: 'action', name: 'action', orderable: false, searchable: false } // العمود الجديد للأزرار

                ],
                rowCallback: function(row, data, index) {
                    // التحقق من نوع المعاملة وتعيين لون الخلفية
                    if (data.type === 'deposit') {
                        $('td', row).eq(1).css('background-color', '#299629');
                        $('td', row).eq(1).css('color', '#fff');
                    } else if (data.type === 'withdrawal') {
                        $('td', row).eq(1).css('background-color', '#ce4242');
                        $('td', row).eq(1).css('color', '#fff');

                    }
                }

            });

            // إعادة تحميل الجدول عند تغيير الفلترة
            $('#transaction_filter,#start_date,#end_date').change(function() {
                table.ajax.reload();
            });

        });
    </script>
@endpush
