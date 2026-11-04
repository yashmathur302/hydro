<x-admin.auth-layout title="Forgot password" heading="Reset your password" intro="Enter the admin email address and we will send you a link to choose a new password.">
    <form method="POST" action="{{ route('admin.password.email') }}" class="form-stack" novalidate>
        @csrf

        <x-admin.form.input name="email" type="email" label="Email address" required autocomplete="username" autofocus maxlength="191" />

        <x-admin.button type="submit" block>Send reset link</x-admin.button>

        <p class="form-row form-row--center">
            <a class="link" href="{{ route('admin.login') }}">Back to log in</a>
        </p>
    </form>
</x-admin.auth-layout>
