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

        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('users_id');
            $table->string('name');
            $table->string('owner_name');
            $table->string('owner_id');
            $table->string('owner_id_picture_front')->nullable();
            $table->string('owner_id_picture_back')->nullable();
            $table->string('owner_phone');
            $table->string('iban_account');
            $table->enum('status', ['non-validated', 'canceled', 'validated'])->default('non-validated');
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();

        });

        Schema::create('withdrawals', function (Blueprint $table) {

            $table->id();
            $table->bigInteger('users_id');
            $table->bigInteger('processed_by_users_id')->nullable();
            $table->decimal('amount', 8, 4);
            $table->string('iban_account');
            $table->string('bank_name');
            $table->string('account_owner_name');
            $table->enum('status', ['requested', 'canceled', 'process', 'completed'])->default('process');
            $table->string('bank_transaction_id');
            $table->text('notes');
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('store_withdrawal_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_withdrawal_id');
            $table->bigInteger('orders_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('store_withdraws');
    }
};
