<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line breadcrumb-line-light header-elements-lg-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
                <span class="breadcrumb-item active">@yield('header')</span>
            </div>

            <a href="#" class="header-elements-toggle text-body d-lg-none"><i class="icon-more"></i></a>
        </div>

        <div class="header-elements d-none">
            {{ Auth::user()->email.' / '.Auth::user()->name }}
            {{-- <div class="breadcrumb justify-content-center">
                <a href="#" class="breadcrumb-elements-item">
                    <i class="icon-comment-discussion mr-2"></i>
                    Support
                </a>

                <div class="breadcrumb-elements-item dropdown p-0">
                    <a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-gear mr-2"></i>
                        Settings
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item"><i class="icon-user-lock"></i> Account security</a>
                        <a href="#" class="dropdown-item"><i class="icon-statistics"></i> Analytics</a>
                        <a href="#" class="dropdown-item"><i class="icon-accessibility"></i> Accessibility</a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item"><i class="icon-gear"></i> All settings</a>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>

    <div class="page-header-content header-elements-lg-inline">
        <br>
        {{-- <div class="page-title d-flex">
            <h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Home</span> - @yield('header')</h4>
            <a href="#" class="header-elements-toggle text-body d-lg-none"><i class="icon-more"></i></a>
        </div> --}}

        {{-- <div class="header-elements d-none mb-3 mb-lg-0">
            <div class="d-flex justify-content-center">
                <a href="#" class="btn btn-link btn-float text-body"><i class="icon-bars-alt text-indigo"></i> <span>Statistics</span></a>
                <a href="#" class="btn btn-link btn-float text-body"><i class="icon-calculator text-indigo"></i> <span>Invoices</span></a>
                <a href="#" class="btn btn-link btn-float text-body"><i class="icon-calendar5 text-indigo"></i> <span>Schedule</span></a>
            </div>
        </div> --}}
    </div>
</div>
<!-- /page header -->