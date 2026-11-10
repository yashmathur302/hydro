<x-admin.auth-layout title="Choose a new password" heading="New Password" intro="Use at least 12 characters with upper and lower case letters, a number and a symbol.">
    <form method="POST" action="{{ route('admin.password.store') }}" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="d-grid gap-3">
            <x-admin.form.input name="email" type="email" label="Email" :value="$email" required autocomplete="username" maxlength="191" />
            <x-admin.form.input name="password" type="password" label="New password" required autocomplete="new-password" autofocus />
            <x-admin.form.input name="password_confirmation" type="password" label="Confirm new password" required autocomplete="new-password" />
        </div>
        <div class="d-flex justify-content-center mt-4">
            <button type="submit" class="btn btn-primary px-5">Reset password</button>
        </div>
    </form>
</x-admin.auth-layout>
