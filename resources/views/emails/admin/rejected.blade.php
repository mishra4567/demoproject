@include('emails.admin.layout.header', [
    'headerColorFrom' => '#ef5350',
    'headerColorTo' => '#b71c1c',
    'headerIcon' => '❌',
    'headerTitle' => 'Registration Update',
])

<p style="color:#444; font-size:15px; margin:0 0 15px;">
    Hi <strong>{{ $name }}</strong>,
</p>
<p style="color:#666; font-size:14px; line-height:1.7; margin:0 0 25px;">
    Thank you for your interest. Unfortunately, your registration request has been
    <strong style="color:#ef5350;">rejected</strong> after review.
</p>

{{-- Reason Box --}}
<table width="100%" cellpadding="0" cellspacing="0"
    style="background:#fff5f5; border:1px solid #ffcdd2;
               border-radius:8px; margin-bottom:25px;">
    <tr>
        <td style="padding: 20px 25px;">
            <span
                style="color:#b71c1c; font-size:12px;
                             text-transform:uppercase; letter-spacing:1px; font-weight:bold;">
                Reason
            </span>
            <p style="color:#555; font-size:14px; line-height:1.7; margin:8px 0 0;">
                {{ $reason }}
            </p>
        </td>
    </tr>
</table>

<p style="color:#666; font-size:14px; line-height:1.7; margin:0;">
    If you believe this was an error, please contact our support team.
</p>

@include('emails.admin.layout.footer', [
    'footerNote' => 'This email was sent regarding your admin registration request.',
])
