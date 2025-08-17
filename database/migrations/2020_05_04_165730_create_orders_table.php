<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable();
            $table->Biginteger('stores_id');
            $table->foreignId('users_id');
            $table->enum('status', ['process','canceled', 'completed'])->default('process');
            $table->enum('currency', ['ltc','doge', 'bch','gpay'])->default('ltc');
            $table->float('crypto_wallet_total_amount', 10, 6)->nullable();
            $table->float('crypto_wallet_total_paid', 10, 6)->nullable();
            $table->string('crypto_wallet_transaction')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            //$table->foreign('stores_id')->references('id')->on('stores')->onDelete();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
