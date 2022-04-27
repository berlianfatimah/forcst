<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actuals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id')->constrained('provinces')->onUpdate('cascade')->onDelete('cascade');
            $table->year('year');
            $table->integer('actual');
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
        Schema::dropIfExists('actuals');
    }
}
