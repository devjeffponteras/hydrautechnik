<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    </head>
    <body style="background:#FFFFFF;font-family:arial;">
    <p>&nbsp;</p>
    <table style="width:850px;margin:auto;background:#fff;border:1px solid #dddddd;padding:1em;-webkit-border-radius:5px;border-radius:5px;font-size:12px;">
        <tr>
            <td>
                <a href="{{ url('/') }}">
                    <img src="{{ asset('storage').'/logos/'.$setting->company_logo }}" alt="Sysu" width="175" />
                </a>
            </td>
        </tr>
        <tr>
            <td>
                Dear {{$h->user->firstname}},
                <br>
                <br>
                <br>
                Good Day!
                <br>
                <br>
                <br>
                We are happy to notify you that your order <strong># {{ $h->order_number }}</strong> status has changed.
                <br>
                <br>
                <br>
                    Status: <strong>{{ $h->delivery_status }}</strong>
                <br>
                @if($h->cancellation_request == 1 || optional($h->deliveries->last())->remarks)
                    Remarks: <strong>{{ ($h->cancellation_request == 1 ? $h->cancellation_reason : optional($h->deliveries->last())->remarks) }}</strong>
                    <br>
                    @if($h->cancellation_request == 1)
                        Reason: <strong>{{ $h->cancellation_reason }}</strong>
                    @endif
                @endif
                <br>
                <br>
                <p>
                    Please stay tuned for more updates!
                </p>
                <br>
                <br>
                Thank you.
                <br>
                <br>
                <p>
                    <strong>
                        Regards, <br />
                        {{ $setting->company_name }}
                    </strong>
                </p>
            </td>
        </tr>
    </table>
    </body>
</html>