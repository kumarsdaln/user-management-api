<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
</head>

<body style="font-family: Arial, sans-serif; background:#f4f4f4; padding:20px;">

    <table width="600" align="center" style="background:white;padding:30px;border-radius:6px">

        <tr>
            <td align="center">
                <h2 style="color:#333">Password Reset Request</h2>
            </td>
        </tr>

        <tr>
            <td>
                <p>Hello {{ $user->name }},</p>

                <p>
                    We received a request to reset your password for your account.
                    Click the button below to reset your password.
                </p>

            </td>
        </tr>

        <tr>
            <td align="center" style="padding:20px">

                <a href="{{url('/') }}/reset-password/confirm?token={{ $token }}&email={{ urlencode($user->email) }}"
                    style="background:#2563eb;color:white;padding:12px 25px;text-decoration:none;border-radius:5px;font-weight:bold">
                    Reset Password
                </a>

            </td>
        </tr>

        <tr>
            <td>

                <p>If you did not request this password reset, please ignore this email.</p>

                <p>This link will expire in 15 minutes.</p>

            </td>
        </tr>

        <tr>
            <td style="border-top:1px solid #eee;padding-top:20px;font-size:12px;color:#888">

                <p>
                    Regards,<br>
                    User Management API
                </p>

            </td>
        </tr>

    </table>

</body>

</html>