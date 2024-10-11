@extends('admin.layout.app')

@section('title')
    <div>
        <i class="bx bx-home"></i> الصفحة الرئيسية

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">العملاء</p>
                            <h4 class="mb-0">{{ App\Models\Customer::count() }}</h4>
                        </div>

                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                <span class="avatar-title">
                                    <i class="bx bx-cool font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">الاقسام الرئيسية</p>
                            <h4 class="mb-0">{{ App\Models\Product::count() }}</h4>
                        </div>

                        <div class="flex-shrink-0 align-self-center ">
                            <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class="bx bx-sitemap font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">الاقسام الفرعية</p>
                            <h4 class="mb-0">{{ App\Models\SubProduct::count() }}</h4>
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

    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row my-2">
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
                    </div>

                    <div class="col-12 my-2">
                        <label for="product">اختر الصنف:</label>
                        <select id="product" class="form-select" aria-label="Select Product">
                            <option value="">اختر صنف</option>
                            @foreach (\App\Models\Product::all() as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="time_filter">فلترة الوقت:</label>
                        <button class="btn btn-secondary filter-time" data-range="today">اليوم</button>
                        <button class="btn btn-secondary filter-time" data-range="week">الأسبوع الأخير</button>
                        <button class="btn btn-secondary filter-time" data-range="four_weeks">آخر 4 أسابيع</button>
                        <button class="btn btn-secondary filter-time" data-range="thirty_days">آخر 31 يومًا</button>
                    </div>

                    <canvas id="subProductSalesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script>
        // متغير لتخزين المخطط
        let subProductSalesChart;

        $(document).ready(function() {
            // تعريف المخطط في البداية
            const ctx = document.getElementById('subProductSalesChart').getContext('2d');
            subProductSalesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($labels), // تسميات SubProducts
                    datasets: [{
                        label: 'مبيعات SubProduct',
                        data: @json($salesData), // بيانات المبيعات
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // دالة لتحديث المخطط
            function updateChart(labels, salesData) {
                subProductSalesChart.data.labels = labels; // تحديث التسميات
                subProductSalesChart.data.datasets[0].data = salesData; // تحديث البيانات
                subProductSalesChart.update(); // تحديث المخطط
            }

            // عندما يتم تغيير السنة أو الشهر أو الضغط على فلتر الوقت
            $('#year, #month, #product').on('change', function() {
                filterChartData();
            });
            $('.filter-time').on('click', function() {
                const timeRange = $(this).data('range');
                filterChartData(timeRange);
            });

            // دالة لجلب البيانات المفلترة وتحديث المخطط
            function filterChartData(timeRange = null) {
                const year = $('#year').val();
                const month = $('#month').val();
                const productId = $('#product').val();
                $.ajax({
                    url: '{{ route('admin.filteredSubProductSales') }}', // مسار الراوت المناسب في Laravel
                    method: 'GET',
                    data: {
                        year: year,
                        month: month,
                        product_id: productId,

                        time_range: timeRange
                    },
                    success: function(response) {
                        updateChart(response.labels, response
                            .salesData); // تحديث المخطط بالبيانات الجديدة
                    },
                    error: function(xhr) {
                        console.log('حدث خطأ في جلب البيانات:', xhr);
                    }
                });
            }
        });
    </script>
@endpush
