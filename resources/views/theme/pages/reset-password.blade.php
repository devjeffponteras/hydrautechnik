@extends('theme.main')

@section('content')
    <div class="content-wrap">
    <div class="container clearfix">

        <div class="tabs mx-auto mb-0 clearfix" id="tab-login-register" style="max-width: 500px;">
            <div class="tab-container">
                @if(session('error'))
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i data-feather="alert-circle" class="mg-r-10"></i> {{ session('error') }}
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i data-feather="check-circle" class="mg-r-10"></i> {{ session('status') }}
                    </div>
                @endif

                <div class="card mb-0">
                    <div class="card-body" style="padding: 40px;">
                        <form class="mb-0" action="{{ route('customer-front.reset_password_post') }}" method="post">
                            @csrf
                            <h3>Reset Password</h3>
                            <div class="row">
                                <input type="hidden" name="token" value="{{$token}}">
                                <div class="col-12 form-group">
                                    <label for="login-form-username">Email:</label>
                                    <input type="email" id="email" name="email" value="{{$email}}" class="form-control" readonly/>
                                </div>

                                <div class="col-12 form-group">
                                    <label for="login-form-username">Password:</label>
                                    <input type="password" id="password" name="password" value="" class="form-control @error('password') is-invalid @enderror"/>
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <p class="form-text small text-muted"> Minimum of eight (8) alphanumeric characters (combination of letters and numbers) with at least one (1) upper case and one (1) special character.</p>
                                </div>

                                <div class="col-12 form-group">
                                    <label for="login-form-username">Confirm Password:</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" value="" class="form-control @error('password_confirmation') is-invalid @enderror"/>
                                    @error('password_confirmation')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12 form-group">
                                    <button type="submit" class="button button-3d button-black m-0">Submit</button>
                                    <a href="{{ route('customer-front.login') }}" class="float-end">Back to Login</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
@endsection

