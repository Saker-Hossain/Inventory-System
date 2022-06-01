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
            // $table->unsignedBigInteger('e_history')->references('id')->on('histories');
            $table->string('brand_id')->nullable();
            $table->string('brand_name')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('model')->nullable();
            $table->string('availability')->nullable();
            $table->date('date_received')->nullable();
            $table->date('date_sold')->nullable();
            $table->string('unit_type');
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
