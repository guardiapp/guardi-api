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
            $table->foreignId('apartment_id')->constrained('apartments')->onDelete('cascade');
            $table->foreignId('visitor_id')->nullable()->constrained('visitors')->onDelete('cascade');
            $table->string('qr');
            $table->text('remarks');
            $table->datetime('visit_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->boolean('cancelled')->default(0);
            $table->boolean('visited')->default(0);
            $table->datetime('entry_time')->nullable();
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
