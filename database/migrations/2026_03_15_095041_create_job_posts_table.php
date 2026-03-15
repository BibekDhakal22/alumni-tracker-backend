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
    Schema::create('job_posts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('posted_by')->constrained('users')->onDelete('cascade');
        $table->string('title');
        $table->text('description');
        $table->string('company');
        $table->string('location');
        $table->string('type')->default('full-time');
        $table->string('industry')->nullable();
        $table->date('deadline')->nullable();
        $table->string('contact_email')->nullable();
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
