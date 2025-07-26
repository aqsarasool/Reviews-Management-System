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
            $table->date('review_date')->nullable(); // Add the review_date field
        });
    }
    
    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('review_date'); // Rollback the review_date field
        });
    }
    
};
