<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->string('uid', 191)->nullable();
            $table->text('uids')->nullable();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('name', 191);
            $table->decimal('price', 18, 4);
            $table->decimal('special_price', 18, 4)->nullable();
            $table->string('special_price_type', 191)->nullable();
            $table->date('special_price_start')->nullable();
            $table->date('special_price_end')->nullable();
            $table->decimal('selling_price', 18, 4)->nullable();
            $table->string('sku', 191)->nullable();
            $table->boolean('manage_stock')->default(1);
            $table->integer('qty')->default(0);
            $table->boolean('in_stock')->default(1);
            $table->boolean('is_default')->default(0);
            $table->boolean('is_active')->default(1);
            $table->integer('position')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_variants');
    }

};
