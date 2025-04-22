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
        Schema::create('driver_ticket', function (Blueprint $table) {
            $table->id();
            $table->string('driver_id');
            $table->string('ticket_id');
            $table->foreign('driver_id')->references('custom_id')->on('drivers')->restrictOnDelete();
            $table->foreign('ticket_id')->references('custom_id')->on('tickets')->restrictOnDelete();
            $table->date('date');
            $table->time('time');
            $table->unsignedTinyInteger('payed');
            $table->enum('status', ['received', 'not_received'])->default('not_received');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_ticket');
    }
};
