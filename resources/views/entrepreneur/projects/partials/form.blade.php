<div class="mb-3">
    <label class="form-label">Title</label>
    <input class="form-control" name="title" value="{{ old('title', $project->title ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Category</label>
    <input class="form-control" name="category" value="{{ old('category', $project->category ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea class="form-control" name="description" rows="5" required>{{ old('description', $project->description ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label">Attachments</label>
    <input type="file" class="form-control" name="attachments[]" multiple>
</div>

