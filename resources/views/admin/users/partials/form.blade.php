<div class="mb-3">
    <label class="form-label">Name</label>
    <input class="form-control" name="name" value="{{ old('name', $user->name ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Email</label>
    <input class="form-control" type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Password @isset($user)<small class="text-muted">(leave blank to keep)</small>@endisset</label>
    <input class="form-control" type="password" name="password">
</div>
<div class="mb-3">
    <label class="form-label">Role</label>
    <select class="form-select" name="role" required>
        @foreach(['Admin','Mentor','Entrepreneur'] as $role)
            <option value="{{ $role }}" @if(old('role', isset($user) ? $user->getRoleNames()->first() : '') === $role) selected @endif>{{ $role }}</option>
        @endforeach
    </select>
</div>

