@extends('fjord::landing')

@section('l-title')
    Login
@endsection

@section('l-content')

    <div class="row justify-content-center">
        <div class="col-xl-7 col-lg-8 col-md-10 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-sm-10">
                            <form method="POST" action="{{ route('fjord.login') }}" class="mt-4 mb-4">
                                @csrf
                                <h6 class="mb-3">{{ __f('login.login') }}</h6>
                                <div class="form-group">
                                    <input 
                                        placeholder="{{ ucfirst(__f('base.email')) }}"
                                        id="email" 
                                        type="email" 
                                        class="form-control @error('email') is-invalid @enderror" 
                                        name="email" 
                                        value="{{ old('email') }}" 
                                        required 
                                        autocomplete="email" 
                                        autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group ">
                                    <input 
                                        placeholder="{{ ucfirst(__f('base.password')) }}" 
                                        id="password" type="password" 
                                        class="form-control @error('password') is-invalid @enderror" 
                                        name="password" 
                                        required 
                                        autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group d-flex justify-content-between">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __f('login.remember_me') }}
                                        </label>
                                    </div>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}">
                                            {{ __f('login.forgot_password') }}
                                        </a>
                                    @endif
                                </div>

                                <div class="form-group row mt-4 justify-content-center d-flex">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __f('login.do_login') }}
                                        </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
