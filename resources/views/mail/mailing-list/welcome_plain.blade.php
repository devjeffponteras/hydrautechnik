You are receiving this email because you are subscribed to {{ $setting->website_name }}

We value your privacy and you may click on the Privacy Policy link below.
{{ route('privacy-policy') }}


If you wish to unsubscriber from our list, you may click unsubscribe link below.
{{ route('mailing-list.front.unsubscribe', ['subscriber' => $subscriber->id, 'code' => $subscriber->code]) }}



{{ $setting->company_name }}
{{ $setting->company_address }}
{{ $setting->tel_no }} | {{ $setting->mobile_no }}

{{ url('/') }}
