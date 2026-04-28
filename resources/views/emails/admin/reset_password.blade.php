@include('emails.admin.layout.header', [
    'headerColorFrom' => '#9C27B0',
    'headerColorTo' => '#4A148C',
    'headerIcon' => '🔑',
    'headerTitle' => 'Reset Your Password',
])

<p style="color:#444; font-size:15px; margin:0 0 15px;">
    Hi <strong>{{ $name }}</strong>,
</p>
<p style="color:#666; font-size:14px; line-height:1.7; margin:0 0 25px;">
    We received a request to reset your admin account password.
    Click the button below to set a new password.
</p>

{{-- Reset Button --}}
<div style="text-align:center; margin: 30px 0;">
    <a href="{{ $reset_url }}"
        style="background: linear-gradient(135deg, #9C27B0, #4A148C);
                   color: #ffffff; padding: 14px 40px;
                   border-radius: 6px; text-decoration: none;
                   font-size: 15px; font-weight: bold;
                   display: inline-block;">
        Reset Password →
    </a>
</div>

{{-- Warning box --}}
<table width="100%" cellpadding="0" cellspacing="0"
    style="background:#fff3e0; border-left: 4px solid #FF9800;
               border-radius:4px; margin-top: 20px;">
    <tr>
        <td style="padding: 15px 20px;">
            <p style="color:#795548; font-size:13px; margin:0; line-height:1.7;">
                ⚠️ This link will expire in <strong>{{ $expiry }}</strong>.<br>
                If you did not request a password reset, please ignore this email.
                Your password will not be changed.
            </p>
        </td>
    </tr>
</table>

@include('emails.admin.layout.footer', [
    'footerNote' => 'This email was sent because a password reset was requested for your account.',
])
