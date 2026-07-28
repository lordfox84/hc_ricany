<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('camps', function (Blueprint $table) {
            $table->string('type')->default('letni')->after('user_id'); // letni / skills
            $table->boolean('registration_open')->default(false)->after('is_published');
            $table->unsignedSmallInteger('capacity')->nullable()->after('registration_open');
        });
    }

    public function down(): void
    {
        Schema::table('camps', function (Blueprint $table) {
            $table->dropColumn(['type', 'registration_open', 'capacity']);
        });
    }
};
