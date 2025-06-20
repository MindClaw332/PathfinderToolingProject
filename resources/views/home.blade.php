@include('partials_mich.header')


<!-- Main Content -->
{{-- title card  --}}
<main class="text-highlight px-6 py-8 max-w-6xl mx-auto">
    <section class="mb-12 text-center">
        <h1 class="text-4xl font-bold mb-4">Welcome to {{ config('app.name', 'Pathfinder Toolkit') }}</h1>
        <p class="text-lg text-accent-light">Your one-stop hub for mastering Pathfinder adventures with powerful tools
            designed for Game Masters and Players alike.</p>
    </section>
    {{-- explanation cards --}}
    <section class="grid gap-8 grid-cols-1 md:grid-cols-2 xl:grid-cols-4">
        <div class="bg-secondary rounded-xl p-6 border border-accent shadow-lg hover:shadow-xl transition-shadow">
            <h2 class="text-xl font-semibold mb-2">Encounter Builder</h2>
            <p class="text-sm text-accent-light mb-4">Craft dynamic encounters tailored to your party's level,
                environment, and play style.</p>
            <a href="{{ route('builder.encounter') }}" class="text-accent hover:underline">Start Building →</a>
        </div>

        <div class="bg-secondary rounded-xl p-6 border border-accent shadow-lg hover:shadow-xl transition-shadow">
            <h2 class="text-xl font-semibold mb-2">Combat Manager</h2>
            <p class="text-sm text-accent-light mb-4">Manage initiative, track HP, and streamline combat flow with ease.
            </p>
            <a href="{{ route('combat') }}" class="text-accent hover:underline">Manage Combat →</a>
        </div>

        <div class="bg-secondary rounded-xl p-6 border border-accent shadow-lg hover:shadow-xl transition-shadow">
            <h2 class="text-xl font-semibold mb-2">About This Project</h2>
            <p class="text-sm text-accent-light mb-4">Built by fans, for fans. Learn about the mission, team, and
                roadmap behind this toolkit.</p>
            <a href="{{ route('about') }}" class="text-accent hover:underline">About Us →</a>
        </div>
        <div class="bg-secondary rounded-xl p-6 border border-accent shadow-lg hover:shadow-xl transition-shadow">
            <h2 class="text-xl font-semibold mb-2">Donate</h2>
            <p class="text-sm text-accent-light mb-4">See how you can help us cover server costs and keep us up and
                running!</p>
            <a href="{{ route('donate') }}" class="text-accent hover:underline">Donate →</a>
        </div>
    </section>
    {{-- small marketing text --}}
    <section class="mt-16 text-center ">
        <div class="border border-accent rounded-xl bg-secondary p-6 inline-block mx-auto">
            <h3 class="text-2xl font-semibold mb-4 underline">Why Use This Toolkit?</h3>
            <ul class="text-accent-light space-y-2 text-sm">
                <li>Save time with pre-built encounter logic</li>
                <li>Visual and clean combat tracking</li>
                <li>No installation — everything runs in your browser</li>
                <li>Open source & community driven</li>
            </ul>
        </div>

    </section>
</main>
@include('partials_mich.footer')
