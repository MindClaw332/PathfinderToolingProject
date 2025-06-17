<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About - Pathfinder 2e Combat Manager</title>
    
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
            line-height: 1.6;
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

        .about-section {
            background: var(--color-card);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .dark .about-section {
            background: var(--color-dark-card);
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .feature-card {
            background: rgba(0,0,0,0.03);
            padding: 1.5rem;
            border-radius: 8px;
            transition: transform 0.2s ease;
        }

        .dark .feature-card {
            background: rgba(255,255,255,0.05);
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            color: var(--color-accent);
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .tech-stack {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
        }

        .tech-badge {
            background: var(--color-accent);
            color: var(--color-primary);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .team-section {
            margin-top: 2rem;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .team-member {
            text-align: center;
            padding: 1.5rem;
            background: rgba(0,0,0,0.03);
            border-radius: 8px;
        }

        .dark .team-member {
            background: rgba(255,255,255,0.05);
        }

        .member-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 1rem;
            background: var(--color-accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: var(--color-primary);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>About Combat Manager</h1>
            <p>A powerful tool for managing your Pathfinder 2e encounters</p>
        </div>

        <div class="about-section">
            <h2>Our Mission</h2>
            <p>Combat Manager was created to streamline the combat experience in Pathfinder 2e, making it easier for Game Masters to run exciting and dynamic encounters while keeping track of all the important details.</p>
            
            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-dice-d20"></i>
                    </div>
                    <h3>Easy Combat Management</h3>
                    <p>Track initiative, HP, and conditions with an intuitive interface.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-ghost"></i>
                    </div>
                    <h3>Monster Database</h3>
                    <p>Quick access to a comprehensive collection of creatures.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Player Tracking</h3>
                    <p>Manage your player characters' stats and conditions.</p>
                </div>
            </div>

            <div class="tech-section">
                <h2>Built With</h2>
                <div class="tech-stack">
                    <span class="tech-badge">Laravel</span>
                    <span class="tech-badge">Alpine.js</span>
                    <span class="tech-badge">Tailwind CSS</span>
                    <span class="tech-badge">MySQL</span>
                </div>
            </div>

            <div class="team-section">
                <h2>Meet the Team</h2>
                <div class="team-grid">
                    <div class="team-member">
                        <div class="member-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3>Developer Name</h3>
                        <p>Lead Developer</p>
                    </div>
                    <div class="team-member">
                        <div class="member-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3>Designer Name</h3>
                        <p>UI/UX Designer</p>
                    </div>
                    <!-- Add more team members as needed -->
                </div>
            </div>
        </div>
    </div>
</body>
</html>