<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>⏳ Order Pending Confirmation – Desi Kitchen</title>
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #FFA500; /* Slightly lighter orange for pending */
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

        .order-details {
            background-color: #f2f2f2;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }

        .order-details li {
            margin-bottom: 8px;
        }

        .footer {
            padding: 15px;
            text-align: center;
            font-size: 14px;
            color: #777777;
        }

        .item-title {
            width: 100%;
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
            <h1>⏳ Order Pending Confirmation – Desi Kitchen</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Hello <strong>{{ $customerName }}</strong> 🙏,</p>

            <p>Your order <strong>{{ $orderId }}</strong> for 
                <strong>{{ \Carbon\Carbon::parse($orderDate)->format('d M Y') }}</strong> is currently 
                <strong>pending confirmation</strong>.</p>

            <p>Our team will review your order shortly and notify you once it’s confirmed ✅.</p>

            <div class="item-title">
                {!! $alacarteHtml !!}
            </div>

            <p>We appreciate your patience and look forward to serving you soon 🍱</p>

            <p>Warm regards,<br><strong>Team Desi Kitchen</strong></p>
        </div>

        <!-- Footer -->
        <div class="footer">
            &copy; {{ date('Y') }} Desi Kitchen. All rights reserved.
        </div>
    </div>
</body>

</html>
