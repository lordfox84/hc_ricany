<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('camps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title_cs');
            $table->string('title_en')->nullable();
            $table->text('excerpt_cs')->nullable();
            $table->text('excerpt_en')->nullable();
            $table->longText('body_cs')->nullable();
            $table->longText('body_en')->nullable();
            $table->string('poster')->nullable();       // plakát kempu
            $table->date('date_from')->nullable();      // termín od
            $table->date('date_to')->nullable();        // termín do
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('camps');
    }
};
