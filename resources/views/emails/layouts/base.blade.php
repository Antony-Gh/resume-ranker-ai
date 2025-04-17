<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <style>
        /* Base Styles */
        body {
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            color: #1a202c;
            line-height: 1.6;
        }
        
        /* Container */
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        
        /* Header */
        .header {
            background: linear-gradient(135deg, #3490dc 0%, #2779bd 100%);
            padding: 24px;
            text-align: center;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }
        
        .logo {
            height: 48px;
            width: auto;
        }
        
        .header-title {
            font-size: 22px;
            font-weight: 600;
            margin: 0;
        }
        
        /* Content */
        .content {
            padding: 32px;
            text-align: center;
        }
        
        .content p {
            margin: 0 0 24px;
            font-size: 16px;
            color: #4a5568;
        }
        
        /* Button */
        .button {
            display: inline-block;
            padding: 12px 24px;
            margin: 20px 0;
            background: #3490dc;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .button:hover {
            background: #2779bd;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        
        /* Footer */
        .footer {
            padding: 24px;
            text-align: center;
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            font-size: 14px;
            color: #718096;
        }
        
        .footer a {
            color: #3490dc;
            text-decoration: none;
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
        
        .security-note {
            margin-top: 16px;
            font-size: 13px;
            color: #a0aec0;
        }
        
        /* Dark Mode */
        @media (prefers-color-scheme: dark) {
            body {
                background: #121212;
                color: #e2e8f0;
            }
            
            .container {
                background: #1e1e1e;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            }
            
            .content p {
                color: #cbd5e0;
            }
            
            .footer {
                background-color: #1a202c;
                border-top-color: #2d3748;
                color: #a0aec0;
            }
            
            .security-note {
                color: #718096;
            }
        }
        
        /* Mobile Responsiveness */
        @media screen and (max-width: 640px) {
            .container {
                margin: 0;
                border-radius: 0;
            }
            
            .header {
                padding: 20px;
            }
            
            .content {
                padding: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        @yield('header')
        <div class="content">
            @yield('content')
        </div>
        @yield('footer')
    </div>

    @if (config('app.env') !== 'production')
        <div style="text-align: center; color: #999; font-size: 11px; margin: 10px auto; max-width: 600px;">
            [Test Environment] This is not a production email
        </div>
    @endif
</body>
</html>