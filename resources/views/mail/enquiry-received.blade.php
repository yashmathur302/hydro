{{-- New enquiry notification (HTML). All visitor values are escaped by Blade and never parsed as Markdown. --}}
@php
    $rows = array_filter([
        'Product' => $enquiry->product_name,
        'Name' => $enquiry->name,
        'Phone' => $enquiry->phone,
        'Email' => $enquiry->email,
        'Company' => $enquiry->company,
        'City' => $enquiry->city,
        'Subject' => $enquiry->subject,
        'Received' => $enquiry->created_at->format('d M Y, h:i A'),
    ]);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $enquiry->type->label() }}</title>
</head>
<body style="margin:0;padding:24px 12px;background:#f3f3f4;font-family:Arial,Helvetica,sans-serif;color:#0f0f10;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;margin:0 auto;background:#ffffff;border:1px solid #e7e7ea;border-radius:14px;">
        <tr>
            <td style="padding:28px 28px 8px;">
                <p style="margin:0 0 4px;font-size:13px;color:#77777f;">{{ config('app.name') }}</p>
                <h1 style="margin:0;font-size:20px;font-weight:600;">New {{ mb_strtolower($enquiry->type->label()) }}</h1>
            </td>
        </tr>
        <tr>
            <td style="padding:16px 28px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;">
                    @foreach ($rows as $label => $value)
                        <tr>
                            <td style="padding:10px 0;border-bottom:1px solid #ededef;color:#5c5c66;width:110px;vertical-align:top;">{{ $label }}</td>
                            <td style="padding:10px 0;border-bottom:1px solid #ededef;font-weight:600;">{{ $value }}</td>
                        </tr>
                    @endforeach
                </table>
                @if ($enquiry->message)
                    <p style="margin:20px 0 6px;font-size:13px;color:#5c5c66;">Message</p>
                    <p style="margin:0;font-size:14px;line-height:1.6;white-space:pre-line;">{{ $enquiry->message }}</p>
                @endif
            </td>
        </tr>
        <tr>
            <td style="padding:8px 28px 28px;">
                <a href="{{ $adminUrl }}" style="display:inline-block;padding:12px 20px;background:#0f0f10;color:#ffffff;text-decoration:none;border-radius:999px;font-size:14px;">Open in admin panel</a>
            </td>
        </tr>
    </table>
</body>
</html>
