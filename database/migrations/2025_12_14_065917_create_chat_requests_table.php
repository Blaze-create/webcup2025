<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('chat_requests', function (Blueprint $table) {
      $table->id();

      $table->unsignedBigInteger('from_id'); // sender user id
      $table->unsignedBigInteger('to_id');   // receiver user id (you)

      $table->text('preview')->nullable();   // small message preview
      $table->timestamp('accepted_at')->nullable();
      $table->timestamp('declined_at')->nullable();

      $table->timestamps();

      $table->index(['to_id', 'accepted_at', 'declined_at']);
      $table->unique(['from_id', 'to_id']); // 1 request per pair
    });
  }

  public function down(): void {
    Schema::dropIfExists('chat_requests');
  }
};