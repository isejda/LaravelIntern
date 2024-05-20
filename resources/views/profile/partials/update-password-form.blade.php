<section>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Update Password') }}</h3>
            <p class="card-subtitle">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                @csrf
                @method('put')

                <div class="form-group">
                    <label for="update_password_current_password" class="form-label">{{ __('Current Password') }}</label>
                    <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" />
                    @error('current_password', 'updatePassword')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="update_password_password" class="form-label">{{ __('New Password') }}</label>
                    <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password" />
                    @error('password', 'updatePassword')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="update_password_password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" />
                    @error('password_confirmation', 'updatePassword')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>

                    @if (session('status') === 'password-updated')
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                Swal.fire({
                                    icon: 'success',
                                    title: '{{ __("Password saved successfully") }}',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            });
                        </script>
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>
