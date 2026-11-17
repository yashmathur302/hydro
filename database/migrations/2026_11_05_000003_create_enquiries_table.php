<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Messages sent from the website: product enquiries and contact-form messages
 * (told apart by "type"). The product name is copied at submission time so
 * the enquiry still makes sense if the product is renamed or deleted.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enquiries', function (Blueprint $table): void {
            $table->id();
            $table->string('type', 30);
            $table->string('status', 30)->default('new');
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name', 150)->nullable();
            $table->string('name', 100);
            $table->string('email', 191)->nullable();
            $table->string('phone', 30);
            $table->string('company', 150)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('subject', 150)->nullable();
            $table->text('message')->nullable();
            $table->text('admin_notes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['type', 'status', 'created_at']);
            $table->index(['type', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};
