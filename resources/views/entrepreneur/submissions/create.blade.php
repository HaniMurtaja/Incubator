@extends('layouts.app')
@section('title', 'Submit Task Work')
@section('content')
<form method="post" action="{{ route('entrepreneur.submissions.store') }}" enctype="multipart/form-data" class="card card-body">
    @csrf
    <div class="mb-3">
        <label class="form-label">Task</label>
        <select class="form-select" name="task_id" required>
            @foreach($tasks as $task)
                <option value="{{ $task->id }}">{{ optional(optional($task->stage)->project)->title }} / {{ $task->title }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Notes</label>
        <textarea class="form-control" name="notes"></textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Files</label>
        <input class="form-control" type="file" name="files[]" multiple>
    </div>
    <button class="btn btn-primary">Submit</button>
</form>
@endsection

