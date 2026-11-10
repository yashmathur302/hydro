<x-admin.auth-layout title="Forgot password" heading="Reset your password" intro="Enter the admin email address and we will send you a link to choose a new password.">
    <form class="auth__fields" method="POST" action="{{ route('admin.password.email') }}" novalidate>
        @csrf
        <x-admin.form.input name="email" type="email" label="Email" required autocomplete="username" autofocus maxlength="191" />
        <button type="submit" class="btn btn-primary w-100 auth__submit">Send reset link</button>
        <p class="auth__back"><a href="{{ route('admin.login') }}">Back to sign in</a></p>
    </form>
</x-admin.auth-layout>
