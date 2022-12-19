<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }} </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="{{ asset('image/copy.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    {{--  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">  --}}
    <link rel="stylesheet" href="{{ asset('css/dataTables-bootstrap4.css') }}">
    {{--  <link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css">  --}}
    <link rel="stylesheet" href="{{ asset('css/dataTables-rowReorder.css') }}">
    {{--  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">  --}}
    <link rel="stylesheet" href="{{ asset('css/dataTables-Responsive.css') }}">
    {{--  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">  --}}
    <link rel="stylesheet" href="{{ asset('css/select2.css') }}">
    {{--  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">  --}}
    <link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">

    {{--  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css">  --}}
    <link rel="stylesheet" href="{{ asset('css/iCheck-bootstrap.css') }}">

    @yield('third_party_stylesheets')

    @stack('page_css')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Main Header -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ asset('image/copy.png') }}" class="user-image img-circle elevation-2"
                            alt="User Image">
                        <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <!-- User image -->
                        <li class="user-header bg-primary">
                            <img src="{{ asset('image/copy.png') }}" class="img-circle elevation-2" alt="User Image">
                            <p>
                                {{ Auth::user()->nama }}
                                <small>Member since {{ Auth::user()->created_at->format('M. Y') }}</small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <a href="#" class="btn btn-default btn-flat">Profile</a>
                            <a href="#" class="btn btn-default btn-flat float-right"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Sign out
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

        <!-- Left side column. contains the logo and sidebar -->
        @include('layouts.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <section class="content">
                @yield('content')
            </section>
        </div>

        <!-- Main Footer -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 1.0.0
            </div>
            <strong>Copyright &copy; 2022 <a href="https://www.instagram.com/rayid.media">Rayid Media</a>.</strong> All
            rights
            reserved.
        </footer>
    </div>

    <script src="{{ mix('js/app.js') }}" defer></script>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    {{--  <script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>  --}}
    <script src="{{ asset('js/dataTables.js') }}" defer></script>
    {{--  <script src = "https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js" defer ></script>  --}}
    <script src="{{ asset('js/dataTables-rowReorder.js') }}" defer></script>
    {{--  <script src = "https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js" defer ></script>  --}}
    <script src="{{ asset('js/dataTables-Responsive.js') }}" defer></script>
    {{--  <script src = "https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js" defer ></script>  --}}
    <script src="{{ asset('js/dataTables-Bootstrap.js') }}" defer></script>
    {{--  <script src = "https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js" ></script>  --}}
    <script src="{{ asset('js/select2.js') }}"></script>
    <script>
        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }

        $(document).on("click", ".alert-close", function() {
            $(".alert").hide();
        });
    </script>

    @yield('third_party_scripts')

    @stack('page_scripts')
</body>

</html>
