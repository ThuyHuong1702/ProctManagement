<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('variations', function (Blueprint $table) {
            $table->id();
            $table->string('uid', 191)->unique();
            $table->string('type', 191)->nullable();
            $table->boolean('is_global')->default(1);
            $table->integer('position')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('variations');
    }

};
