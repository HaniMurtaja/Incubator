<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskSubmissionFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_submission_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_submission_id')->constrained()->cascadeOnDelete();

            $table->string('path');
            $table->string('original_name');
            $table->string('mime', 127)->nullable();
            $table->unsignedBigInteger('size')->nullable();

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
        Schema::dropIfExists('task_submission_files');
    }
}
