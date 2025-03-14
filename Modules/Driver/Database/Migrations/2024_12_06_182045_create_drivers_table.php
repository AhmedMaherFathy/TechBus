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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('custom_id')->unique();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('national_id')->unique();
            $table->string('driver_license')->unique();
            $table->string('password');
            $table->enum('status', ['active', 'off', 'standby'])->default('active');
            $table->double('lat')->nullable();
            $table->double('long')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
