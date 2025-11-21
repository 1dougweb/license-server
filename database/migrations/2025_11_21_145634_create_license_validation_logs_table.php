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
        Schema::create('license_validation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('license_id')->nullable()->constrained()->onDelete('set null');
            $table->string('domain')->nullable();
            $table->string('device_id')->nullable();
            $table->string('ip')->nullable();
            $table->text('user_agent')->nullable();
            $table->boolean('is_valid')->default(false);
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_validation_logs');
    }
};
