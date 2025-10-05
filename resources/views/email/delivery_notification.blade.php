<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🚚 Your Desi Kitchen Tiffin is on the way!</title>
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
            background-color: #ff9800;
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
            background-color: #f2f2f2;
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
            <h1>🚚 Your Desi Kitchen Tiffin is on the way!</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Hello <strong>{{ $customerName }}</strong> 🙏,</p>

            <p>Your tiffin order {{ $orderId }} is <strong>out for delivery</strong>.</p>

            <div class="highlight">
                <p>Please keep your phone nearby and answer calls from Desi Kitchen 📞 so our driver can reach you.</p>
            </div>

            <p>Enjoy your meal 🌱💚</p>

            <p>Thanks,<br><strong>Team Desi Kitchen</strong></p>
        </div>

        <!-- Footer -->
        <div class="footer">
            &copy; {{ date('Y') }} Desi Kitchen. All rights reserved.
        </div>
    </div>
</body>
</html>
