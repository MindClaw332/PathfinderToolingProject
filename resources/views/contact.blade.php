<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact - Pathfinder 2e Combat Manager</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --color-primary: #000917;
            --color-secondary: #00162E;
            --color-tertiary: #002445;
            --color-accent: #AE8708;
            --color-text: #1b1b18;
            --color-bg: #FDFDFC;
            --color-card: #ffffff;
            --color-dark-text: #EDEDEC;
            --color-dark-bg: #0a0a0a;
            --color-dark-card: #161615;
        }

        body {
            background: var(--color-bg);
            color: var(--color-text);
            font-family: 'Instrument Sans', sans-serif;
            margin: 0;
            padding: 0;
        }

        .dark body {
            background: var(--color-dark-bg);
            color: var(--color-dark-text);
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }

        .header {
            background: var(--color-secondary);
            color: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0, 9, 23, 0.3);
        }

        .contact-info {
            background: var(--color-card);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .dark .contact-info {
            background: var(--color-dark-card);
        }

        .contact-section {
            margin-bottom: 2rem;
        }

        .contact-section h2 {
            color: var(--color-accent);
            margin-bottom: 1rem;
        }

        .social-links {
            display: flex;
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .social-links a {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--color-text);
            text-decoration: none;
            padding: 0.75rem 1rem;
            background: rgba(0,0,0,0.03);
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .dark .social-links a {
            color: var(--color-dark-text);
            background: rgba(255,255,255,0.05);
        }

        .social-links a:hover {
            transform: translateY(-2px);
            background: var(--color-accent);
            color: var(--color-primary);
        }

        .social-links i {
            font-size: 1.5rem;
        }

        .support-card {
            background: rgba(0,0,0,0.03);
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 2rem;
        }

        .dark .support-card {
            background: rgba(255,255,255,0.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Contact Us</h1>
            <p>Connect with us through various platforms</p>
        </div>

        <div class="contact-info">
            <div class="contact-section">
                <h2>Community</h2>
                <p>Join our community to discuss features, report bugs, or share your experience:</p>
                
                <div class="social-links">
                    <a href="https://discord.gg/yourdiscord" target="_blank">
                        <i class="fab fa-discord"></i>
                        <span>Discord Server</span>
                    </a>
                    <a href="https://github.com/yourrepo" target="_blank">
                        <i class="fab fa-github"></i>
                        <span>GitHub</span>
                    </a>
                </div>
            </div>

            <div class="contact-section">
                <h2>Social Media</h2>
                <p>Follow us for updates and announcements:</p>
                
                <div class="social-links">
                    <a href="https://twitter.com/yourhandle" target="_blank">
                        <i class="fab fa-twitter"></i>
                        <span>Twitter</span>
                    </a>
                    <a href="https://reddit.com/r/yoursubreddit" target="_blank">
                        <i class="fab fa-reddit"></i>
                        <span>Reddit</span>
                    </a>
                </div>
            </div>

            <div class="support-card">
                <h3>Support Hours</h3>
                <p>Our Discord community is active during these hours:</p>
                <ul>
                    <li>Monday - Friday: 9:00 AM - 6:00 PM (CET)</li>
                    <li>Weekend: 11:00 AM - 4:00 PM (CET)</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>