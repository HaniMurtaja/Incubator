<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignmentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignment_logs', function (Blueprint $table) {
            $table->id();
            $table->date('assignment_date')->index();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('business_mentor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('entrepreneur_id')->constrained('users')->cascadeOnDelete();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('assignment_logs');
    }
}
