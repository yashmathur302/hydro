<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

/**
 * Creates the single admin account. The site supports one admin only, so the
 * command refuses to run once an admin exists.
 */
final class CreateAdminCommand extends Command
{
    protected $signature = 'admin:create';

    protected $description = 'Create the admin account for the admin panel';

    public function handle(): int
    {
        if (Admin::query()->exists()) {
            $this->error('An admin account already exists. Use "Forgot password" on the login page to reset it.');

            return self::FAILURE;
        }

        $name = text(label: 'Name', required: true, validate: ['name' => 'max:100']);
        $email = text(label: 'Email address', required: true, validate: ['email' => 'email|max:191']);
        $password = password(label: 'Password', required: true, hint: 'At least 12 characters with upper and lower case letters, a number and a symbol.');
        $passwordConfirmation = password(label: 'Confirm password', required: true);

        $validator = Validator::make(
            ['password' => $password, 'password_confirmation' => $passwordConfirmation],
            ['password' => ['confirmed', Password::defaults()]],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }

            return self::FAILURE;
        }

        Admin::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        $this->info("Admin account created for {$email}.");

        return self::SUCCESS;
    }
}
