<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu" class="mm-active">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Menu</li>

                <li class="{{ Request::is(route('admin.home') ? 'mm-active' : '') }}">
                    <a href="{{ route('admin.home') }}" class="waves-effect">
                        <i class="bx bx-home"></i>
                        <span key="t-chat">الصفحة الرئيسية</span>
                    </a>
                </li>

                

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-layout"></i>
                        <span key="t-dashboards">المنتج</span>
                    </a>
                    <ul class="sub-menu mm-collapse " aria-expanded="false">
                        <li><a href="{{ route('admin.products.index') }}" key="t-default">الاصناف</a></li>
                        <li><a href="{{ route('admin.sub-products.index') }}" key="t-saas">الانواع</a></li>
                    </ul>
                </li>


                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-user-check"></i>
                        <span key="t-dashboards">العملاء</span>
                    </a>
                    <ul class="sub-menu mm-collapse" aria-expanded="false">
                        <li><a href="{{ route('admin.customers.index') }}" key="t-default">قاعدة العملاء</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bxs-report"></i>
                        <span key="t-dashboards">الفواتير</span>
                    </a>
                    <ul class="sub-menu mm-collapse" aria-expanded="false">
                        <li><a href="{{ route('admin.invoices.index') }}" key="t-default">الفواتير</a></li>
                        <li><a href="{{ route('admin.invoices.create') }}" key="t-default">اضافة فاتورة</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bxs-user-rectangle"></i>
                        <span key="t-dashboards">شخصي</span>
                    </a>
                    <ul class="sub-menu mm-collapse" aria-expanded="false">
                        <li><a href="{{ route('admin.albunuds.index') }}" key="t-default">البنود</a></li>
                        <li><a href="{{ route('admin.daily-expense.index') }}" key="t-default">المدفوعات</a></li>
                    </ul>
                </li>

                


            
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
