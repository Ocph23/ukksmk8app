<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Purple Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="assets/images/favicon.ico" />
</head>

<body ng-app="app">
    <div style="height: 100vh; display: flex; flex-direction:column; ">
        @if (Route::has('login'))
        <div class="m-5">
            @auth
            <a style="font-size: 20px;" class="nav-link text-right" href="{{ url('admin') }}">Admin</a>
            @else
            <a style="font-size: 20px;" class="nav-link text-right" href="{{ route('login') }}">Log in</a>

            @if (Route::has('register'))
            <a href="{{ route('register') }}">Register</a>
            @endif
            @endauth
        </div>
        @endif

        <div>
            <div style="display:flex; justify-content: center; ALIGN-ITEMS:CENTER; flex-direction:column">
                <img style="width: 10%;" src="/assets/images/smk8logotransparant.png">
                <h1 class="mt-5">SISTEM INFORMASI </h1>
                <h1>UJI KOMPETENSI KEAHLIAN </h1>

            </div>
        </div>

        <div>
            <div style="align-items:self-end; display:flex; justify-content: center; ALIGN-ITEMS:CENTER; flex-direction:column">
                <h4 style="margin-top: 100px;">SMK NEGERI 8 TEKNOLOGI INFORMASI DAN KOMUNIKASI</h4>
                <h4>JAYAPURA</h4>
                <h6>@2024 - Ocph23</h6>
            </div>
        </div>
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{asset('assets/vendors/js/vendor.bundle.base.js')}} "></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{asset('assets/js/jquery.cookie.js')}}"></script>
    <script src="{{asset('assets/vendors/chart.js/Chart.min.js')}}" type="text/javascript"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{asset('assets/js/off-canvas.js')}}"></script>
    <script src="{{asset('assets/js/hoverable-collapse.js')}}"></script>
    <script src="{{asset('assets/js/misc.js')}}"></script>

    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{asset('assets/js/dashboard.js')}}"></script>
    <script src="{{asset('assets/js/todolist.js')}}"></script>


    <script src="{{asset('assets/js/angular.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-filter/0.5.17/angular-filter.min.js" integrity="sha512-f2q5tYQJ0pnslHkuVw7tm7GP7E0BF1YLckJjgLU5z4p1vNz78Jv+nPIEKtZerevbt/HwEfYnRrAo9U3u4m0UHw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{asset('assets/js/apps/app.js')}}"></script>

    <script src="{{asset('assets/js/apps/services/service.js')}}"></script>
    <script src="{{asset('assets/js/apps/services/helperService.js')}}"></script>
    <script src="{{asset('assets/js/apps/services/jurusanService.js')}}"></script>
    <script src="{{asset('assets/js/apps/services/tahunajaranService.js')}}"></script>
    <script src="{{asset('assets/js/apps/services/aksesorService.js')}}"></script>
    <script src="{{asset('assets/js/apps/services/paketService.js')}}"></script>
    <script src="{{asset('assets/js/apps/services/siswaService.js')}}"></script>
    <script src="{{asset('assets/js/apps/services/penilaianService.js')}}"></script>


    <script src="{{asset('assets/js/apps/controllers/controller.js')}}"></script>
    <script src="{{asset('assets/js/apps/controllers/jurusanController.js')}}"></script>
    <script src="{{asset('assets/js/apps/controllers/tahunajaranController.js')}}"></script>
    <script src="{{asset('assets/js/apps/controllers/aksesorController.js')}}"></script>
    <script src="{{asset('assets/js/apps/controllers/paketController.js')}}"></script>
    <script src="{{asset('assets/js/apps/controllers/siswaController.js')}}"></script>
    <script src="{{asset('assets/js/apps/controllers/paketDetailController.js')}}"></script>
    <script src="{{asset('assets/js/apps/controllers/siswaDetailController.js')}}"></script>
    <!-- End custom js for this page -->
</body>

</html>