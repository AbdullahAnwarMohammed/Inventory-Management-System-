@extends('admin.layout.app')
@section('title')
    اضافة فاتورة جديدة
    <div>
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-primary ">الفواتير</a>


        <button type="button" class="btn btn-success waves-effect" data-bs-toggle="modal"
            data-bs-target=".bs-example-modal-lg">إضافة عميل
            جديد</button>


    </div>
@endsection
@section('content')
    @include('admin.invoices.modal.add-customer')
    <div class="container">

        <form action="{{ route('admin.invoices.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col">
                    <label for="customer_id">اختيار العميل</label>
                    <select id="customer_id" required name="customer_id" class="form-control"
                        onchange="fetchWalletBalance()">
                        <option value="">اختر عميل</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col">
                    <label for="wallet_balance">الرصيد الحالي</label>
                    <input type="text" id="wallet_balance" class="form-control" readonly>

                </div>
            </div>

            <div id="product-list" class="my-3">
                <h3 class="text-primary">إضافة منتجات</h3>
                <div class="card">
                    <div class="card-body">
                        <div class="product-item">
                            <div class="row">
                                <div class="col">
                                    <label for="product_id">اختيار المنتج الرئيسي</label>
                                    <select name="items[0][product_id]" required class="form-control"
                                        onchange="fetchSubProducts(this)">
                                        <option value="">اختر منتج</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('items.0.product_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label for="sub_product_id[]">اختيار المنتج الفرعي</label>
                                    <select name="items[0][sub_product_id]" required class="form-control"
                                        onchange="updateTotal()">
                                        <option value="">اختر منتج فرعي</option>
                                    </select>
                                    @error('items.0.sub_product_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="quantity[]">الكمية</label>
                                    <input type="number" required name="items[0][quantity]" disabled class="form-control"
                                        oninput="updateTotal(); checkStockAvailability(this)">
                                    @error('items.0.quantity')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label for="price[]">السعر</label>
                                    <input type="text" required name="items[0][price]" disabled class="form-control"
                                        oninput="updateTotal()">
                                    @error('items.0.price')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label for="total[]">المجموع</label>
                                    <input type="text" required name="items[0][total]" class="form-control total"
                                        readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-primary my-2" onclick="addProduct()">إضافة منتج آخر</button>

            <div class="form-group">
                <label for="invoice_total">المجموع الكلي</label>
                <input type="text" id="invoice_total" class="form-control" readonly>
            </div>

            <button type="submit" class="btn btn-success my-2">إضافة فاتورة</button>
        </form>
    </div>
@endsection

@push('css')
    <style>
        .remove-product {
            position: absolute;
            left: 0;
            top: 0;
        }
    </style>
@endpush

@push('js')
    <script>
        function fetchWalletBalance() {
            const customerId = document.getElementById('customer_id').value;

            fetch(`/customers/${customerId}/wallet-balance`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('wallet_balance').value = data.balance;
                });
        }

        function fetchSubProducts(selectElement) {
            const productId = $(selectElement).val();
            const index = $(selectElement).attr('name').match(/\d+/)[0];

            $.ajax({
                url: `/products/${productId}/sub-products`,
                method: 'GET',
                success: function(data) {
                    const subProductSelect = $(`select[name="items[${index}][sub_product_id]"]`);
                    subProductSelect.empty().append('<option value="">اختر منتج فرعي</option>');

                    $.each(data, function(index, subProduct) {
                        subProductSelect.append(
                            `<option value="${subProduct.id}" data-quantity="${subProduct.quantity}">${subProduct.name} - كمية متاحة: ${subProduct.quantity}</option>`
                        );
                    });

                    // تعطيل حقل الكمية والسعر عند عدم اختيار منتج فرعي
                    const quantityInput = $(`input[name="items[${index}][quantity]"]`);
                    const priceInput = $(`input[name="items[${index}][price]"]`);

                    if (subProductSelect.val() === "") {
                        quantityInput.prop('disabled', true);
                        priceInput.prop('disabled', true);
                    } else {
                        quantityInput.prop('disabled', false);
                        priceInput.prop('disabled', false);
                    }
                }
            });

            // إضافة حدث للتأكد من تحديث الحقول عند تغيير المنتج الفرعي
            $(`select[name="items[${index}][sub_product_id]"]`).on('change', function() {
                const subProductId = $(this).val();
                const quantityInput = $(`input[name="items[${index}][quantity]"]`);
                const priceInput = $(`input[name="items[${index}][price]"]`);

                if (subProductId === "") {
                    quantityInput.prop('disabled', true);
                    priceInput.prop('disabled', true);
                } else {
                    quantityInput.prop('disabled', false);
                    priceInput.prop('disabled', false);
                }
            });
        }

        function updateTotal() {
            const productItems = document.querySelectorAll('.product-item');
            let invoiceTotal = 0;

            productItems.forEach(item => {
                const quantity = item.querySelector('input[name^="items"][name$="[quantity]"]').value;
                const price = item.querySelector('input[name^="items"][name$="[price]"]').value;
                const totalField = item.querySelector('input[name^="items"][name$="[total]"]');

                const total = (quantity * price) || 0;
                totalField.value = total.toFixed(2);
                invoiceTotal += parseFloat(total);
            });

            document.getElementById('invoice_total').value = invoiceTotal.toFixed(2);
        }

        function checkStockAvailability(input) {
            const productItem = $(input).closest('.product-item');
            const subProductSelect = productItem.find('select[name^="items"][name$="[sub_product_id]"]');
            const availableQuantity = subProductSelect.find('option:selected').data('quantity');
            const enteredQuantity = $(input).val();

            if (enteredQuantity > availableQuantity) {
                Swal.fire({
                    title: "خطأ",
                    text: `الكمية المطلوبة (${enteredQuantity}) أكبر من الكمية المتاحة (${availableQuantity}).`,
                    icon: "error"
                });

                $(input).val(''); // تعيين الكمية إلى الكمية المتاحة
            }
        }

        function addProduct() {
            const productList = document.getElementById('product-list');
            const index = productList.getElementsByClassName('product-item').length;

            const productItem = document.createElement('div');
            productItem.className = 'product-item';
            productItem.innerHTML = `
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <label for="product_id">اختيار المنتج الرئيسي</label>
                                <select name="items[${index}][product_id]" required class="form-control" onchange="fetchSubProducts(this)">
                                    <option value="">اختر منتج</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label for="sub_product_id[]">اختيار المنتج الفرعي</label>
                                <select name="items[${index}][sub_product_id]" required class="form-control" onchange="updateTotal()">
                                    <option value="">اختر منتج فرعي</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="quantity[]">الكمية</label>
                                <input type="number" name="items[${index}][quantity]" required class="form-control" oninput="updateTotal(); checkStockAvailability(this)" disabled>
                            </div>
                            <div class="col">
                                <label for="price[]">السعر</label>
                                <input type="text" name="items[${index}][price]" required class="form-control" oninput="updateTotal()" disabled>
                            </div>
                            <div class="col">
                                <label for="total[]">المجموع</label>
                                <input type="text" name="items[${index}][total]" required class="form-control total" readonly>
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger mt-2 remove-product" onclick="removeProduct(this)">إزالة</button>
                    </div>
                </div>
            `;
            productList.appendChild(productItem);
        }

        function removeProduct(button) {
            const productItem = button.closest('.product-item');
            productItem.remove();
            updateTotal();
        }

        $('#customer_id').select2({
            placeholder: "اختر عميل",
            allowClear: true
        });

        $(function() {
            // اضافة عميل جديد
            $('#addCustomerForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('admin.customers.store') }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        // إغلاق النافذة المنبثقة
                        $('.bs-example-modal-lg').modal('hide');
                        // إضافة العميل الجديد إلى قائمة العملاء
                        $('#customer_id').append(new Option(response.name, response.id));

                        // إظهار رسالة نجاح
                        Swal.fire({
                            title: "نجاح",
                            text: "تم إضافة العميل بنجاح.",
                            icon: "success"
                        });
                    },
                    error: function(xhr) {
                        // إظهار الأخطاء في الـ modal
                        var errors = xhr.responseJSON.errors;
                        var errorHtml = '<ul>';
                        $.each(errors, function(key, value) {
                            errorHtml += '<li class="text-danger">' + value[0] +
                            '</li>'; // عرض أول خطأ فقط لكل حقل
                        });
                        errorHtml += '</ul>';

                        $('#modalErrors').html(errorHtml).removeClass('d-none'); // عرض الأخطاء
                    }
                });
            });
        })
    </script>
@endpush
