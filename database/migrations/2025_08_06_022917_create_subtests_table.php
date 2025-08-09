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
        Schema::create('subtests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('subject_id')->nullable()->references('id')->on('subjects')->nullOnDelete();
            $table->string('subtest_code');
            $table->foreignUuid('tryout_id')->references('id')->on('tryouts')->onDelete('cascade');
            $table->string('title');
            $table->integer('amount_minutes');
            $table->integer('amount_question');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subtests');
    }
};
