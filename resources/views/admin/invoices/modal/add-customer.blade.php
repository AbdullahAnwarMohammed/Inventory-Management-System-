   <!--  Large modal example -->
   <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">اضافة عميل جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modalErrors"></div> <!-- مكان عرض الأخطاء -->

                <form id="addCustomerForm">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <label for="customer_name">اسم العميل</label>
                            <input type="text" class="form-control" id="name" required name="name" required>
    
                        </div>
                        <div class="col">
                            <label for="customer_email">البريد الإلكتروني</label>
                            <input type="email" class="form-control" id="email" name="email" >
    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="customer_phone">رقم الهاتف</label>
                            <input type="text" class="form-control" id="phone" name="phone" >
    
                        </div>
                        <div class="col">
                            <label for="customer_phone">العنوان</label>
                            <input type="text" class="form-control" id="address" name="address" >
    
                        </div>
                    </div>


                    <input type="hidden" name="modal" value="modal">
                    <button type="submit" class="btn btn-success my-2">إضافة</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
