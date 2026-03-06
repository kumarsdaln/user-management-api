<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
</head>

<body style="margin:0;padding:0;background:#f4f6fb;font-family:Arial, Helvetica, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 3px 12px rgba(0,0,0,0.08);">

                    <!-- Header -->
                    <tr>
                        <td
                            style="background:#2563eb;color:#ffffff;padding:22px;text-align:center;font-size:22px;font-weight:bold;">
                            Welcome to Our Platform
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding:30px;color:#333;font-size:15px;line-height:1.6;">

                            <p>Hello <strong>{{ $user->name }}</strong>,</p>

                            <p>Your account has been successfully created. Here are your account details:</p>

                            <!-- User Details -->
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="margin:20px 0;border-collapse:collapse;">
                                <tr>
                                    <td style="padding:10px;border:1px solid #eee;background:#f9fafb;">
                                        <strong>Name</strong></td>
                                    <td style="padding:10px;border:1px solid #eee;">{{ $user->name }}</td>
                                </tr>

                                <tr>
                                    <td style="padding:10px;border:1px solid #eee;background:#f9fafb;">
                                        <strong>Email</strong></td>
                                    <td style="padding:10px;border:1px solid #eee;">{{ $user->email }}</td>
                                </tr>
                            </table>

                            <p>You can now login to your account and start using the platform.</p>

                            <!-- CTA Button -->
                            <div style="text-align:center;margin:30px 0;">
                                <a href="{{ url('/login') }}"
                                    style="background:#2563eb;color:#ffffff;padding:12px 28px;text-decoration:none;border-radius:6px;font-size:15px;font-weight:bold;display:inline-block;">
                                    Login to Your Account
                                </a>
                            </div>

                            <p>If you did not create this account, please ignore this email.</p>

                            <p>
                                Thanks,<br>
                                <strong>Team</strong>
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="background:#f9fafb;padding:18px;text-align:center;font-size:12px;color:#777;border-top:1px solid #eee;">
                            © {{ date('Y') }} UMS. All rights reserved.<br>
                            This is an automated email, please do not reply.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>