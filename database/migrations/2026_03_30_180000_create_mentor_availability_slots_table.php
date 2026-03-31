<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMentorAvailabilitySlotsTable extends Migration
{
    public function up()
    {
        Schema::create('mentor_availability_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mentor_availability_slots');
    }
}
