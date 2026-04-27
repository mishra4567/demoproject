@include('emails.admin.layout.header', [
    'headerColorFrom' => '#FF9800',
    'headerColorTo' => '#E65100',
    'headerIcon' => '⚠️',
    'headerTitle' => 'Account Suspended',
])

<p style="color:#444; font-size:15px; margin:0 0 15px;">
    Hi <strong>{{ $name }}</strong>,
</p>
<p style="color:#666; font-size:14px; line-height:1.7; margin:0 0 25px;">
    Your admin account has been
    <strong style="color:#FF9800;">suspended</strong>.
    You will not be able to log in until this suspension is lifted.
</p>

{{-- Account + Reason Box --}}
<table width="100%" cellpadding="0" cellspacing="0"
    style="background:#fff8e1; border:1px solid #ffe082;
               border-radius:8px; margin-bottom:25px;">
    <tr>
        <td style="padding: 18px 20px; border-bottom: 1px solid #ffe082;">
            <span
                style="color:#e65100; font-size:12px;
                             text-transform:uppercase; letter-spacing:1px; font-weight:bold;">
                Account
            </span><br>
            <strong style="color:#333; font-size:15px;">
                #{{ $id }} — {{ $email }}
            </strong>
        </td>
    </tr>
    <tr>
        <td style="padding: 18px 20px;">
            <span
                style="color:#e65100; font-size:12px;
                             text-transform:uppercase; letter-spacing:1px; font-weight:bold;">
                Reason
            </span>
            <p style="color:#555; font-size:14px; line-height:1.7; margin:8px 0 0;">
                {{ $reason }}
            </p>
        </td>
    </tr>
</table>

{{-- Warning Note --}}
<table width="100%" cellpadding="0" cellspacing="0"
    style="background:#fff3e0; border-left: 4px solid #FF9800; border-radius:4px;">
    <tr>
        <td style="padding: 15px 20px;">
            <p style="color:#795548; font-size:13px; margin:0; line-height:1.6;">
                ⚠️ <strong>Note:</strong>
                Any attempt to create a new account while suspended
                may result in a permanent ban.
            </p>
        </td>
    </tr>
</table>

@include('emails.admin.layout.footer', [
    'footerNote' => 'This email was sent regarding your admin account status.',
])
