<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Seller Application Update</title>
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

        .label {
            color: #6b7280;
            font-weight: 600;
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
        <div class="header">Your Seller Application Status</div>
        <div class="container">
            <div class="content">
                <p>Hello {{ $full_name }},</p>
                <p>Thank you for your interest in joining the My Bridge Marketplace.
                    After reviewing your application, we were unable to approve it at this time.
                </p>
                <div class="details">
                    <div class="label">Reason</div>
                    <div>{{ $reason }}</div>
                </div>
               
                <p class="muted">If you would like to resubmit your application or need further clarification, don't hesitate to get in touch with our support team.</p>
                 <div class="cta">
                    <a class="btn" href="{{ $support_url }}" target="_blank" rel="noopener">Contact Support</a>
                </div>
            </div>
            <p class="muted">Kind Regards,</p>
            <p>My Bridge Marketplace Team</p>
            <div class="footer">If you did not expect this email, you can ignore it.</div>
        </div>
    </div>
</body>

</html>