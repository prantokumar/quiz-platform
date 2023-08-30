<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ env('APP_NAME') }} - @yield('title')</title>
    <meta charset="utf-8" />
    <link rel="icon" type="image/x-icon" href="{{ asset('images/hamko-logo.png') }}">
    {{-- SEO --}}
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="" />
    <meta property="og:url" content="" />
    <meta property="og:site_name" content="" />
    {{-- SEO --}}

    {{-- global csrf token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- global csrf token --}}

    {{-- custom local css cdn area start --}}
    @include('backend.global.cdncss')
    {{-- custom local css cdn area end --}}

    {{-- global css area start --}}
    @yield('custom_stylesheet')
    {{-- global css area end --}}
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        @include('backend.layouts.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('backend.layouts.topbar')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                @yield('content')
                <!-- End Page Content -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            @include('backend.layouts.footer')
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="{{ url('/admin/logout') }}">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Logout Modal End-->
    {{-- custom local js cdn area start --}}
    @include('backend.global.cdnjs')
    {{-- custom local js cdn area end --}}

    {{-- global js area start --}}
    @yield('custom_scripts')
    {{-- global js area end --}}
</body>

</html>
