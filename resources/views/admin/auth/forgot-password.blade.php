<x-admin.auth-layout title="Forgot password" heading="Reset Password" intro="Enter the admin email address and we will send you a link to choose a new password.">
    <form method="POST" action="{{ route('admin.password.email') }}" novalidate>
        @csrf
        <x-admin.form.input name="email" type="email" label="Email" required autocomplete="username" autofocus maxlength="191" />
        <div class="d-flex justify-content-center mt-4">
            <button type="submit" class="btn btn-primary px-5">Send reset link</button>
        </div>
        <p class="text-center mt-3 mb-0"><a href="{{ route('admin.login') }}">Back to sign in</a></p>
    </form>
</x-admin.auth-layout>
