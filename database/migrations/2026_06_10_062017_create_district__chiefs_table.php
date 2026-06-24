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
        Schema::create('district__chiefs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nip', 20)->unique();
            $table->string('phone', 20)->unique();
            $table->string('email')->unique();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->year('start_year');
            $table->year('end_year')->nullable();
            $table->foreignId('district_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('district__chiefs');
    }
};
