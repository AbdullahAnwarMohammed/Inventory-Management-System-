



<div class="btn-group" >    
    <!-- زر تعديل المنتج الفرعي -->
    <a href="{{ route('admin.sub-products.edit', $subProduct->id) }}" class="btn btn-sm btn-success">
        <i class="bx bx-edit"></i> تعديل
    </a>



        <!-- زر حذف المنتج الفرعي -->
        <form action="{{ route('admin.sub-products.destroy', $subProduct->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من عملية الحذف؟');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="bx bx-trash-alt"></i> حذف
            </button>
        </form>

        <!-- زر إضافة كمية جديدة للمنتج الفرعي -->
        <a href="{{ route('admin.subProductsQuantity.create', $subProduct->id) }}" class="btn btn-info btn-sm">
            <i class="bx bx-plus"></i> إضافة كمية
        </a>

          <!-- زر إضافة كمية جديدة للمنتج الفرعي -->
          <a href="{{ route('admin.sub-products.show', $subProduct->id) }}" class="btn btn-dark btn-sm">
            <i class="bx bx-plus"></i> العمليات
        </a>
</div>
