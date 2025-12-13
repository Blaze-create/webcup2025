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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('name')->nullable();
            $table->string('species');
            $table->string('atmosphere');
            $table->string('gravity');
            $table->integer('tempMin');
            $table->integer('tempMax');
            $table->string('comms');
            $table->string('intent');
            $table->string('bioType');
            $table->integer('risk'); // 0-100

            $table->timestamps();

            // 1 profile per user
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
