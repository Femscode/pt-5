<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Account Created</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f6f7fb;
            font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, Helvetica Neue, Arial, sans-serif;
        }

        .wrapper {
            width: 100%;
            background: #f6f7fb;
            padding: 24px;
        }

        .header {
            background: #EAF2FF;
            color: #0b5cff;
            padding: 20px 24px;
            font-size: 18px;
            font-weight: 600;
        }

        .container {
            max-width: 680px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .content {
            padding: 24px 0;
            color: #1f2937;
            font-size: 16px;
            line-height: 1.7;
        }

        .cta {
            text-align: center;
            padding: 8px 0 24px;
        }

        .btn {
            display: inline-block;
            background: #0b5cff;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 8px;
            font-weight: 600;
        }

        .muted {
            color: #6b7280;
            font-size: 12px;
        }

        .details {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px;
            margin: 12px 0 20px;
        }

        .details .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px dashed #e5e7eb;
        }

        .details .row:last-child {
            border-bottom: none;
        }

        .label {
            color: #6b7280;
        }

        .value {
            color: #111827;
            font-weight: 600;
        }

        .footer {
            padding: 16px 0;
            color: #6b7280;
            font-size: 12px;
            border-top: 1px solid #f0f2f5;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="header">Welcome to MBI Admin</div>
        <div class="container">
            <div class="content">
                <p>Hello {{ $full_name }},</p>
                <p>Your admin account has been created. Use the credentials below to sign in.</p>
                <div class="details">
                    <div class="row"><span class="label">Email:</span><span class="value">{{ $email }}</span></div>
                    <div class="row"><span class="label">Temporary Password:</span><span class="value">{{ $password }}</span></div>
                </div>
                <div class="cta">
                    <a class="btn" href="{{ $login_url }}" target="_blank" rel="noopener">Login to Admin</a>
                </div>
                <p class="muted">For security, change your password after you sign in.</p>
            </div>
            <div class="footer">If you did not expect this email, you can ignore it.</div>
        </div>
    </div>
</body>

</html>