<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Přidat varianty ke kempům
        Schema::table('camps', function (Blueprint $table) {
            $table->json('variants')->nullable()->after('capacity');
        });

        // Odstranit child_age (nahrazeno child_date_of_birth) — separátní volání kvůli SQLite
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn('child_age');
        });

        // Přidat nová pole do registrací
        Schema::table('registrations', function (Blueprint $table) {
            $table->string('parent_last_name', 100)->nullable()->after('parent_name');
            $table->string('child_last_name', 100)->nullable()->after('child_name');
            $table->date('child_date_of_birth')->nullable()->after('child_last_name');
            $table->string('street', 200)->nullable()->after('note');
            $table->string('city', 100)->nullable()->after('street');
            $table->string('zip', 20)->nullable()->after('city');
            $table->string('country', 100)->nullable()->after('zip');
            $table->string('club', 200)->nullable()->after('country');
            $table->string('position', 20)->nullable()->after('club');   // útočník / obránce / brankář
            $table->string('jersey_size', 10)->nullable()->after('position');
            $table->string('jersey_size_custom', 50)->nullable()->after('jersey_size');
            $table->string('variant', 300)->nullable()->after('jersey_size_custom');
        });
    }

    public function down(): void
    {
        Schema::table('camps', function (Blueprint $table) {
            $table->dropColumn('variants');
        });

        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn([
                'parent_last_name', 'child_last_name', 'child_date_of_birth',
                'street', 'city', 'zip', 'country', 'club',
                'position', 'jersey_size', 'jersey_size_custom', 'variant',
            ]);
        });

        Schema::table('registrations', function (Blueprint $table) {
            $table->unsignedTinyInteger('child_age')->nullable();
        });
    }
};
