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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained('residents')->onDelete('cascade');
            $table->foreignId('visitor_id')->nullable()->constrained('visitors')->onDelete('cascade');
            $table->datetime('entry_time')->nullable();
            $table->datetime('exit_time')->nullable();
            $table->text('comments')->nullable();
            $table->boolean('visit_confirmed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
