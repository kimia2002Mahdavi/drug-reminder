<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // کلید خارجی به جدول users
            $table->string('name');
            $table->string('type'); // نوع دارو (قرص، تزریق، شربت)
            $table->string('dosage'); // مقدار مصرف در هر بار
            $table->string('interval_type'); // نوع تکرار (روزانه، هر X روز، هر X ساعت)
            $table->integer('interval_value'); // مقدار X در بازه زمانی
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
        Schema::dropIfExists('medications');
    }
}