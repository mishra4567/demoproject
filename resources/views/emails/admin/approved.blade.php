@include('emails.admin.layout.header', [
    'headerColorFrom' => '#4CAF50',
    'headerColorTo' => '#2E7D32',
    'headerIcon' => '🎉',
    'headerTitle' => 'Account Approved!',
])

<p style="color:#444; font-size:15px; margin:0 0 15px;">
    Hi <strong>{{ $name }}</strong>,
</p>
<p style="color:#666; font-size:14px; line-height:1.7; margin:0 0 25px;">
    Great news! Your admin account has been reviewed and
    <strong style="color:#4CAF50;">approved</strong>.
    You can now log in to the admin panel using your credentials.
</p>

{{-- Login Details Box --}}
<table width="100%" cellpadding="0" cellspacing="0"
    style="background:#f9fafb; border:1px solid #e5e7eb;
               border-radius:8px; margin-bottom:25px;">
    <tr>
        <td style="padding: 18px 20px; border-bottom: 1px solid #e5e7eb;">
            <span style="color:#999; font-size:12px; text-transform:uppercase; letter-spacing:1px;">User ID</span><br>
            <strong style="color:#333; font-size:15px;">#{{ $id }}</strong>
        </td>
    </tr>
    <tr>
        <td style="padding: 18px 20px; border-bottom: 1px solid #e5e7eb;">
            <span style="color:#999; font-size:12px; text-transform:uppercase; letter-spacing:1px;">
                Username
            </span><br>
            <strong style="color:#333; font-size:15px;">{{ $username }}</strong>
        </td>
    </tr>
    <tr>
        <td style="padding: 18px 20px; border-bottom: 1px solid #e5e7eb;">
            <span style="color:#999; font-size:12px; text-transform:uppercase; letter-spacing:1px;">Email</span><br>
            <strong style="color:#333; font-size:15px;">{{ $email }}</strong>
        </td>
    </tr>
    <tr>
        <td style="padding: 18px 20px; border-bottom: 1px solid #e5e7eb;">
            <span style="color:#999; font-size:12px; text-transform:uppercase; letter-spacing:1px;">
                Password
            </span><br>
            <strong
                style="color:#333; font-size:15px;
                           background:#fff3e0; padding: 4px 10px;
                           border-radius:5px; font-family:monospace;">
                {{ $password }}
            </strong>
            <br>
            <span style="color:#e65100; font-size:11px;">
                ⚠️ Please change your password after first login.
            </span>
        </td>
    </tr>
    <tr>
        <td style="padding: 18px 20px;">
            <span style="color:#999; font-size:12px; text-transform:uppercase; letter-spacing:1px;">Role</span><br>
            <span
                style="background:#e8f5e9; color:#2E7D32;
                             padding: 3px 10px; border-radius:20px; font-size:13px;">
                {{ $role }}
            </span>
        </td>
    </tr>
</table>

{{-- Login Button --}}
<div style="text-align:center; margin: 30px 0;">
    <a href="{{ $login_url }}"
        style="background: linear-gradient(135deg, #4CAF50, #2E7D32);
                   color: #ffffff; padding: 14px 40px; border-radius: 6px;
                   text-decoration: none; font-size: 15px; font-weight: bold;
                   display: inline-block;">
        Login to Admin Panel →
    </a>
</div>

<p style="color:#999; font-size:12px; text-align:center; margin:0;">
    Use your registered email and password to log in.
</p>

@include('emails.admin.layout.footer', [
    'footerNote' => 'This email was sent regarding your admin account approval.',
])
