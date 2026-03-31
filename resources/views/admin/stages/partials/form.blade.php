<div class="mb-3">
    <label class="form-label">Project</label>
    <select class="form-select" name="project_id" required>
        @foreach($projects as $project)
            <option value="{{ $project->id }}" @if(old('project_id', $stage->project_id ?? '') == $project->id) selected @endif>
                {{ $project->title }}
            </option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Name</label>
    <input class="form-control" name="name" value="{{ old('name', $stage->name ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Order</label>
    <input class="form-control" type="number" name="stage_order" value="{{ old('stage_order', $stage->stage_order ?? 1) }}" min="1" required>
</div>

