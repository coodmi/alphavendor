<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f6fa; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .container { text-align: center; padding: 40px; }
        .code { font-size: 96px; font-weight: 800; color: #e74c3c; line-height: 1; }
        .title { font-size: 24px; font-weight: 600; color: #2c3e50; margin: 16px 0 8px; }
        .message { color: #7f8c8d; font-size: 15px; margin-bottom: 32px; }
        .btn { display: inline-block; padding: 12px 28px; background: #0d5c63; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; }
        .btn:hover { background: #0a4a52; }
    </style>
</head>
<body>
    <div class="container">
        <div class="code">500</div>
        <div class="title">Server Error</div>
        <div class="message">Something went wrong. Please try again later.</div>
        <a href="javascript:history.back()" class="btn">Go Back</a>
    </div>
</body>
</html>
