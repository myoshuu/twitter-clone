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
    Schema::create('tag_tweets', function (Blueprint $table) {
      $table->id();
      $table->foreignId('tag_id')->constrained('tags')->onDelete('cascade')->onUpdate('cascade');
      $table->foreignId('tweet_id')->constrained('tweets')->onDelete('cascade')->onUpdate('cascade');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('tag_tweets');
  }
};
