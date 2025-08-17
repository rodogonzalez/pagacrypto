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

        Schema::create('store_categories', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('icon')->default('la-store-alt')->nullable();


        });

        Schema::create('stores', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('users_id');

            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            $table->bigInteger('parking_limit')->default(0);
            $table->bigInteger('store_categories_id');
            $table->longText('description')->nullable();

            $table->float('position_lng', 10, 6)->nullable();
            $table->float('position_lat',  10, 6)->nullable();

            $table->string('wallet_btc')->nullable();
            $table->string('wallet_ltc')->nullable();
            $table->string('wallet_bch')->nullable();
            $table->string('wallet_doge')->nullable();



            $table->timestamps();
        });

        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('users_id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('stores_id');
            $table->bigInteger('product_categories_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('barcode')->unique()->nullable();
            $table->decimal('price', 8, 2);
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('products');
        Schema::dropIfExists('local_stores');

    }
};
