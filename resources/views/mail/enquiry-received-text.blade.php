New {{ mb_strtolower($enquiry->type->label()) }} - {{ config('app.name') }}

@if ($enquiry->product_name)
Product: {{ $enquiry->product_name }}
@endif
Name: {{ $enquiry->name }}
Phone: {{ $enquiry->phone }}
@if ($enquiry->email)
Email: {{ $enquiry->email }}
@endif
@if ($enquiry->company)
Company: {{ $enquiry->company }}
@endif
@if ($enquiry->city)
City: {{ $enquiry->city }}
@endif
@if ($enquiry->subject)
Subject: {{ $enquiry->subject }}
@endif

@if ($enquiry->message)
Message:
{{ $enquiry->message }}
@endif

Open in admin panel: {{ $adminUrl }}
