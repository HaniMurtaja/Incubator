<x-layouts.auth>
    <div class="card card-md">
        <div class="card-body">
            <h2 class="h2 text-center mb-4">Create account</h2>

            <form action="{{ route('register') }}" method="post" autocomplete="off" novalidate>
                @csrf

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Your name" required autofocus>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="you@example.com" required>
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
                    <button type="submit" class="btn btn-primary w-100">Create account</button>
                </div>
            </form>
        </div>
    </div>

    <div class="text-center text-muted mt-3">
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
    </div>
</x-layouts.auth>

