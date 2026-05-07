<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtendedFieldsToMeetingRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('meeting_requests', function (Blueprint $table) {
            $table->foreignId('incubator_round_id')
                ->nullable()
                ->after('project_id')
                ->constrained('incubator_rounds')
                ->nullOnDelete();
            $table->string('meeting_mode', 16)->default('online')->after('status');
            $table->unsignedSmallInteger('duration_minutes')->default(30)->after('meeting_mode');
            $table->boolean('notify_members')->default(true)->after('duration_minutes');
        });
    }

    public function down()
    {
        Schema::table('meeting_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('incubator_round_id');
            $table->dropColumn(['meeting_mode', 'duration_minutes', 'notify_members']);
        });
    }
}

