@vite([
    'resources/css/app.css', 
    'resources/js/app.js', 
    'resources/js/builder/builder.js', 
    'resources/js/builder/search.js', 
    'resources/js/builder/hazards.js',
    'resources/js/builder/creatures.js',
    'resources/js/builder/randomize.js',
])

<!-- selection bar -->
<div class="p-2">
    <div class="flex flex-wrap flex-row bg-secondary w-full p-2 border border-accent rounded-lg justify-between">
        <!-- show/hide creatures and hazards -->
        @if($creatures || $hazards)
            <button onclick="toggleCreatureHazard ()" class="cursor-pointer">
                creatures/hazards
            </button>
        @endif
        <a href="{{ route('builder.randomize', ['contentId' => $contentId]) }}" class="cursor-pointer">
            randomize partially
        </a>
        <a href="{{ route('builder.encounter', ['contentId' => $contentId]) }}" class="cursor-pointer">
            Encounter
        </a>
        {{-- <a href="{{ route('builder.newcreature', ['contentId' => $contentId]) }}" class="cursor-pointer">
            create creature
        </a> --}}
        {{-- <a href="{{ route('builder.creature', ['contentId' => $contentId]) }}" class="cursor-pointer">
            My creatures
        </a> --}}
        <button onclick="toggleTheme()" class="cursor-pointer">
            Toggle Theme
        </button>
    </div>
</div>