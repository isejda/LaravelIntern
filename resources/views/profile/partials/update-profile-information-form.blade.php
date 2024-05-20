<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('Profile Information') }}</h3>
        <p class="card-subtitle">{{ __("Update your account's profile information and email address.") }}</p>
    </div>
    <div class="card-body">
        <form id="send-verification" method="post" action="{{ route('verification.send') }}" enctype="multipart/form-data">
            @csrf
        </form>
        <div class="row">
            <div class="col-md-6 ">
            <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Name') }}</label>
                    <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                    @error('name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="lastname" class="form-label">{{ __('Lastname') }}</label>
                    <input id="lastname" name="lastname" type="text" class="form-control" value="{{ old('lastname', $user->lastname) }}" required autofocus autocomplete="lastname" />
                    @error('lastname')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="username" class="form-label">{{ __('Username') }}</label>
                    <input id="username" name="username" type="text" class="form-control" value="{{ old('username', $user->username) }}" required autofocus autocomplete="username" />
                    @error('username')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="birthday" class="form-label">{{ __('Birthday') }}</label>
                    <input id="birthday" name="birthday" type="date" class="form-control" value="{{ old('birthday', $user->birthday) }}" required autofocus autocomplete="birthday" />
                    @error('birthday')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="birthday" />
                    @error('email')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-2">
                            <p class="text-gray-800">
                                {{ __('Your email address is unverified.') }}
                                <button form="send-verification" class="btn btn-link">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>
                            @if (session('status') === 'verification-link-sent')
                                <p class="text-success">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="image" class="form-label">{{ __('Profile Picture') }}</label>
                    <input id="image" name="image" type="file" class="form-control" autofocus autocomplete="image" />
                    @error('image')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>

                    @if (session('status') === 'profile-updated')
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                Swal.fire({
                                    icon: 'success',
                                    title: '{{ __("User saved successfully") }}',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            });
                        </script>
                    @endif

                </div>
            </form>
            </div>
            <div class="col-md-6 stretch-card grid-margin">
                <img id="profile-image" src="{{app('App\Http\Controllers\SidebarController')->getImageURL()}}" class="img-fluid" alt="profile" />
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('image').addEventListener('change', function(event) {
        var file = event.target.files[0];
        var reader = new FileReader();

        reader.onload = function(e) {
            document.getElementById('profile-image').src = e.target.result;
        };

        reader.readAsDataURL(file);
    });
</script>


