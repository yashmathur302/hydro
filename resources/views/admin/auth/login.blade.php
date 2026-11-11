<x-admin.auth-layout title="Log in" heading="Sign in" intro="Enter your admin email and password to manage the website.">
    <form class="auth__fields" method="POST" action="{{ route('admin.login.store') }}" novalidate>
        @csrf
        <x-admin.form.input name="email" type="email" label="Email" required autocomplete="username" autofocus maxlength="191" />
        <div>
            <x-admin.form.input name="password" type="password" label="Password" required autocomplete="current-password" />
            <div class="auth__aside-link">
                <a href="{{ route('admin.password.request') }}">Forgot password?</a>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100 auth__submit">Sign in</button>
    </form>
</x-admin.auth-layout>
