@extends('theme.main')

@section('content')
    <div class="content-wrap">
        <div class="tabs divcenter nobottommargin clearfix" id="tab-login-register" style="max-width: 500px;">

                <div class="tab-container">
                    @if($message = Session::get('error'))
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i data-feather="alert-circle" class="mg-r-10"></i> {{ $message }}
                        </div>
                    @endif

                    @if($message = Session::get('success'))
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i data-feather="alert-circle" class="mg-r-10"></i> {{ $message }}
                        </div>
                    @endif

                    <div class="tab-content clearfix" id="tab-login">
                        <div class="card nobottommargin">
                            <div class="card-body" style="padding: 40px;">
                                <form id="login-form" name="login-form" class="nobottommargin" action="{{ route('customer-front.customer_login') }}" method="post">
                                    @csrf
                                    <h3>Login to your Account</h3>

                                    <div class="col_full">
                                        <label for="login-form-username">Email:</label>
                                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"/>
                                    </div>

                                    <div class="col_full">
                                        <div class="col_full">
                                            <label for="formGroupExampleInput">Password:</label>
                                            <div class="input-group show_hide_password" id="show_hide_password">
                                                <input class="form-control" type="password" name="password">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><a href=""><i class="icon icon-eye-slash" aria-hidden="true"></i></a></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col_full nobottommargin">
                                        <button type="submit" class="button button-3d button-black nomargin" id="login-form-submit" name="login-form-submit" value="login">Login</button>
                                        <a href="{{ route('customer-front.forgot_password') }}" class="fright">Forgot Password?</a>
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
    <script>
        /** form validations **/
        $(document).ready(function () {
            //called when key is pressed in textbox
            $('#mobile').keypress(function (e) {
                //if the letter is not digit then display error and don't type anything
                var charCode = (e.which) ? e.which : event.keyCode
                if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;

            });
        });

        $(".show_hide_password a").on('click', function(event) {
            event.preventDefault();
            if($(this).parent().parent().siblings('input').attr("type") == "text"){
                $(this).parent().parent().siblings('input').attr('type', 'password');
                $(this).children('i').addClass( "icon-eye-slash" );
                $(this).children('i').removeClass( "icon-eye" );
            }else if($(this).parent().parent().siblings('input').attr("type") == "password"){
                $(this).parent().parent().siblings('input').attr('type', 'text');
                $(this).children('i').removeClass( "icon-eye-slash" );
                $(this).children('i').addClass( "icon-eye" );
            }
        });
    </script>
@endsection

