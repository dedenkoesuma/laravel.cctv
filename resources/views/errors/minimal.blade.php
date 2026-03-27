<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <style>
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            max-width: 500px;
        }
        .error-code {
            font-size: 72px;
            font-weight: 700;
            color: #ef4444;
            margin-bottom: 16px;
        }
        .error-message {
            font-size: 24px;
            color: #1f2937;
            margin-bottom: 24px;
        }
        .error-description {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 32px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-code">@yield('code')</div>
        <div class="error-message">@yield('message')</div>
        @hasSection('description')
            <div class="error-description">@yield('description')</div>
        @endif
        <a href="{{ url('/') }}" class="btn">← Back to Home</a>
    </div>
</body>
</html>