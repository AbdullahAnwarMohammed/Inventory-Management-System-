<a href="{{route('admin.daily-expense.edit',$row->id)}}" class="btn btn-success btn-sm">تعديل</a>
<form onclick="return confirm('سوف تقوم بعملية الحذف')" class="d-inline" action="{{route('admin.daily-expense.destroy',$row->id)}}" method="POST" >
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-sm">حذف</button>
</form>
<a href="{{route('admin.daily-expense.show',$row->id)}}" class="btn btn-dark btn-sm"><i class="mdi  mdi-eye"></i> عرض</a>
