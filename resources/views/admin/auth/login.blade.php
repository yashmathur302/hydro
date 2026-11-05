<x-admin.auth-layout title="Log in" heading="Welcome back" intro="Log in to manage your website.">
    <form method="POST" action="{{ route('admin.login.store') }}" class="form-stack" novalidate>
        @csrf

        <x-admin.form.input name="email" type="email" label="Email address" required autocomplete="username" autofocus maxlength="191" />
        <x-admin.form.input name="password" type="password" label="Password" required autocomplete="current-password" />

        <div class="form-row form-row--end">
            <a class="link" href="{{ route('admin.password.request') }}">Forgot password?</a>
        </div>

        <x-admin.button type="submit" icon="arrow-right" block>Log in</x-admin.button>
    </form>
</x-admin.auth-layout>
