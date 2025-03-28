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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};


[ id => 
  name =>
  description =>
  brand_id => 
  category_id =>
  variations[
		id_1 =>
		name => 
		type =>
		values => [
				lable => 
				value =>
			   ],
		id_2 =>
		name => 
		type =>
		values => [
				lable => 
				value =>
			   ]

	    ]
  variants[
	    id_1 => [name => (ví dụ M/Đỏ)
		     sku => 
	    	     price =>
	    	     special_price =>
   	     	     special_price_type =>
            	     special_price_start =>
             	     special_price_end =>
	       	     manage_stack => 
	             in_stock =>
 	             is_active =>
		    ],
	    id_2 => [name => (ví dụ M/Xanh kết hợp các lable variations)
		     sku => 
	    	     price =>
	    	     special_price =>
   	     	     special_price_type =>
            	     special_price_start =>
             	     special_price_end =>
	       	     manage_stack => 
	             in_stock =>
 	             is_active =>
		    ],
    	    ....
	]
]
