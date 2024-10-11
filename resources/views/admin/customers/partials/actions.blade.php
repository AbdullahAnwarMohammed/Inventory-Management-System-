<div class="btn-group" role="group">
    <!-- زر تعديل العميل -->
    <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-success btn-sm" title="تعديل">
        <i class="bx bx-edit"></i> تعديل
    </a>


    <!-- زر حذف العميل -->
    <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من أنك تريد حذف هذا العميل؟')">
            <i class="bx bx-trash-alt"></i> حذف
        </button>
    </form>

        <!-- زر عرض المحفظة -->
        <a href="{{ route('admin.wallet.home', $customer->id) }}" class="btn btn-dark btn-sm" title="عرض المحفظة">
            <i class="bx bx-wallet-alt"></i> المحفظة
        </a>
    
        <a href="{{ route('admin.transaction.find', $customer->id) }}" class="btn btn-info btn-sm" title="عرض المحفظة">
            <i class="bx bx-wallet-alt"></i> العمليات
        </a>

</div>
