</td>
</tr>

{{-- Footer --}}
<tr>
    <td
        style="background:#f9fafb; padding:20px 40px; text-align:center;
                                   border-top: 1px solid #e5e7eb;">
        <p style="color:#aaa; font-size:12px; margin:0;">
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
            {{ $footerNote ?? 'If you did not request this, please ignore this email.' }}
        </p>
    </td>
</tr>

</table>
</td>
</tr>
</table>

</body>

</html>
