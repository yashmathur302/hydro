{{-- Sign-in style pages (login, forgot / reset password) in the Hope UI split layout. --}}
@props(['title', 'heading', 'intro' => null])

<!DOCTYPE html>
<html lang="en">
<head>
    <x-admin.head :title="$title" />
</head>
<body>
    <main class="wrapper">
        <section class="login-content">
            <div class="row m-0 align-items-center bg-white vh-100">
                <div class="col-md-6">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="card card-transparent shadow-none d-flex justify-content-center mb-0 auth-card">
                                <div class="card-body">
                                    <x-admin.brand :href="route('admin.login')" class="d-flex align-items-center mb-3 auth-brand" />
                                    <h1 class="h2 mb-2 text-center">{{ $heading }}</h1>
                                    @if ($intro)
                                        <p class="text-center">{{ $intro }}</p>
                                    @endif

                                    <x-admin.flash />

                                    {{ $slot }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sign-bg" aria-hidden="true">
                        <svg width="280" height="230" viewBox="0 0 431 398" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g opacity="0.05">
                                <rect x="-157.085" y="193.773" width="543" height="77.5714" rx="38.7857" transform="rotate(-45 -157.085 193.773)" fill="#3B8AFF"/>
                                <rect x="7.46875" y="358.327" width="543" height="77.5714" rx="38.7857" transform="rotate(-45 7.46875 358.327)" fill="#3B8AFF"/>
                                <rect x="61.9355" y="138.545" width="310.286" height="77.5714" rx="38.7857" transform="rotate(45 61.9355 138.545)" fill="#3B8AFF"/>
                                <rect x="62.3154" y="-190.173" width="543" height="77.5714" rx="38.7857" transform="rotate(45 62.3154 -190.173)" fill="#3B8AFF"/>
                            </g>
                        </svg>
                    </div>
                </div>
                <div class="col-md-6 d-md-block d-none bg-primary p-0 vh-100 overflow-hidden" aria-hidden="true">
                    <img src="{{ Vite::asset('resources/images/admin/auth-side.webp') }}" class="img-fluid gradient-main auth-side" alt="">
                </div>
            </div>
        </section>
    </main>
</body>
</html>
