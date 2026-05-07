<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\QuickAccessController;
use App\Http\Controllers\Admin\ProjectReviewController;
use App\Http\Controllers\Admin\AssignmentLogController;
use App\Http\Controllers\Admin\MeetingRequestController;
use App\Http\Controllers\Admin\AuditTrailController;
use App\Http\Controllers\Admin\StageTemplateController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\IncubatorLifeCycleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Entrepreneur\ProjectController as EntrepreneurProjectController;
use App\Http\Controllers\Entrepreneur\MeetingController as EntrepreneurMeetingController;
use App\Http\Controllers\Entrepreneur\RoundController as EntrepreneurRoundController;
use App\Http\Controllers\Entrepreneur\TaskSubmissionController;
use App\Http\Controllers\Mentor\ProjectController as MentorProjectController;
use App\Http\Controllers\Mentor\TaskController as MentorTaskController;
use App\Http\Controllers\Mentor\CommandCenterController;
use App\Http\Controllers\Mentor\MentorshipCalendarController;
use App\Http\Controllers\Mentor\RoundController as MentorRoundController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::post('/contact-us', [LandingController::class, 'contact'])->name('contact.submit');
Route::post('/quick-access/{role}', [QuickAccessController::class, 'loginAsRole'])->name('quick.access');
Route::get('/locale/{locale}', function ($locale) {
    abort_unless(in_array($locale, ['ar', 'en'], true), 404);
    session(['locale' => $locale]);
    return redirect()->back();
})->name('locale.switch');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    Route::prefix('admin')->name('admin.')->middleware('role:Admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        Route::resource('users', UserController::class);
        Route::resource('stages', StageTemplateController::class);
        Route::get('projects', [ProjectReviewController::class, 'index'])->name('projects.index');
        Route::post('projects', [ProjectReviewController::class, 'store'])->name('projects.store');
        Route::patch('projects/{project}/review', [ProjectReviewController::class, 'update'])->name('projects.review');
        Route::resource('assignments', AssignmentLogController::class)->only(['index', 'store', 'destroy']);
        Route::resource('meetings', MeetingRequestController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::get('audit-trail', [AuditTrailController::class, 'index'])->name('audit.index');
        Route::get('incubator-life-cycle', [IncubatorLifeCycleController::class, 'index'])->name('lifecycle.index');
        Route::post('incubator-life-cycle/rounds', [IncubatorLifeCycleController::class, 'storeRound'])->name('lifecycle.rounds.store');
        Route::patch('incubator-life-cycle/rounds/{round}', [IncubatorLifeCycleController::class, 'updateRound'])->name('lifecycle.rounds.update');
        Route::delete('incubator-life-cycle/rounds/{round}', [IncubatorLifeCycleController::class, 'destroyRound'])->name('lifecycle.rounds.destroy');
        Route::post('incubator-life-cycle/rounds/{round}/projects', [IncubatorLifeCycleController::class, 'storeProject'])->name('lifecycle.projects.store');
        Route::delete('incubator-life-cycle/rounds/{round}/projects/{project}', [IncubatorLifeCycleController::class, 'destroyProject'])->name('lifecycle.projects.destroy');
    });

    Route::prefix('mentor')->name('mentor.')->middleware('role:Mentor')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'mentor'])->name('dashboard');
        Route::resource('tasks', MentorTaskController::class);
        Route::get('command-center', [CommandCenterController::class, 'index'])->name('command.index');
        Route::patch('command-center/stages/{stage}', [CommandCenterController::class, 'updateStage'])->name('command.stages.update');
        Route::post('command-center/stages/{stage}/tasks', [CommandCenterController::class, 'storeTask'])->name('command.tasks.store');
        Route::patch('command-center/stages/{stage}/tasks/{task}/comment', [CommandCenterController::class, 'updateTaskComment'])->name('command.tasks.comment.update');
        Route::get('calendar', [MentorshipCalendarController::class, 'index'])->name('calendar.index');
        Route::get('calendar/events', [MentorshipCalendarController::class, 'events'])->name('calendar.events');
        Route::post('calendar/availability', [MentorshipCalendarController::class, 'storeAvailability'])->name('calendar.availability.store');
        Route::delete('calendar/availability/{slot}', [MentorshipCalendarController::class, 'destroyAvailability'])->name('calendar.availability.destroy');
        Route::post('calendar/meetings', [MentorshipCalendarController::class, 'storeMeeting'])->name('calendar.meetings.store');
        Route::patch('calendar/meeting-requests/{meeting}/status', [MentorshipCalendarController::class, 'updateMeetingRequestStatus'])->name('calendar.requests.status.update');
        Route::get('rounds', [MentorRoundController::class, 'index'])->name('rounds.index');
        Route::get('projects', [MentorProjectController::class, 'index'])->name('projects.index');
        Route::get('projects/{project}', [MentorProjectController::class, 'show'])->name('projects.show');
        Route::post('projects/{project}/stages/{stage}/tasks', [MentorProjectController::class, 'storeTask'])->name('projects.tasks.store');
        Route::patch('projects/{project}/stages/{stage}/tasks/{task}/comment', [MentorProjectController::class, 'updateTaskComment'])->name('projects.tasks.comment.update');
        Route::patch('projects/{project}/stages/{stage}/tasks/{task}/status', [MentorProjectController::class, 'updateTaskStatus'])->name('projects.tasks.status.update');
        Route::post('projects/{project}/stages/{stage}/tasks/{task}/messages', [MentorProjectController::class, 'sendTaskMessage'])->name('projects.tasks.messages.store');
        Route::post('submissions/{submission}/evaluate', [MentorProjectController::class, 'evaluate'])->name('submissions.evaluate');
    });

    Route::prefix('entrepreneur')->name('entrepreneur.')->middleware('role:Entrepreneur')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'entrepreneur'])->name('dashboard');
        Route::resource('projects', EntrepreneurProjectController::class);
        Route::get('meetings', [EntrepreneurMeetingController::class, 'index'])->name('meetings.index');
        Route::get('meetings/availability-events', [EntrepreneurMeetingController::class, 'availabilityEvents'])->name('meetings.availability.events');
        Route::post('meetings', [EntrepreneurMeetingController::class, 'store'])->name('meetings.store');
        Route::get('rounds', [EntrepreneurRoundController::class, 'index'])->name('rounds.index');
        Route::patch('projects/{project}/tasks/{task}/status', [EntrepreneurProjectController::class, 'updateTaskStatus'])->name('projects.tasks.status.update');
        Route::post('projects/{project}/tasks/{task}/submit', [EntrepreneurProjectController::class, 'submitTaskWork'])->name('projects.tasks.submit');
        Route::post('projects/{project}/tasks/{task}/messages', [EntrepreneurProjectController::class, 'sendTaskMessage'])->name('projects.tasks.messages.store');
        Route::resource('submissions', TaskSubmissionController::class)->only(['index', 'create', 'store', 'show']);
    });
});
