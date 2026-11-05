<x-admin.auth-layout title="Choose a new password" heading="Choose a new password" intro="Use at least 12 characters with upper and lower case letters, a number and a symbol.">
    <form method="POST" action="{{ route('admin.password.store') }}" class="form-stack" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <x-admin.form.input name="email" type="email" label="Email address" :value="$email" required autocomplete="username" maxlength="191" />
        <x-admin.form.input name="password" type="password" label="New password" required autocomplete="new-password" autofocus />
        <x-admin.form.input name="password_confirmation" type="password" label="Confirm new password" required autocomplete="new-password" />

        <x-admin.button type="submit" block>Reset password</x-admin.button>
    </form>
</x-admin.auth-layout>
