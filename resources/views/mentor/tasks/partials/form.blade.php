<div class="mb-3">
    <label class="form-label">Stage</label>
    <select class="form-select" name="stage_id" required>
        @foreach($stages as $stage)
            <option value="{{ $stage->id }}" @if(old('stage_id', $task->stage_id ?? '') == $stage->id) selected @endif>
                {{ optional($stage->project)->title }} / {{ $stage->name }}
            </option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Title</label>
    <input class="form-control" name="title" value="{{ old('title', $task->title ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea class="form-control" name="description">{{ old('description', $task->description ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label">Due date</label>
    <input class="form-control" type="date" name="due_date" value="{{ old('due_date', isset($task->due_date) ? $task->due_date->format('Y-m-d') : '') }}">
</div>

