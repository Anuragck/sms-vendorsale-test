<!-- Sidebar -->
@php
$user = Auth::user();
@endphp
<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
    <router-link class="sidebar-brand d-flex align-items-center justify-content-center" to="/">
        {{-- <div class="sidebar-brand-icon">
        <img src="/assets/img/logo/logo.png">
      </div> --}}
        <div class="sidebar-brand-text mx-3">S M S</div>
    </router-link>
    <hr class="sidebar-divider my-0">
    <li class="nav-item active" href="">
        <router-link to="/" class="nav-link">

            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </router-link>
    </li>
    <hr class="sidebar-divider">

    <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMasters" aria-expanded="true"
            aria-controls="collapseForm">
            <i class="fab fa-fw fa-wpforms"></i>
            <span>Masters</span>
        </a>
        <div id="collapseMasters" class="collapse" aria-labelledby="headingForm" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Masters</h6>
                @if ($user->role == 3)
                    <router-link class="collapse-item" to="/users">Users</router-link>
                @endif
                <router-link class="collapse-item" to="/products">Products</router-link>
                <router-link class="collapse-item" to="/customers">Customers</router-link>
                @if ($user->jobcard)
                    <router-link class="collapse-item" to="/employees">Employees</router-link>
                @endif
                <router-link class="collapse-item" to="/expense-categories">ExpenseCategories</router-link>
                <router-link class="collapse-item" to="/income-categories">IncomeCategories</router-link>
                <router-link class="collapse-item" to="/vendors">Vendors</router-link>

            </div>
        </div>
    </li>

    <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOperations"
            aria-expanded="true" aria-controls="collapseTable">
            <i class="fas fa-fw fa-table"></i>
            <span>Operations</span>
        </a>
        <div id="collapseOperations" class="collapse" aria-labelledby="headingTable"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Operations</h6>
                <router-link class="collapse-item" to="/sales">Sales</router-link>
                <router-link class="collapse-item" to="/vendor-sales">Vendor Sales</router-link>
                <router-link class="collapse-item" to="/quotations">Quotations</router-link>
                <router-link class="collapse-item" to="/sales-return">Sale Returns</router-link>

                <router-link class="collapse-item" to="/purchase">Purchases</router-link>

                <router-link class="collapse-item" to="/purchase-orders">Purchase Orders</router-link>

                <router-link class="collapse-item" to="/expenses">Expenses</router-link>
                <router-link class="collapse-item" to="/incomes">Other Incomes</router-link>
                @if ($user->jobcard)
                    <router-link class="collapse-item" to="/jobcard">Jobcards</router-link>
                @endif
                @if ($user->reward)
                    <router-link class="collapse-item" to="/reward-sale">Redeem Points</router-link>
                @endif
                <router-link class="collapse-item" to="/stock-adjustments">Stock Adjustments</router-link>
                <router-link class="collapse-item" to="/closings">Day End (Closings)</router-link>
            </div>
        </div>
    </li>

    <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReports"
            aria-expanded="true" aria-controls="collapseTable">
            <i class="fas fa-fw fa-table"></i>
            <span>Reports</span>
        </a>
        <div id="collapseReports" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Reports</h6>

                <router-link class="collapse-item" to="/purchase-report">Purchase Report</router-link>
                <router-link class="collapse-item" to="/sales-report">Sales Report</router-link>
                <router-link class="collapse-item" to="/quotation-report">Quotation Report</router-link>
                <router-link class="collapse-item" to="/sales-return-report">Sales Return Report</router-link>

                <router-link class="collapse-item" to="/expense-report">Expense Report</router-link>
                <router-link class="collapse-item" to="/income-report">Income Report</router-link>
                <router-link class="collapse-item" to="/customer-credit-report">Credit Report</router-link>

                <router-link class="collapse-item" to="/purchase-credit-report">Purchase Credit Report</router-link>

                <router-link class="collapse-item" to="/payment-report">Receipt Report</router-link>


                <router-link class="collapse-item" to="/stock-report">Stock Report</router-link>


                <router-link class="collapse-item" to="/item-report">Item Report</router-link>
                @if ($user->jobcard)
                    <router-link class="collapse-item" to="/jobcard-report">Jobcard Report</router-link>
                @endif
                <router-link class="collapse-item" to="/closing-report">Closing Report</router-link>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">

</ul>
<!-- Sidebar -->
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">

        <!-- TopBar -->
        <nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4 static-top">
            <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>


            <ul class="navbar-nav ml-auto">


                <div class="ml-auto mt-1">

                    <navbar-search />
                </div>


                <div class="topbar-divider d-none d-sm-block">


                </div>

                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user-circle"></i>
                        <span class="ml-2 d-none d-lg-inline text-white small">{{ Auth::user()->name }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="#passwordChangeModal" data-toggle="modal"
                            data-target="#passwordChangeModal">
                            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                            Change Password
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>

        </nav>

        <!-- Topbar -->
