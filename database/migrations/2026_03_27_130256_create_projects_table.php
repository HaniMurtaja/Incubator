<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entrepreneur_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('mentor_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('title');
            $table->text('description');
            $table->string('category')->nullable();

            // pending | accepted | rejected | in_progress | completed
            $table->string('status', 32)->default('pending')->index();

            $table->timestampTz('submitted_at')->nullable();
            $table->timestampTz('decided_at')->nullable();
            $table->text('decision_notes')->nullable();

            $table->timestampTz('started_at')->nullable();
            $table->timestampTz('completed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
