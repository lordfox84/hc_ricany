<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camp_id')->constrained()->cascadeOnDelete();

            // Údaje rodiče / zákonného zástupce
            $table->string('parent_name');
            $table->string('parent_email');
            $table->string('parent_phone')->nullable();

            // Údaje dítěte
            $table->string('child_name');
            $table->unsignedTinyInteger('child_age')->nullable();
            $table->string('note')->nullable();           // poznámka (alergie apod.)

            // GDPR — audit trail
            $table->boolean('gdpr_consent')->default(false);      // povinný souhlas se zpracováním
            $table->boolean('marketing_consent')->default(false);  // volitelný souhlas pro cross-selling
            $table->timestamp('consented_at')->nullable();         // kdy byl souhlas udělen
            $table->string('ip_address', 45)->nullable();          // IPv4 i IPv6

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
