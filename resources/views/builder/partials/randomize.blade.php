<div id="randomizeButton" class="w-1/2">
    @if(empty($skippedCreatures))
        <button onclick="randomize()" class="bg-secondary w-full m-2 p-1 rounded-lg">randomize</button>
    @else
        <button onclick="randomize()" class="bg-secondary w-full m-2 p-1 rounded-lg opacity-50 cursor-not-allowed" disabled>randomize</button>
    @endif
</div>
