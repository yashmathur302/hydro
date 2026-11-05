<x-admin.layout title="My account">
    <x-admin.page-header title="My account" description="Your login details for the admin panel." />

    <div class="stack stack--narrow">
        <form method="POST" action="{{ route('admin.account.profile.update') }}" novalidate>
            @csrf
            @method('PUT')

            <x-admin.card title="Profile">
                <x-admin.error-summary />
                <div class="form-grid">
                    <x-admin.form.input name="name" label="Name" :value="$admin->name" required maxlength="100" autocomplete="name" />
                    <x-admin.form.input name="email" type="email" label="Email address" :value="$admin->email" required maxlength="191" autocomplete="username" hint="Used to log in and to receive password reset links." />
                </div>

                <x-slot:footer>
                    <x-admin.button type="submit">Save profile</x-admin.button>
                </x-slot:footer>
            </x-admin.card>
        </form>

        <form method="POST" action="{{ route('admin.account.password.update') }}" novalidate>
            @csrf
            @method('PUT')

            <x-admin.card title="Change password" description="At least 12 characters with upper and lower case letters, a number and a symbol. Other signed-in browsers will be logged out.">
                <x-admin.error-summary bag="updatePassword" />
                <div class="form-stack">
                    <x-admin.form.input name="current_password" type="password" label="Current password" bag="updatePassword" required autocomplete="current-password" />
                    <x-admin.form.input name="password" type="password" label="New password" bag="updatePassword" required autocomplete="new-password" />
                    <x-admin.form.input name="password_confirmation" type="password" label="Confirm new password" bag="updatePassword" required autocomplete="new-password" />
                </div>

                <x-slot:footer>
                    <x-admin.button type="submit" icon="lock">Change password</x-admin.button>
                </x-slot:footer>
            </x-admin.card>
        </form>
    </div>
</x-admin.layout>
