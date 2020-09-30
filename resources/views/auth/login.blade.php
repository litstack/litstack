@extends('litstack::landing')

@section('title')
    Login
@endsection

@section('content')
    <div class="row" style="position: absolute;top:0;right:0;left:0;bottom:0;">
        <div class="col-6 h-100">
        <div class="d-flex justify-content-center align-items-center h-100">
            <div class="col-8">
                <form method="POST" id="login" onsubmit="doLogin(event)" class="mt-4 mb-4">
                    @csrf
                    {{-- <h6 class="mb-3">{{ __lit('login.login') }}</h6> --}}
                    <div class="form-group mb-5">
                        <input 
                            id="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            name="email" 
                            required 
                            @if(config('lit.login.username'))
                            placeholder="{{ ucfirst(__lit('login.email_or_username')) }} "
                            type="text"
                            @else
                            placeholder="{{ ucfirst(__lit('base.email')) }}"
                            autocomplete="email" 
                            type="email"
                            @endif
                            autofocus
                            style="
                            background-color: transparent;
                            border-width: 0 0 1px 0;
                            border-radius: 0;
                            padding: .3125rem 0;
                            "
                            >
    
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
    
                    <div class="form-group mb-3">
                        <input 
                            placeholder="{{ ucfirst(__lit('base.password')) }}" 
                            id="password" type="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            style="
                            background-color: transparent;
                            border-width: 0 0 1px 0;
                            border-radius: 0;
                            padding: .3125rem 0;
                            ">
    
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
    
                    <div class="form-group d-flex justify-content-between">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
    
                            <label class="form-check-label" for="remember">
                                {{ __lit('login.remember_me') }}
                            </label>
                        </div>
                        {{--
                        <a href="{{ route('password.request') }}" id="forgot-password" style="display:none;">
                            {{ __lit('login.forgot_password') }}
                        </a>
                        --}}                                    
                    </div>
    
                    <div class="text-danger text-center" id="login-failed" style="display:none;">
                        {{ __lit('login.failed') }}
                    </div>
    
                    <div class="form-group row mt-4 justify-content-center d-flex">
                            <button type="submit" class="btn btn-primary">
                                {{ __lit('login.do_login') }}
                            </button>
                    </div>
                </form>
            </div>
        </div>
            
        </div>

        <div class="col-6" style="background:#0a0e23;">

        </div>
    </div>
    <script type="text/javascript">
        function doLogin(e) {
            e.preventDefault()

            const data = new FormData(document.forms.login);

            let promise = axios.post('{{ Lit::route('login.post') }}', data)
            promise.then(function(response) {
                window.location = response.data
            })
            promise.catch(function(error) {
                document.getElementById('login-failed').style.display = 'block';
                document.getElementById('forgot-password').style.display = 'block';
            })
        }
    </script>
    @if(isset($script))
        <script src="{{ $script }}"></script>
    @endif
@endsection
