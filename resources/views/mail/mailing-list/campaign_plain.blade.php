{!! $campaign->content !!}

<hr>

Unsubscribe: {{ route('mailing-list.front.unsubscribe', ['subscriber' => $subscriber->id, 'code' => $subscriber->code]) }}

{{ $setting->company_name }}
{{ $setting->company_address }}
{{ $setting->tel_no }} | {{ $setting->mobile_no }}

{{ url('/') }}
