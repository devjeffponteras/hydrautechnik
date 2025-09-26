<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

</head>
<title>Untitled Document</title>

<body>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f0f0f0;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    p {
        margin: 10px 0;
        padding: 0;
        font-weight: normal;
    }

    p {
        font-size: 13px;
    }
</style>

<!-- BODY-->
<div style="max-width: 700px; width: 100%; background: #fff;margin: 30px auto;">

    <div style="padding:30px 60px;">
        <div style="text-align: center;padding: 20px 0;">
            <img src="{{ Setting::get_company_logo_storage_path() }}" alt="company logo" width="175" />
        </div>

        <p style="margin-top: 30px;"><strong>Dear {{ $clientInfo['name'] }},</strong></p>

        <p>
            This is to inform you that your inquiry has been sent to our Admin for action.
        </p>

        <p>
            Please expect a response within 24 hours.
        </p>

        <p>
            For your reference, please see details of your inquiry below.
        </p>

        <br />

        <table style="width:100%; padding: 20px;background: #f0f0f0;font-size: 14px;">
            <tbody>
            <tr>
                <td width="30%"><strong>Subject</strong></td>
                <td>{{ $clientInfo['subject'] }}</td>
            </tr>
            <tr>
                <td width="30%"><strong>Name</strong></td>
                <td>{{ $clientInfo['name'] }}</td>
            </tr>
            <tr>
                <td><strong>Email</strong></td>
                <td>{{ $clientInfo['email'] }}</td>
            </tr>
            <tr>
                <td><strong>Contact Number</strong></td>
                <td>{{ $clientInfo['contact'] }}</td>
            </tr>
            <tr>
                <td><strong>Message</strong></td>
                <td>{{ $clientInfo['message'] }}</td>
            </tr>
            @if(isset($clientInfo['company_address']))
            <tr>
                <td><strong>Company Address</strong></td>
                <td>{{ $clientInfo['company_address'] }}</td>
            </tr>
            @endif

            <tr>
                <td><strong>Services</strong></td>
                <td>
                    @foreach($clientInfo['services'] as $key => $service)
                        * {{ $service }}<br/>
                    @endforeach
                </td>
            </tr>
            </tbody>
        </table>

        <br />

        <br />

        <p>
            <strong>
                Regards, <br />
                {{ $setting->company_name }}
            </strong>
        </p>
    </div>

    <div style="padding: 30px;background: #fff;margin-top: 20px;border-top: solid 1px #eee;text-align: center;color: #aaa;">
        <p style="font-size: 12px;">
            <strong>{{ $setting->company_name }}</strong> <br /> {{ $setting->company_address }} <br /> {{ $setting->tel_no }} | {{ $setting->mobile_no }}

            <br /><br /> {{ url('/') }}
        </p>
    </div>
</div>

</body>

</html>
