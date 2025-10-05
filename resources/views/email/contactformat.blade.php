<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>New Contact Message</title>
    <style>
        body {
            background: #f4f4f4;
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: #4CAF50;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .header h2 {
            margin: 0;
            font-size: 22px;
        }

        .content {
            padding: 25px;
        }

        .content p {
            margin: 8px 0;
            font-size: 15px;
            color: #333;
        }

        .label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 90px;
        }

        .message {
            margin-top: 20px;
            padding: 15px;
            background: #fafafa;
            border-left: 4px solid #4CAF50;
            white-space: pre-line;
            /* preserves line breaks */
        }

        .footer {
            text-align: center;
            padding: 15px;
            background: #eee;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>New Contact Message</h2>
        </div>

        <div class="content">
            <p><span class="label">Name:</span> {{ $name }}</p>
            <p><span class="label">Email:</span> {{ $email }}</p>
            <p><span class="label">Subject:</span> {{ $subject }}</p>
            <p><span class="label">Phone:</span> {{ $phone ?? 'N/A' }}</p>

            <div class="message">
                {{ $body }}
            </div>
        </div>

        <div class="footer">
            © {{ date('Y') }} Desi Kitchen – Contact Form
        </div>
    </div>
</body>

</html>