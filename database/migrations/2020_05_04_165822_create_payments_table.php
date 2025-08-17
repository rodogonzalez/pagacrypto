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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id');
            $table->decimal('amount', 8, 4);
            $table->foreignId('order_id');
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('crypto_order_payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id');
            $table->double('amount_received');
            $table->double('fee');
            $table->string('transaction_id')->nullable();
            $table->text('payment_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crypto_order_payments');
        Schema::dropIfExists('payments');
    }
};
