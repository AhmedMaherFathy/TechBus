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
        Schema::create('buses', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number')->unique();
            $table->string('custom_id');
            $table->enum('status',['active','off'])->default('active');
            $table->string('license')->nullable();

            $table->string('route_id')->nullable();
            $table->string('ticket_id')->nullable();
            $table->string('driver_id')->nullable();

            $table->foreign('route_id')->references('custom_id')->on('routes')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('driver_id')->references('custom_id')->on('drivers')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('ticket_id')->references('custom_id')->on('tickets')->nullOnDelete()->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buses');
    }
};
