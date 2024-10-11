@extends('admin.layout.app')

@section('title')
    <div>
        <i class="bx bx-user-check"></i> العملاء
    </div>
    <a href="{{ route('admin.customers.create') }}" class="btn btn-primary"> <i class="bx bx-add-to-queue"></i> عميل جديد</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <!-- واجهة الفلترة -->
        <form id="filterForm" class="mb-4">
            <div class="row">
                <div class="col">
                    <label for="filterType">تصفية العملاء:</label>
                    <select name="filterType" id="filterType" class="form-control">
                        <option value="">جميع العملاء</option>
                        <option value="creditor">العملاء الدائنين</option>
                        <option value="debtor">العملاء المدينين</option>
                    </select>
                </div>

                <div class="col">
                    <label for="purchaseFilter">تصفية الشراء:</label>
                    <select name="purchaseFilter" id="purchaseFilter" class="form-control">
                        <option value="">جميع العملاء</option>
                        <option value="purchased_today">العملاء الذين قاموا بشراء اليوم</option>
                        <option value="not_purchased_today">العملاء الذين لم يشتروا اليوم</option>
                    </select>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table id="customers-table" class="table text-center table-bordered">
                <thead>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>المحفظة</th>
                    <th>الاحداث</th>
                </thead>
                <tbody>
                    <!-- سيتم ملء البيانات باستخدام DataTables -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

<!-- روابط جافاسكربت لـ DataTables -->
@push('js')
    <script>
        $(document).ready(function() {
            // تهيئة DataTables
            var table = $('#customers-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("admin.customers.index") }}',
                    data: function (d) {
                        // إرسال الفلترة الحالية مع الطلب
                        d.filterType = $('#filterType').val();
                        d.purchaseFilter = $('#purchaseFilter').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false }, // تأكد من إضافة هذا السطر
                    { data: 'name', name: 'name' },
                    { data: 'wallet_balance', name: 'wallet_balance' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ]
            });

            // إعادة تحميل البيانات عند تغيير الفلتر
            $('#filterType, #purchaseFilter').change(function () {
                table.draw();
            });
        });
    </script>
@endpush
