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
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

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

    <!-- Global Plugins (SweetAlert, Summernote, jQuery UI) -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Global Scripts Configuration -->
    <script>
        // SweetAlert Toast Setup
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
        });

        // Laravel Flash Messages to SweetAlert
        @if(session('success'))
            Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
        @endif

        @if(session('error'))
            Toast.fire({ icon: 'error', title: "{{ session('error') }}" });
        @endif

        // Validation Errors to SweetAlert
        @if($errors->any())
            let errorHtml = '<ul style="text-align: left; margin: 0; padding-left: 20px;">';
            @foreach($errors->all() as $error)
                errorHtml += '<li>{{ $error }}</li>';
            @endforeach
            errorHtml += '</ul>';
            
            Toast.fire({ icon: 'error', title: 'Validation Failed', html: errorHtml });
        @endif

        // Global Summernote Initialization
        $(document).ready(function() {
            $('.summernote-editor').summernote({
                placeholder: 'Write content here...',
                tabsize: 2,
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });

        // Global Delete Confirmation Logic
        $(document).on('click', '.global-delete-btn', function(e) {
            e.preventDefault(); // Form ko direct submit hone se rokna
            
            let form = $(this).closest('form'); // Button ke parent form ko find karna

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this data!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Agar user Yes par click karta hai, toh form submit kardo
                    form.submit();
                }
            });
        });
    </script>
    
    <!-- Custom JS page ke hisaab se -->
    @stack('scripts')
</body>
</html>