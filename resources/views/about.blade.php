<php <!DOCTYPE html>
    <html lang="en" class="dark">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>About - Kobold Lair</title>

        <!-- Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Alpine.js -->
        <script src="//unpkg.com/alpinejs" defer></script>

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>

    @include('partials_mich.header')
    <div class="container mx-auto p-8">

        <!-- About section with golden border -->
        <div class="bg-secondary border border-accent rounded-lg">
            <div class="p-6">
                <div class="text-white mb-8">
                    <h2 class="text-xl font-bold text-accent mb-4">Our Mission</h2>
                    <p>Kobold Lair was created to streamline the combat experience in Pathfinder 2e, making it
                        easier for Game Masters to run exciting and dynamic encounters while keeping track of all
                        the important details.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-tertiary p-6 rounded-lg">
                        <div class="text-accent text-3xl mb-4">
                            <i class="fas fa-dice-d20"></i>
                        </div>
                        <h3 class="text-accent font-bold mb-2">Easy Combat Management</h3>
                        <p class="text-white">Track initiative, HP, and conditions with an intuitive interface.</p>
                    </div>

                    <div class="bg-tertiary p-6 rounded-lg">
                        <div class="text-accent text-3xl mb-4">
                            <i class="fas fa-ghost"></i>
                        </div>
                        <h3 class="text-accent font-bold mb-2">Monster Database</h3>
                        <p class="text-white">Quick access to a comprehensive collection of creatures.</p>
                    </div>

                    <div class="bg-tertiary p-6 rounded-lg">
                        <div class="text-accent text-3xl mb-4">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="text-accent font-bold mb-2">Player Tracking</h3>
                        <p class="text-white">Manage your player characters' stats and conditions.</p>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-xl font-bold text-accent mb-4">Built With</h2>
                    <div class="flex flex-wrap gap-4">
                        <span class="bg-tertiary text-white px-4 py-2 rounded-lg">Laravel</span>
                        <span class="bg-tertiary text-white px-4 py-2 rounded-lg">Alpine.js</span>
                        <span class="bg-tertiary text-white px-4 py-2 rounded-lg">Tailwind CSS</span>
                        <span class="bg-tertiary text-white px-4 py-2 rounded-lg">MySQL</span>
                    </div>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-accent mb-4">Meet the Team</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-tertiary p-6 rounded-lg text-center">
                            <div class="w-24 h-24 bg-accent rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-user text-4xl text-primary"></i>
                            </div>
                            <h3 class="text-white font-bold">Michael Schrijvers</h3>
                            <p class="text-accent">Main Backend Developer </p>
                            <a href="https://github.com/MindClaw332" target="_blank"
                                class="inline-flex items-center gap-2 bg-secondary hover:bg-accent hover:text-primary transition-colors px-4 py-2 rounded-lg text-white">
                                <i class="fab fa-github"></i>
                                <span>GitHub Profile</span>
                            </a>
                        </div>
                        <div class="bg-tertiary p-6 rounded-lg text-center">
                            <div class="w-24 h-24 bg-accent rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-user text-4xl text-primary"></i>
                            </div>
                            <h3 class="text-white font-bold">Zoë Dreessen</h3>
                            <p class="text-accent">Developer Encounter Creator</p>
                            <a href="https://github.com/carinezoe" target="_blank"
                                class="inline-flex items-center gap-2 bg-secondary hover:bg-accent hover:text-primary transition-colors px-4 py-2 rounded-lg text-white">
                                <i class="fab fa-github"></i>
                                <span>GitHub Profile</span>
                            </a>
                        </div>
                        <div class="bg-tertiary p-6 rounded-lg text-center">
                            <div class="w-24 h-24 bg-accent rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-user text-4xl text-primary"></i>
                            </div>
                            <h3 class="text-white font-bold">Michiel Konings</h3>
                            <p class="text-accent">Developer combat manager</p>
                            <a href="https://github.com/JellyBlass" target="_blank"
                                class="inline-flex items-center gap-2 bg-secondary hover:bg-accent hover:text-primary transition-colors px-4 py-2 rounded-lg text-white">
                                <i class="fab fa-github"></i>
                                <span>GitHub Profile</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partials_mich.footer')

    </html>
