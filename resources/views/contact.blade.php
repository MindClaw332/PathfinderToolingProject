<php
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact - Kobald lair</title>
    
    <!-- Vite for Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-primary">
    <div class="container mx-auto p-8">
        <!-- Header section with golden border -->
        <div class="bg-secondary border border-accent rounded-lg mb-8">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-white">Contact Us</h1>
                <p class="text-white">Connect with us through various platforms</p>
            </div>
        </div>

        <!-- Contact info section with golden border -->
        <div class="bg-secondary border border-accent rounded-lg">
            <div class="p-6">
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-accent mb-4">Community</h2>
                    <p class="text-white mb-4">Join our community to discuss features, report bugs, or share your experience:</p>
                    
                        <a href="https://github.com/MindClaw332/PathfinderToolingProject" target="_blank"
                           class="flex items-center gap-2 bg-tertiary text-white px-4 py-2 rounded-lg hover:bg-accent hover:text-primary transition-colors">
                            <i class="fab fa-github text-xl"></i>
                            <span>GitHub</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>