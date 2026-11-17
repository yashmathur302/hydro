<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Static content pages (privacy policy, terms and conditions, ...).
 * Content is written in Markdown and rendered with raw HTML escaped.
 * The two legal pages are created as drafts so the admin only fills them in.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table): void {
            $table->id();
            $table->string('title', 150);
            $table->string('slug', 170)->unique();
            $table->longText('content')->nullable();
            $table->boolean('is_active')->default(false);
            $table->string('meta_title', 120)->nullable();
            $table->string('meta_description', 300)->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->timestamps();
        });

        $now = now();

        DB::table('pages')->insert([
            ['title' => 'Privacy Policy', 'slug' => 'privacy-policy', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Terms & Conditions', 'slug' => 'terms-and-conditions', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
