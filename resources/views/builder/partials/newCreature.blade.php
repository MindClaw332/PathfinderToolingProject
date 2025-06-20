<div class="divide-y-1 divide-y divide-tertiary" id="creatureHTML">
    @foreach($newCreatures as $index => $creature)
        <!-- show randomized creatures -->
        <div class="flex flex-row justify-between block" 
        onmouseover="showStatsInfo({{ $index }}, 'newCreatures')" onmouseout="hideStatsInfo({{ $index }}, 'newCreatures')">
            <div class="text-accent p-2">{{$creature['name']}}</div>
        </div>
        <!-- hover -->
        <div class="flex flex-col hidden" id="newCreatures-{{ $index }}"></div>
    @endforeach
    <!-- data -->
    <div id="newCreatureData-container" class="hidden"
        data-new_creatures="{{ isset($newCreatures) ? json_encode($newCreatures) : '[]' }}">
        data
        </div>
</div>