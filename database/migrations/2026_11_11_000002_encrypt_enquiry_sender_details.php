<?php

declare(strict_types=1);

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The visitor's IP address and browser on enquiries and messages are stored
 * encrypted (App\Models\Enquiry casts). Encrypted values are longer, so the
 * columns become TEXT, and values saved before this change are encrypted.
 */
return new class extends Migration
{
    private const COLUMNS = ['ip_address', 'user_agent'];

    public function up(): void
    {
        Schema::table('enquiries', function (Blueprint $table): void {
            $table->text('ip_address')->nullable()->change();
            $table->text('user_agent')->nullable()->change();
        });

        $this->rewrite(static fn (string $value): string => self::isEncrypted($value) ? $value : Crypt::encryptString($value));
    }

    public function down(): void
    {
        $this->rewrite(static fn (string $value): string => self::isEncrypted($value) ? Crypt::decryptString($value) : $value);

        Schema::table('enquiries', function (Blueprint $table): void {
            $table->string('ip_address', 45)->nullable()->change();
            $table->string('user_agent', 255)->nullable()->change();
        });
    }

    private function rewrite(Closure $transform): void
    {
        DB::table('enquiries')->select(['id', ...self::COLUMNS])->orderBy('id')->each(function (object $row) use ($transform): void {
            $changes = [];

            foreach (self::COLUMNS as $column) {
                if ($row->{$column} !== null) {
                    $changes[$column] = $transform((string) $row->{$column});
                }
            }

            if ($changes !== []) {
                DB::table('enquiries')->where('id', $row->id)->update($changes);
            }
        });
    }

    private static function isEncrypted(string $value): bool
    {
        try {
            Crypt::decryptString($value);

            return true;
        } catch (DecryptException) {
            return false;
        }
    }
};
