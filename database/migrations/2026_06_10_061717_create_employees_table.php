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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nip', 20)->unique();
            $table->string('phone', 20)->unique();
            $table->string('email')->unique();
            $table->enum('status', ['active', 'on_leave', 'inactive'])->default('active');
            $table->enum('position', ['field_officer', 'supervisor', 'head_of_department']);
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
