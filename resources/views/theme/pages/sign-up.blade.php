@extends('theme.main')

@section('content')
    <div class="content-wrap">
    <div class="container clearfix">

        <div class="tabs mx-auto mb-0 clearfix" id="tab-login-register" style="max-width: 500px;">
            <div class="tab-container">
                @if($message = Session::get('error'))
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i data-feather="alert-circle" class="mg-r-10"></i> {{ $message }}
                    </div>
                @endif

                <div class="card mb-0">
                    <div class="card-body" style="padding: 40px;">
                        <h3>Register for an Account</h3>

                        <form id="register-form" name="register-form" class="row mb-0" action="{{ route('customer-front.customer-sign-up') }}" method="post">
                            @csrf
                            <div class="col-12 form-group">
                                <label for="register-form-name">Firstname:</label>
                                <input type="text" id="firstname" name="firstname" value="{{ old('firstname') }}" class="form-control @error('firstname') is-invalid @enderror" />
                                @error('firstname')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-12 form-group">
                                <label for="register-form-name">Lastname:</label>
                                <input type="text" id="lastname" name="lastname" value="{{ old('lastname') }}" class="form-control @error('lastname') is-invalid @enderror" />
                                @error('lastname')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-12 form-group">
                                <label for="register-form-email">Email Address:</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"/>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-12 form-group">
                                <label for="mobile">Mobile No.:</label>
                                <input type="text" id="mobile" name="mobile" value="{{ old('mobile') }}" class="form-control @error('mobile') is-invalid @enderror" />
                                @error('mobile')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-12 form-group">
                                <label for="mobile">Company:</label>
                                <input type="text" id="company" name="company" value="{{ old('company') }}" class="form-control @error('company') is-invalid @enderror" />
                                @error('company')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-12 form-group">
                                <label for="register-form-password">Choose Password:</label>
                                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" />
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-12 form-group">
                                <label for="register-form-repassword">Re-enter Password:</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" value="" class="form-control @error('password_confirmation') is-invalid @enderror" />
                                @error('password_confirmation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-12 form-group">
                                <script src="https://www.google.com/recaptcha/api.js?hl=en" async="" defer="" ></script>
                                <div class="g-recaptcha" data-sitekey="{{ Setting::info()->google_recaptcha_sitekey }}"></div>
                                <label class="control-label text-danger" for="g-recaptcha-response" id="catpchaError" style="display:none;font-size: 14px;"><i class="fa fa-times-circle-o"></i>The Captcha field is required.</label></br>
                                @if($errors->has('g-recaptcha-response'))
                                    @foreach($errors->get('g-recaptcha-response') as $message)
                                        <label class="control-label text-danger" for="g-recaptcha-response"><i class="fa fa-times-circle-o"></i>{{ $message }}</label></br>
                                    @endforeach
                                @endif
                            </div>

                            <div class="col-12 form-group">
                                <button type="submit" class="button button-3d button-black m-0" id="register-form-submit" name="register-form-submit" value="register">Register Now</button>
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

