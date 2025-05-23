<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReminderSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminder_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // کلید خارجی به جدول users
            $table->foreignId('medication_id')->constrained()->onDelete('cascade'); // کلید خارجی به جدول medications
            $table->timestamp('reminder_time'); // زمان دقیق دوز موردنظر
            $table->string('status')->default('pending'); // وضعیت (pending, sent, confirmed, ...)
            $table->text('message')->nullable(); // متن پیام یادآوری
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
        Schema::dropIfExists('reminder_schedules');
    }
}