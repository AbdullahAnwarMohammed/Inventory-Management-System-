<!-- actions.blade.php -->
<div class="d-inline-flex">
    <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn btn-info btn-sm mx-1">
        <i class="bx bx-show"></i> عرض
    </a>
    {{-- <a href="{{ route('admin.invoices.edit', $invoice->id) }}" class="btn btn-success btn-sm mx-1">
        <i class="bx bx-edit"></i> تعديل
    </a> --}}
    {{-- <form action="{{ route('admin.invoices.destroy', $invoice->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm mx-1">
            <i class="bx bx-trash-alt"></i> حذف
        </button>
    </form> --}}
</div>
