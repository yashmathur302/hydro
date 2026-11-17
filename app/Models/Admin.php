<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\AdminFactory;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * The single administrator who manages the website content.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $last_login_at
 */
class Admin extends Authenticatable
{
    /** @use HasFactory<AdminFactory> */
    use HasFactory;

    use Notifiable;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Send the reset link to the admin's password reset route instead of the
     * framework's default "password.reset" route name.
     */
    public function sendPasswordResetNotification(#[\SensitiveParameter] $token): void
    {
        ResetPassword::createUrlUsing(
            static fn (self $admin, string $resetToken): string => route('admin.password.reset', [
                'token' => $resetToken,
                'email' => $admin->email,
            ])
        );

        $this->notify(new ResetPassword($token));
    }
}
