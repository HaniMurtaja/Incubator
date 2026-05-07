<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncubatorRoundSponsorTable extends Migration
{
    public function up()
    {
        Schema::create('incubator_round_sponsor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incubator_round_id')->constrained('incubator_rounds')->cascadeOnDelete();
            $table->foreignId('sponsor_id')->constrained('sponsors')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['incubator_round_id', 'sponsor_id'], 'round_sponsor_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('incubator_round_sponsor');
    }
}

