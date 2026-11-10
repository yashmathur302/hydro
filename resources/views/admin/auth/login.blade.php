<x-admin.auth-layout title="Log in" heading="Sign In" intro="Log in to manage your website.">
    <form method="POST" action="{{ route('admin.login.store') }}" novalidate>
        @csrf
        <div class="d-grid gap-3">
            <x-admin.form.input name="email" type="email" label="Email" required autocomplete="username" autofocus maxlength="191" />
            <x-admin.form.input name="password" type="password" label="Password" required autocomplete="current-password" />
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.password.request') }}">Forgot Password?</a>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-3">
            <button type="submit" class="btn btn-primary px-5">Sign In</button>
        </div>
    </form>
</x-admin.auth-layout>
