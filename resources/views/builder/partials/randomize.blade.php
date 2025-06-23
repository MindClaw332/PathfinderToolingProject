<span id="randomizeButton" class="w-1/2 m-1 mt-2 mr-2">
    @if(empty($skippedCreatures))
        <button onclick="randomize()" class="bg-secondary w-full p-1 rounded-lg">Randomize</button>
    @else
        <button onclick="randomize()" class="bg-secondary w-full p-1 rounded-lg opacity-50 cursor-not-allowed" disabled>Randomize</button>
    @endif
</span>
