@include('emails.admin.layout.header', [
    'headerColorFrom' => '#2196F3',
    'headerColorTo' => '#0D47A1',
    'headerIcon' => '✉️',
    'headerTitle' => 'Verify Your Email',
])

<p style="color:#444; font-size:15px; margin:0 0 15px;">
    Hi <strong>{{ $name }}</strong>,
</p>
<p style="color:#666; font-size:14px; line-height:1.7; margin:0 0 25px;">
    Thank you for registering. Please verify your email address
    by clicking the button below to complete your registration.
</p>

{{-- Info Box --}}
<table width="100%" cellpadding="0" cellspacing="0"
    style="background:#f9fafb; border:1px solid #e5e7eb;
               border-radius:8px; margin-bottom:25px;">
    <tr>
        <td style="padding: 18px 20px; border-bottom: 1px solid #e5e7eb;">
            <span
                style="color:#999; font-size:12px;
                             text-transform:uppercase; letter-spacing:1px;">
                Name
            </span><br>
            <strong style="color:#333; font-size:15px;">{{ $name }}</strong>
        </td>
    </tr>
    <tr>
        <td style="padding: 18px 20px;">
            <span
                style="color:#999; font-size:12px;
                             text-transform:uppercase; letter-spacing:1px;">
                Email
            </span><br>
            <strong style="color:#333; font-size:15px;">{{ $email }}</strong>
        </td>
    </tr>
</table>

{{-- Verify Button --}}
<div style="text-align:center; margin: 30px 0;">
    <a href="{{ $verification_link }}"
        style="background: linear-gradient(135deg, #2196F3, #0D47A1);
                   color: #ffffff; padding: 14px 40px;
                   border-radius: 6px; text-decoration: none;
                   font-size: 15px; font-weight: bold;
                   display: inline-block;">
        Verify Email Address →
    </a>
</div>

{{-- What happens next --}}
<table width="100%" cellpadding="0" cellspacing="0"
    style="background:#e3f2fd; border-left: 4px solid #2196F3;
               border-radius:4px; margin-top: 20px;">
    <tr>
        <td style="padding: 15px 20px;">
            <p style="color:#1565C0; font-size:13px; margin:0 0 8px;">
                <strong>What happens next?</strong>
            </p>
            <p style="color:#555; font-size:13px; margin:0; line-height:1.7;">
                1. Click the button above to verify your email.<br>
                2. A super admin will review and approve your account.<br>
                3. You will receive another email with your login details once approved.
            </p>
        </td>
    </tr>
</table>

<p style="color:#999; font-size:12px; text-align:center; margin-top: 25px;">
    This link will expire in <strong>24 hours</strong>.
    If you did not register, please ignore this email.
</p>

@include('emails.admin.layout.footer', [
    'footerNote' => 'This email was sent because you registered for an admin account.',
])
