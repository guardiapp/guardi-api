<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('managers_residences', function (Blueprint $table) {
            $table->id();
            $table->foreignId("manager_id")->references("id")->on("users");
                //->nullOnDelete()
                //->nullOnUpdate();
            $table->foreignId("residence_id")->references("id")->on("residences");
                //->nullOnDelete()
                //->nullOnUpdate();
            $table->enum("state", ["ACTIVE", "INACTIVE"])->default("INACTIVE");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('managers_residences');
    }
};
