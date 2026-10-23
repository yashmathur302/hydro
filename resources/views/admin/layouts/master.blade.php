<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Semi-Ecommerce Admin Panel</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts and icons -->
    <script src="{{ asset('admin_assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["{{ asset('admin_assets/css/fonts.min.css') }}"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('admin_assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin_assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin_assets/css/kaiadmin.min.css') }}" />

    {{-- @vite('resources/css/app.css') --}}
    
    <!-- Custom CSS page ke hisaab se -->
    @stack('styles')
</head>
<body>
    <div class="wrapper">
        
        <!-- Sidebar Include -->
        @include('admin.layouts.sidebar')

        <div class="main-panel">
            <div class="main-header">
                <!-- Header Include -->
                @include('admin.layouts.header')
            </div>

            <div class="container">
                <div class="page-inner">
                    <!-- Yahan har page ka dynamic content aayega -->
                    @yield('content')
                </div>
            </div>

            <!-- Footer Include -->
            @include('admin.layouts.footer')
        </div>
    </div>

    <!-- Core JS Files -->
    <script src="{{ asset('admin_assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/core/bootstrap.min.js') }}"></script>
    <!-- Kaiadmin JS -->
    <script src="{{ asset('admin_assets/js/kaiadmin.min.js') }}"></script>
    
    <!-- Custom JS page ke hisaab se -->
    @stack('scripts')
</body>
</html>