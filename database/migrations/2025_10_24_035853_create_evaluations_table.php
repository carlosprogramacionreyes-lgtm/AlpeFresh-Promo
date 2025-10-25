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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->foreignId('chain_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assignment_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('visited_at')->nullable();
            $table->string('status')->default('draft');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('geofence_valid')->default(false);
            $table->json('availability')->nullable();
            $table->tinyInteger('quality_rating')->nullable();
            $table->text('quality_observations')->nullable();
            $table->string('quality_photo_path')->nullable();
            $table->decimal('price_observed', 10, 2)->nullable();
            $table->decimal('price_regular', 10, 2)->nullable();
            $table->decimal('price_discount', 10, 2)->nullable();
            $table->boolean('has_promotion')->default(false);
            $table->string('price_photo_path')->nullable();
            $table->json('incidents')->nullable();
            $table->text('incident_comments')->nullable();
            $table->text('review_notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['store_id', 'visited_at']);
            $table->index(['user_id', 'visited_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
