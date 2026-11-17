<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Sessions no longer record visitor IP addresses or browser strings
 * (App\Session\PrivateDatabaseSessionHandler); clear the ones already stored.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('sessions')->update(['ip_address' => null, 'user_agent' => null]);
    }

    public function down(): void
    {
        // Nothing to restore: the cleared values are intentionally gone.
    }
};
