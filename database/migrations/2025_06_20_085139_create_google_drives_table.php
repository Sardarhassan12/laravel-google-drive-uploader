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
        Schema::create('google_drives', function (Blueprint $table) {
            $table->id();
            $table->json('token_json')->nullable();
            // $table->string('access_token');
            // $table->string('refresh_token');
            // $table->timestamp('token_expire_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('google_drives');
    }
};
