<x-layouts.auth>
    <div class="card card-md">
        <div class="card-body">
            <h2 class="h2 text-center mb-4">Reset password</h2>
            <p class="text-muted mb-4">Enter your email address and we’ll send you a link to reset your password.</p>

            <form action="{{ route('password.email') }}" method="post" autocomplete="off" novalidate>
                @csrf

                <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="you@example.com" required autofocus>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn btn-primary w-100">Send reset link</button>
                </div>
            </form>
        </div>
    </div>

    <div class="text-center text-muted mt-3">
        <a href="{{ route('login') }}">Back to sign in</a>
    </div>
</x-layouts.auth>

