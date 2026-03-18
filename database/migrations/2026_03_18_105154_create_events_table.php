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
    Schema::create('events', function (Blueprint $table) {
        $table->id();
        $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
        $table->string('title');
        $table->text('description');
        $table->string('location');
        $table->dateTime('event_date');
        $table->string('type')->default('reunion');
        $table->integer('max_attendees')->nullable();
        $table->string('contact_email')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
