@extends('litstack::landing')

@section('title')
    Login
@endsection

@section('content')
    <div class="row vh-100" id="login">
        <div class="col-12 col-lg-6 h-100">
            <div class="d-flex justify-content-center align-items-center h-100">
                <div class="col-8">
                    <form method="POST" id="login" onsubmit="doLogin(event)" class="mt-4 mb-4">
                        @csrf
                        {{-- <h6 class="mb-3">{{ __lit('login.login') }}</h6> --}}
                        <div class="mobile-icon text-center mb-5">
                            <svg width="70px" height="100%" viewBox="0 0 169 263" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
                                <g transform="matrix(1,0,0,1,-204.96,-204.96)">
                                    <path d="M204.96,368.93C204.96,391.57 223.31,409.92 245.95,409.92C268.59,409.92 286.94,391.57 286.94,368.93L286.94,245.95L204.96,245.95L204.96,368.93Z" style="fill:rgb(73,81,242);fill-rule:nonzero;"/>
                                    <path d="M204.96,327.94C204.96,350.58 223.31,368.93 245.95,368.93C268.59,368.93 286.94,350.58 286.94,327.94L286.94,245.96L204.96,245.96L204.96,327.94Z" style="fill:rgb(64,255,164);fill-opacity:0.42;fill-rule:nonzero;"/>
                                    <path d="M204.96,286.94C204.96,309.58 223.31,327.93 245.95,327.93C268.59,327.93 286.94,309.58 286.94,286.94L286.94,245.95L204.96,245.95L204.96,286.94Z" style="fill:rgb(64,255,164);fill-opacity:0.42;fill-rule:nonzero;"/>
                                    <circle cx="245.95" cy="245.95" r="40.99" style="fill:rgb(64,255,164);"/>
                                    <circle cx="332.48" cy="426.66" r="40.99" style="fill:rgb(73,81,242);"/>
                                </g>
                            </svg>
                        </div>
                        
                        <div class="form-fields">
                        <div class="form-group mb-5">
                            <input 
                                id="email" 
                                class="form-control @error('email') is-invalid @enderror lit-login-form" 
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
                                />
        
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
                                class="form-control @error('password') is-invalid @enderror lit-login-form" 
                                name="password" 
                                required 
                                autocomplete="current-password"
                                />
        
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            @if(Session::has('status'))
                                <span class="valid-feedback" style="display:block;">{{ Session::get('status') }}</span>
                            @endif
                        </div>
                    </div>

                        {{-- <div class="form-group mb-3 mt-5">
                            <input 
                                placeholder="{{ ucfirst(__lit('2fa.code')) }}" 
                                id="code"
                                class="form-control lit-login-form" 
                                name="code" 
                                required 
                                />
                        </div> --}}
        
                        <div class="form-group d-flex justify-content-between">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
        
                                <label class="form-check-label" for="remember">
                                    {{ __lit('login.remember_me') }}
                                </label>
                            </div>
                            @if(Route::has('lit.password.forgot.show'))
                            <div>
                                <a href="{{ lit()->route('password.forgot.show') }}">{{ __lit('login.forgot_password') }}</a>
                            </div>
                            @endif
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

        <div class="col-6 text-center dark-logo" style="background:#0a0e23; display: flex; justify-content: center; align-items: center;">
            <svg width="300px" height="100%" viewBox="0 0 889 263" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
                <g transform="matrix(1,0,0,1,-204.96,-204.96)">
                    <path d="M204.96,368.93C204.96,391.57 223.31,409.92 245.95,409.92C268.59,409.92 286.94,391.57 286.94,368.93L286.94,245.95L204.96,245.95L204.96,368.93Z" style="fill:rgb(73,81,242);fill-rule:nonzero;"/>
                    <path d="M204.96,327.94C204.96,350.58 223.31,368.93 245.95,368.93C268.59,368.93 286.94,350.58 286.94,327.94L286.94,245.96L204.96,245.96L204.96,327.94Z" style="fill:rgb(64,255,164);fill-opacity:0.42;fill-rule:nonzero;"/>
                    <path d="M204.96,286.94C204.96,309.58 223.31,327.93 245.95,327.93C268.59,327.93 286.94,309.58 286.94,286.94L286.94,245.95L204.96,245.95L204.96,286.94Z" style="fill:rgb(64,255,164);fill-opacity:0.42;fill-rule:nonzero;"/>
                    <circle cx="245.95" cy="245.95" r="40.99" style="fill:rgb(64,255,164);"/>
                    <circle cx="332.48" cy="426.66" r="40.99" style="fill:rgb(73,81,242);"/>
                    <rect x="459.11" y="237.6" width="23.11" height="130.76" style="fill:white;"/>
                    <rect x="507.77" y="270.29" width="23.11" height="98.07" style="fill:white;"/>
                    <path d="M519.39,231.28C515.73,231.28 512.58,232.5 509.94,234.95C507.3,237.4 505.98,240.37 505.98,243.86C505.98,247.31 507.29,250.26 509.91,252.7C512.53,255.15 515.69,256.37 519.39,256.37C523.05,256.37 526.19,255.15 528.81,252.7C531.43,250.25 532.74,247.31 532.74,243.86C532.74,240.37 531.43,237.4 528.81,234.95C526.19,232.5 523.05,231.28 519.39,231.28Z" style="fill:white;fill-rule:nonzero;"/>
                    <path d="M600.52,349.91C599.31,350.13 597.98,350.23 596.53,350.23C594.61,350.23 592.87,349.93 591.29,349.34C589.72,348.75 588.45,347.63 587.49,345.99C586.53,344.35 586.05,341.98 586.05,338.87L586.05,288.17L605.4,288.17L605.4,270.29L586.05,270.29L586.05,246.79L562.94,246.79L562.94,270.29L549.02,270.29L549.02,288.17L562.94,288.17L562.94,342.7C562.9,348.83 564.23,353.94 566.93,358.02C569.63,362.11 573.3,365.12 577.94,367.05C582.58,368.99 587.79,369.87 593.58,369.7C596.86,369.62 599.63,369.32 601.91,368.81C604.19,368.3 605.94,367.83 607.18,367.41L603.29,349.34C602.66,349.5 601.73,349.69 600.52,349.91Z" style="fill:white;fill-rule:nonzero;"/>
                    <path d="M677.12,312.43L660.39,308.85C655.41,307.7 651.85,306.23 649.7,304.44C647.55,302.65 646.5,300.33 646.54,297.48C646.5,294.16 648.1,291.46 651.36,289.37C654.62,287.28 658.65,286.24 663.46,286.24C667.04,286.24 670.06,286.81 672.53,287.96C675,289.11 676.97,290.62 678.44,292.49C679.91,294.36 680.94,296.36 681.54,298.49L702.61,296.19C701.03,287.85 696.93,281.23 690.32,276.33C683.7,271.44 674.64,268.99 663.15,268.99C655.32,268.99 648.41,270.21 642.43,272.66C636.45,275.11 631.79,278.56 628.45,283C625.11,287.45 623.46,292.7 623.5,298.74C623.46,305.89 625.69,311.8 630.2,316.46C634.71,321.12 641.67,324.43 651.08,326.39L667.81,329.9C672.32,330.88 675.65,332.28 677.8,334.11C679.95,335.94 681.02,338.26 681.02,341.07C681.02,344.39 679.35,347.18 676.01,349.44C672.67,351.69 668.26,352.82 662.76,352.82C657.44,352.82 653.12,351.69 649.8,349.44C646.48,347.18 644.31,343.84 643.29,339.41L620.75,341.58C622.15,350.61 626.52,357.64 633.84,362.68C641.16,367.72 650.83,370.24 662.83,370.24C671,370.24 678.24,368.92 684.53,366.28C690.83,363.64 695.76,359.97 699.31,355.26C702.86,350.56 704.66,345.12 704.71,338.95C704.66,331.93 702.38,326.25 697.85,321.9C693.3,317.6 686.4,314.43 677.12,312.43Z" style="fill:white;fill-rule:nonzero;"/>
                    <path d="M767.87,349.91C766.66,350.13 765.33,350.23 763.88,350.23C761.97,350.23 760.22,349.93 758.64,349.34C757.07,348.75 755.8,347.63 754.84,345.99C753.88,344.35 753.4,341.98 753.4,338.87L753.4,288.17L772.75,288.17L772.75,270.29L753.4,270.29L753.4,246.79L730.3,246.79L730.3,270.29L716.38,270.29L716.38,288.17L730.3,288.17L730.3,342.7C730.26,348.83 731.59,353.94 734.29,358.02C736.99,362.11 740.66,365.12 745.3,367.05C749.94,368.99 755.16,369.87 760.94,369.7C764.22,369.62 766.99,369.32 769.27,368.81C771.55,368.3 773.3,367.83 774.54,367.41L770.65,349.34C770.01,349.5 769.09,349.69 767.87,349.91Z" style="fill:white;fill-rule:nonzero;"/>
                    <path d="M861.01,276.13C857.14,273.6 852.86,271.78 848.18,270.67C843.5,269.56 838.79,269.01 834.07,269.01C827.21,269.01 820.96,270.02 815.3,272.04C809.64,274.06 804.85,277.09 800.93,281.11C797.01,285.13 794.2,290.12 792.5,296.08L814.08,299.14C815.23,295.78 817.45,292.86 820.75,290.39C824.05,287.92 828.53,286.69 834.19,286.69C839.55,286.69 843.66,288.01 846.51,290.65C849.36,293.29 850.79,297.01 850.79,301.82L850.79,302.2C850.79,304.41 849.97,306.04 848.33,307.08C846.69,308.12 844.08,308.89 840.51,309.38C836.93,309.87 832.27,310.41 826.53,311.01C821.76,311.52 817.16,312.34 812.71,313.47C808.26,314.6 804.27,316.25 800.74,318.42C797.21,320.59 794.42,323.49 792.37,327.11C790.33,330.73 789.31,335.3 789.31,340.84C789.31,347.27 790.75,352.67 793.62,357.06C796.49,361.45 800.41,364.75 805.37,366.99C810.33,369.23 815.92,370.34 822.13,370.34C827.24,370.34 831.69,369.63 835.51,368.2C839.32,366.77 842.49,364.88 845.02,362.52C847.55,360.16 849.52,357.62 850.93,354.89L851.69,354.89L851.69,368.36L873.91,368.36L873.91,302.72C873.91,296.21 872.73,290.78 870.37,286.44C868,282.1 864.88,278.66 861.01,276.13ZM850.85,333.18C850.85,336.8 849.92,340.14 848.07,343.21C846.22,346.27 843.59,348.73 840.19,350.58C836.78,352.43 832.76,353.36 828.12,353.36C823.31,353.36 819.35,352.28 816.24,350.1C813.13,347.92 811.58,344.72 811.58,340.46C811.58,337.48 812.36,335.04 813.94,333.15C815.51,331.26 817.66,329.78 820.39,328.71C823.11,327.65 826.2,326.88 829.65,326.41C831.18,326.2 832.99,325.94 835.08,325.64C837.17,325.34 839.27,325 841.4,324.62C843.53,324.24 845.46,323.79 847.18,323.28C848.9,322.77 850.13,322.22 850.85,321.62L850.85,333.18Z" style="fill:white;fill-rule:nonzero;"/>
                    <path d="M929.52,291.45C933.1,288.92 937.25,287.65 941.97,287.65C947.54,287.65 952,289.24 955.35,292.41C958.69,295.58 960.81,299.53 961.7,304.25L983.79,304.25C983.28,297.18 981.19,291 977.53,285.7C973.87,280.4 968.96,276.29 962.82,273.38C956.67,270.47 949.61,269.01 941.65,269.01C932.07,269.01 923.78,271.15 916.78,275.43C909.78,279.71 904.36,285.67 900.53,293.31C896.7,300.95 894.78,309.75 894.78,319.71C894.78,329.63 896.67,338.39 900.43,345.99C904.2,353.59 909.58,359.54 916.58,363.83C923.58,368.13 931.98,370.28 941.77,370.28C949.99,370.28 957.14,368.78 963.22,365.78C969.31,362.78 974.12,358.61 977.65,353.27C981.18,347.93 983.23,341.79 983.78,334.85L961.7,334.85C961.02,338.38 959.76,341.38 957.93,343.85C956.1,346.32 953.83,348.2 951.13,349.5C948.43,350.8 945.37,351.45 941.97,351.45C937.16,351.45 932.98,350.16 929.42,347.59C925.87,345.02 923.11,341.33 921.15,336.55C919.19,331.76 918.21,326.03 918.21,319.34C918.21,312.74 919.2,307.08 921.18,302.36C923.17,297.62 925.95,293.99 929.52,291.45Z" style="fill:white;fill-rule:nonzero;"/>
                    <path d="M1066.17,368.36L1093.81,368.36L1053.56,312.62L1091.58,270.29L1064.57,270.29L1029.33,309.68L1027.73,309.68L1027.73,237.6L1004.62,237.6L1004.62,368.36L1027.73,368.36L1027.73,335.39L1036.14,326.38L1066.17,368.36Z" style="fill:white;fill-rule:nonzero;"/>
                </g>
            </svg>
        </div>
    </div>
    <script type="text/javascript">
        const loginRoute = '{{ Lit::route('login.post') }}';
        window.doLogin = function(e) {
            e.preventDefault()

            const data = new FormData(document.forms.login);

            let promise = axios.post(loginRoute, data)
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
    @foreach(lit_app()->getLoginScripts() as $src)
        <script src="{{ $src }}"></script>
    @endforeach
@endsection
