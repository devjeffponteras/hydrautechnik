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
                        <form class="mb-0" action="{{ route('customer-front.send_reset_link_email') }}" method="post">
                            @csrf
                            <h3>Forgot Password</h3>
                            <p>Enter the e-mail address associated with your account. Click submit to have a password reset link e-mailed to you.</p>

                            <div class="row">
                                <div class="col-12 form-group">
                                    <label for="login-form-username">Email:</label>
                                    <input type="email" id="email" name="email" value="" class="form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required/>
                                </div>

                                <div class="col-12 form-group">
                                    <button type="submit" class="button button-3d button-black m-0" id="login-form-submit" name="login-form-submit" value="login">Submit</button>
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

