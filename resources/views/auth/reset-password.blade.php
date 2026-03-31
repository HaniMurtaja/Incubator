<x-layouts.auth>
    <div class="card card-md">
        <div class="card-body">
            <h2 class="h2 text-center mb-4">Choose a new password</h2>

            <form action="{{ route('password.update') }}" method="post" autocomplete="off" novalidate>
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input type="email" name="email" value="{{ old('email', $request->email) }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn btn-primary w-100">Reset password</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.auth>

