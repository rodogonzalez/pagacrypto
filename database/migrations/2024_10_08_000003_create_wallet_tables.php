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
        Schema::create('wallet_tables', function (Blueprint $table) {
            /**
             * 
             * 
             * 
             * 
             */
            $table->id();
            $table->bigInteger('stores_id');
            $table->string('currency');
            $table->string('api_key');
            $table->string('password');
            $table->string('label');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_tables');
    }
};
