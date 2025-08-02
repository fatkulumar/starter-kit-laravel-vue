<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tryouts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('thumbnail')->nullable(); // opsional: thumbnail atau banner
            $table->foreignUuid('event_id')->references('id')->on('events')->onDelete('cascade'); // relasi opsional ke event
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('start_time'); // waktu mulai tryout
            $table->timestamp('end_time');   // waktu berakhir tryout
            $table->integer('duration'); // Durasi pengerjaan dalam menit
            $table->boolean('is_active')->default(true); // aktif/tidak
            $table->boolean('is_locked')->default(false); // untuk mengunci tryout setelah deadline
            $table->string('guide_link')->nullable();  // link khusus panduan tryout ini
            $table->integer('price')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tryouts');
    }
};
