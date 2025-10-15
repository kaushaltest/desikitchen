<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>❌ Your Desi Kitchen Order Has Been Cancelled</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            color: #333333;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #FEA116; /* red for cancellation */
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
            line-height: 1.6;
        }
        .content p {
            margin: 10px 0;
        }
        .highlight {
            background-color: #f2f2f2; /* light red background */
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .footer {
            padding: 15px;
            text-align: center;
            font-size: 14px;
            color: #777777;
        }
        @media screen and (max-width: 600px) {
            .container {
                width: 95%;
            }
            .header h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>❌ Your Desi Kitchen Order Has Been Cancelled</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Hello <strong>{{ $customerName }}</strong> 🙏,</p>

            <p>We’re sorry to inform you that your order <strong>#{{ $orderId }}</strong> Placed for <strong>{{ $orderDate }}</strong> has been <strong>cancelled</strong>.</p>
            {!! $reason !!}
            <div class="highlight">
                <p>If the payment was completed, it will be refunded.</p>
                <p>For any concerns or clarification, please contact our support team at <strong>info@desikitchen-ky.com</strong>.</p>
            </div>

            <p>We hope to serve you again soon 🍱</p>
            <p>Thanks,<br><strong>Team Desi Kitchen</strong></p>
        </div>

        <!-- Footer -->
        <div class="footer">
            &copy; {{ date('Y') }} Desi Kitchen. All rights reserved.
        </div>
    </div>
</body>
</html>
