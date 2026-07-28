<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Renumber existing teams sequentially first, in case duplicate
        // sort_order values already exist (ties broken by id).
        $teams = DB::table('teams')->orderBy('sort_order')->orderBy('id')->get(['id']);
        foreach ($teams as $index => $team) {
            DB::table('teams')->where('id', $team->id)->update(['sort_order' => $index]);
        }

        Schema::table('teams', function (Blueprint $table) {
            $table->unique('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropUnique(['sort_order']);
        });
    }
};
