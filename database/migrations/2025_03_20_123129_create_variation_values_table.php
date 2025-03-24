<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('variation_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variation_id')->constrained('variations')->onDelete('cascade');
            $table->string('label', 191)->nullable();
            $table->string('value', 191);
            $table->integer('position')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('variation_values');
    }

};
