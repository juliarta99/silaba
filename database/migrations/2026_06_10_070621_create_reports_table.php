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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('code', 100)->unique();
            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->foreignId('district_id')->constrained()->cascadeOnDelete();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->datetime('sla_deadline')->nullable();
            $table->foreignId('parent_report_id')->nullable()->constrained('reports')->nullOnDelete();
            $table->enum('status', [
                'pending',
                'rejected',
                'completed',
                'in_progress',
                'under_review',
                'waiting_for_materials',
            ])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
