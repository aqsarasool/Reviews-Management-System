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
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('product_name')->nullable();  // Adding product_name
            $table->string('location')->nullable();      // Adding location
        });
    }
    
    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('product_name');
            $table->dropColumn('location');
        });
    }
    
};
