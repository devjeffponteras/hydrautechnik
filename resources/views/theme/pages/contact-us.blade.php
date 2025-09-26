@extends('theme.main')

@section('pagecss')
@endsection

@section('content')
<div class="container topmargin-lg bottommargin-lg">
    <div class="row">
        <div class="col-lg-4">
            <h3>Leave Us a Message</h3>
            @if(session()->has('success'))
                <div class="style-msg successmsg">
                    <div class="sb-msg"><i class="icon-thumbs-up"></i><strong>Success!</strong> {{ session()->get('success') }}</div>
                    {{-- <button type="button" class="btn-close btn-sm" data-dismiss="alert" aria-hidden="true">&times;</button> --}}
                </div>
            @endif
            
            @if(session()->has('error'))
                <div class="style-msg successmsg">
                    <div class="sb-msg"><i class="icon-thumbs-up"></i><strong>Success!</strong> {{ session()->get('error') }}</div>
                    {{-- <button type="button" class="btn-close btn-sm" data-dismiss="alert" aria-hidden="true">&times;</button> --}}
                </div>
            @endif
            <p><strong>Note:</strong> Please do not leave required fields (*) empty.</p>
            <div class="form-style fs-sm">
                <form id="contactUsForm" action="{{ route('contact-us') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="fullName" class="fs-6 fw-semibold text-initial nols">Full Name *<span class="text-danger">*</span></label>
                        <input type="text" id="fullName" class="form-control form-input" name="name" placeholder="First and Last Name" />
                    </div>

                    <div class="form-group">
                        <label for="emailAddress" class="fs-6 fw-semibold text-initial nols">E-mail Address *<span class="text-danger">*</span></label>
                        <input type="email" id="emailAddress" class="form-control form-input" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" placeholder="hello@email.com" />
                    </div>
                    <div class="form-group">
                        <label for="contactNumber" class="fs-6 fw-semibold text-initial nols">Contact Number <span class="text-danger">*</span></label>
                        <input type="number" id="contactNumber" class="form-control form-input" name="contact" placeholder="Landline or Mobile" />
                    </div>
                    <div class="form-group">
                        <label for="message" class="fs-6 fw-semibold text-initial nols">Message *<span class="text-danger">*</span></label>
                        <textarea name="message" id="message" class="form-control form-input textarea" rows="5"></textarea>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <!-- <a class="button button-circle border-bottom ms-0 text-initial nols fw-normal button-large d-block text-center" href="javascript:void(0)" onclick="document.getElementById('contactUsForm').submit()">Submit</a> -->
                            <button name="submit" type="submit" id="submit-button" tabindex="5" value="Submit" class="button button-3d m-0" href="javascript:void(0)" onclick="document.getElementById('contactUsForm').submit()">
                                <i class="bi-send" style="margin-right: 5px;"></i> Submit
                            </button>
                        </div>
                        <div class="col-md-6">
                            <!-- <a href="javascript:void(0)" class="button button-circle button-dark border-bottom ms-0 text-initial nols fw-normal button-large d-block text-center" onclick="resetForm();">Reset</a> -->
                            <button name="reset" type="reset" id="reset-button" tabindex="5" class="button button-3d m-0 reset-button" href="javascript:void(0)" onclick="resetForm();">
                                <i class="bi-arrow-counterclockwise" style="margin-right: 5px;"></i>Reset
                            </button>
                        </div>
                    </div>
                    
                    {{-- hidden inputs --}}
                    <div class="form-group" style="display:none;">
                        <input type="text" id="services" class="form-control form-input" name="services" placeholder="Enter Subject" value="Design" required/>
                        <input type="text" id="subject" class="form-control form-input" name="subject" placeholder="Enter Subject" value="Design" required/>
                    </div>

                </form>
                {{-- captcha script --}}
                <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            </div>

        </div>
        <div class="col-lg-8 mb-5">
            {!! $page->contents !!}
        </div>
    </div>
    
    <div class="row contact-details">
        <div class="col-lg-4 my-2">
            <div class="feature-box fbox-center fbox-bg fbox-plain">
                <div class="fbox-icon">
                    <a href="#"><i class="uil uil-map-marker"></i></a>
                </div>
                <div class="fbox-content">
                    <h3>Main Office
                        <span class="subtitle">
                            Rm. 301, 3rd Floor, Right Wing, Electoral Tribunal Building
                            Commonwealth Avenue, Quezon City
                        </span>
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-lg-4 my-2">
            <div class="feature-box fbox-center fbox-bg fbox-plain">
                <div class="fbox-icon">
                    <a href="#"><i class="bi-telephone"></i></a>
                </div>
                <div class="fbox-content">
                    <h3>Speak to Us
                        <span class="subtitle">(+632) 931 7642</span>
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-lg-4 my-2">
            <div class="feature-box fbox-center fbox-bg fbox-plain">
                <div class="fbox-icon">
                    <a href="#"><i class="bi-envelope"></i></a>
                </div>
                <div class="fbox-content">
                    <h3>Email Us
                        <span class="subtitle">isjrms@hret.gov.ph</span>
                    </h3>
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
        $("#contact").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            var charCode = (e.which) ? e.which : event.keyCode
            if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;

        });
    });

    // $('#contactUsForm').submit(function (evt) {
    //     let recaptcha = $("#g-recaptcha-response").val();
    //     if (recaptcha === "") {
    //         evt.preventDefault();
    //         $('#catpchaError').show();
    //         return false;
    //     }
    // });
    
    function resetForm() {
        document.getElementById("contactUsForm").reset();
    }
</script>
@endsection
