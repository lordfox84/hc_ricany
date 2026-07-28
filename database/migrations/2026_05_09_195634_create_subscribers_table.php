<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email', 255)->unique();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('locale', 5)->default('cs');
            // source: odkud přišel — 'newsletter' nebo 'camp'
            $table->string('source', 20)->default('newsletter');
            $table->boolean('gdpr_consent')->default(false);
            $table->boolean('marketing_consent')->default(true);
            $table->timestamp('consented_at')->nullable();
            // Odhlášení z marketingu (bez smazání záznamu)
            $table->timestamp('unsubscribed_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
