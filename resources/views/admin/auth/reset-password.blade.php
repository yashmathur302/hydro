<x-admin.auth-layout title="Choose a new password" heading="Choose a new password" intro="Use at least 12 characters with upper and lower case letters, a number and a symbol.">
    <form class="auth__fields" method="POST" action="{{ route('admin.password.store') }}" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <x-admin.form.input name="email" type="email" label="Email" :value="$email" required autocomplete="username" maxlength="191" />
        <x-admin.form.input name="password" type="password" label="New password" required autocomplete="new-password" autofocus />
        <x-admin.form.input name="password_confirmation" type="password" label="Confirm new password" required autocomplete="new-password" />
        <button type="submit" class="btn btn-primary w-100 auth__submit">Reset password</button>
    </form>
</x-admin.auth-layout>
