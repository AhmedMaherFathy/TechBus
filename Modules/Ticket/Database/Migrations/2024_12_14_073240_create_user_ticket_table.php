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
        Schema::create('user_ticket', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('ticket_id');
            $table->foreign('user_id')->references('custom_id')->on('users')->restrictOnDelete();
            $table->foreign('ticket_id')->references('custom_id')->on('tickets')->restrictOnDelete();
            $table->date('date');
            $table->time('time');
            $table->unsignedTinyInteger('payed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_ticket');
    }
};
