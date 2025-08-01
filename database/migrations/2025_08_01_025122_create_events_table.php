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
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('banner')->nullable(); // gambar/banner event
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->timestamp('registration_deadline'); // batas akhir pendaftaran
            $table->timestamp('preliminary_date'); // tanggal penyisihan
            $table->timestamp('final_date'); // tanggal final
            $table->string('whatsapp_group_link'); // link grup WA peserta
            $table->string('guidebook_link'); // link panduan / buku teknis
            $table->string('location');
            $table->boolean('is_online')->default(true);
            $table->string('link_zoom'); // link Zoom/Gmeet jika online
            $table->integer('quota'); // batas peserta (opsional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
