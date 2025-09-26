<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Meta -->
    <meta name="description" content="">
    <meta name="author">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/img/favicon.png">

    <title>Admin Panel | {{ Setting::info()->company_name }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage').'/icons/'.Setting::getFaviconLogo()->website_favicon }}">

    <!-- vendor css -->
    <link href="{{ asset('lib/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ionicons/css/ionicons.min.css') }}" rel="stylesheet">

    <!-- DashForge CSS -->
    <link href="{{ asset('css/dashforge.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashforge.auth.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom-admin.css') }}" rel="stylesheet">

    <style>
        .signin-hero {
            height: 100vh;
            background: #ebf7ff url('{{ Setting::get_company_logo_storage_path() }}') center center no-repeat;
            background-size: 50%;
        }
    </style>
</head>

<body id="scroll1">

<div class="content-auth">
    <div class="row no-gutters">
        <div class="col-lg-6" style="height:150px !important;">
            <div class="signin-hero" style="max-width: 100%;"></div>
        </div>
        <div class="col-lg-6">
            <div class="sign-wrapper">
                @if($message = Session::get('error'))
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i data-feather="alert-circle" class="mg-r-10"></i> {{ $message }}
                    </div>
                @endif

                @if($message = Session::get('msg'))
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i data-feather="alert-circle" class="mg-r-10"></i> {{ $message }}
                    </div>
                @endif

                <div class="wd-100p">
                    <h3 class="mg-b-3">Forgot Password</h3>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="form-group mg-b-10">
                            <label class="d-block mg-t-20">{{ __('E-Mail Address') }}</label>                            
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">{{ __('Send Password Reset Link') }}</button>
                        <a href="{{route('login')}}" class="btn btn-outline-secondary btn-sm">
                            Cancel
                        </a>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

