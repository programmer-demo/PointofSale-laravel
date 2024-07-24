<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
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
            $table->string('code_product')->unique();
            $table->string('name')->unique();
            $table->string('brand')->nullable();
            $table->double('purchase_price' , 16 , 2);
            $table->double('selling_price' , 16 , 2)->default(0);
            $table->double('discount' , 16 , 2)->default(0);
            $table->integer('stock');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('no action');
            $table->unsignedBigInteger('member_id')->unsigned();
            $table->foreign('member_id')->references('id')->on('members')->onDelete('no action');
            $table->softDeletes();
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
    }
}
