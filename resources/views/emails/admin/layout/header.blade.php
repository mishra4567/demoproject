<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body style="margin:0; padding:0; background:#f4f6f9; font-family: Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:#ffffff; border-radius:10px; overflow:hidden;
                           box-shadow: 0 2px 10px rgba(0,0,0,0.08);">

                    {{-- Colored Header Band --}}
                    <tr>
                        <td
                            style="background: linear-gradient(135deg, {{ $headerColorFrom }}, {{ $headerColorTo }});
                                   padding: 35px; text-align:center;">
                            <img src="{{ asset('images/icon/logo.png') }}" alt="Logo" height="45"
                                style="margin-bottom: 15px;"><br>
                            <span style="font-size: 28px;">{{ $headerIcon }}</span>
                            <h1 style="color:#ffffff; margin:10px 0 0; font-size:22px;">
                                {{ $headerTitle }}
                            </h1>
                        </td>
                    </tr>

                    {{-- Body Open --}}
                    <tr>
                        <td style="padding: 35px 40px;">
