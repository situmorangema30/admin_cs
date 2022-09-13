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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('code', 50);
            $table->foreignId('product_categories_id');
            $table->decimal('price', $precision = 18);
            $table->decimal('purchase_price', $precision = 18);
            $table->string('short_description', 250)->nullable();
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->unsignedTinyInteger('new_product');
            $table->unsignedTinyInteger('best_seller');
            $table->unsignedTinyInteger('featured');
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
    }
};
