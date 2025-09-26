@php
    $web = App\Models\Setting::first();
    $third_party_signins = explode(",",$web->third_party_signin);
@endphp

@if(in_array('facebook', $third_party_signins))
    <a href="{{ route('login.provider', ['provider' => 'facebook']) }}" class="button button-large w-100 si-colored si-facebook nott fw-normal ls0 center m-0 mb-2"><i class="icon-facebook-sign"></i> Log in with Facebook</a>
@endif

@if(in_array('google', $third_party_signins))
    <a href="{{ route('login.provider', ['provider' => 'google']) }}" class="button button-large w-100 si-colored si-google nott fw-normal ls0 center m-0"><i class="icon-google"></i> Log in with Google</a>
@endif

@if($web->third_party_signin != '')
    <div class="divider divider-center"><span class="position-relative" style="top: -2px">OR</span></div>
@endif
