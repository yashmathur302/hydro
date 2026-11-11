<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Editable content of fixed page sections (home banner, home about block ...).
 * One row per page + section; the fields live in a JSON "content" column and
 * are defined and validated by App\Enums\HomeSection.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_sections', function (Blueprint $table): void {
            $table->id();
            $table->string('page', 50);
            $table->string('section', 50);
            $table->json('content');
            $table->timestamps();

            $table->unique(['page', 'section']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_sections');
    }
};
