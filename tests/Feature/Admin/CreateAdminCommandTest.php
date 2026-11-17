<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CreateAdminCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_the_admin(): void
    {
        $this->artisan('admin:create')
            ->expectsQuestion('Name', 'Site Owner')
            ->expectsQuestion('Email address', 'owner@example.com')
            ->expectsQuestion('Password', 'Str0ng!Passw0rd#2026')
            ->expectsQuestion('Confirm password', 'Str0ng!Passw0rd#2026')
            ->assertSuccessful();

        $this->assertDatabaseHas('admins', ['email' => 'owner@example.com']);
    }

    public function test_it_rejects_a_weak_password(): void
    {
        $this->artisan('admin:create')
            ->expectsQuestion('Name', 'Site Owner')
            ->expectsQuestion('Email address', 'owner@example.com')
            ->expectsQuestion('Password', 'password')
            ->expectsQuestion('Confirm password', 'password')
            ->assertFailed();

        $this->assertDatabaseCount('admins', 0);
    }

    public function test_it_refuses_to_create_a_second_admin(): void
    {
        Admin::factory()->create();

        $this->artisan('admin:create')->assertFailed();

        $this->assertDatabaseCount('admins', 1);
    }
}
