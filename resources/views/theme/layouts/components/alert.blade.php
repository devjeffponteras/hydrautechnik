<!-- #alert start -->
<div class="container">
    <div class="row justify-content-center"> <!-- Center the column -->
        <div class="col-md-6">
            @if($message = Session::get('error'))
                <div id="errorAlert" class="alert alert-danger alert-dismissible fade show" role="alert" style="position: fixed; top: 35%; left: 50%; transform: translateX(-50%); z-index: 1000;"> <!-- Adjusted position -->
                    <i data-feather="alert-circle" class="mg-r-10"></i> {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <script>
                    setTimeout(function(){
                        $('#errorAlert').fadeOut('slow');
                    }, 3500);
                </script>
            @endif

            @if($message = Session::get('success'))
                <div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert" style="position: fixed; top: 35%; left: 50%; transform: translateX(-50%); z-index: 1000;"> <!-- Adjusted position -->
                    <i data-feather="alert-circle" class="mg-r-10"></i> {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <script>
                    setTimeout(function(){
                        $('#successAlert').fadeOut('slow');
                    }, 3500);
                </script>
            @endif
        </div>
    </div>
</div>
<!-- #alert end -->
