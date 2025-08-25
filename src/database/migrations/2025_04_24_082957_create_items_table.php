<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('condition');
            $table->string('name');
            $table->string('brand');
            $table->text('description');
            $table->integer('price');
            $table->foreignId('seller_id')->constrained('users');
            $table->foreignId('buyer_id')->nullable()->constrained('users');
            $table->foreignId('trader_id')->nullable()->constrained('users');
            $table->boolean('sold')->default(false);
            $table->string('payment_method')->nullable();
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
        Schema::dropIfExists('items');
    }
}
