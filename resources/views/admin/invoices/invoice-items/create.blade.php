@extends('admin.layout.app')
@section('title')

@endsection
@section('content')
    <div class="container">




        <form action="{{ route('admin.invoiceItem.store') }}" method="POST">
            @csrf
            <div class="row">

                <div class="col">

                    <label for="customer_id">رقم الفاورة</label>
                    <input type="hidden" name="invoice_id" value="{{$InvoiceItem->id}}">
                    <input type="text" readonly value="{{ $InvoiceItem->invoice_number }}" class="form-control">

                </div>

                <div class="col">
                    <label for="customer_id">العميل</label>
                    <select id="customer_id"  name="customer_id" class="form-control"
                        onchange="fetchWalletBalance()">
                        <option value="{{$InvoiceItem->customer->id}}">{{ $InvoiceItem->customer->name }}</option>

                    </select>
                    @error('customer_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>


            </div>


            <div class="form-group">
                <label for="wallet_balance">الرصيد الحالي</label>
                <input type="text" id="wallet_balance" value="{{ $InvoiceItem->customer->wallet->balance }}"
                    class="form-control" readonly>
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
                                        @foreach (\App\Models\Product::all() as $product)
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
                                    <input type="number" required name="items[0][quantity]" class="form-control"
                                        oninput="updateTotal()">
                                    @error('items.0.quantity')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label for="price[]">السعر</label>
                                    <input type="text" required name="items[0][price]" class="form-control"
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
            const productId = selectElement.value;
            const index = selectElement.name.match(/\d+/)[0];

            fetch(`/products/${productId}/sub-products`)
                .then(response => response.json())
                .then(data => {
                    const subProductSelect = document.querySelector(`select[name="items[${index}][sub_product_id]"]`);
                    subProductSelect.innerHTML = '<option value="">اختر منتج فرعي</option>';

                    data.forEach(subProduct => {
                        subProductSelect.innerHTML +=
                            `<option value="${subProduct.id}">${subProduct.name}</option>`;
                    });
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
                            @foreach (\App\Models\Product::all() as $product)
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
                        <input type="number" name="items[${index}][quantity]" required  class="form-control" oninput="updateTotal()">
                    </div>
                    <div class="col">
                        <label for="price[]">السعر</label>
                        <input type="text" name="items[${index}][price]" required  class="form-control" oninput="updateTotal()">
                    </div>
                    <div class="col">
                        <label for="total[]">المجموع</label>
                        <input type="text" name="items[${index}][total]" required  class="form-control total" readonly>
                    </div>
                </div>
                <button type="button" class="btn btn-danger mt-2 remove-product" onclick="removeProduct(this)">حذف</button>
            </div>
        </div>
    `;
            productList.appendChild(productItem);
        }

        function removeProduct(button) {
            const productItem = button.closest('.product-item');
            productItem.remove();
            updateTotal(); // تحديث المجموع الكلي بعد الحذف
        }
    </script>
@endpush
