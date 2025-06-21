<div class="divide-y-1 divide-y divide-tertiary" id="creatureHTML">
    @foreach($newCreatures as $index => $creature)
        <!-- show randomized creatures -->
        <div class="text-accent block" 
        onmouseover="showStatsInfo({{ $index }}, 'newCreatures')" onmouseout="hideStatsInfo({{ $index }}, 'newCreatures')">
           <div class="flex flex-row w-1/3 p-2 justify-between">
                <div class="content-center">
                    {{$creature['name']}} 
                </div>
                <div class="w-8 h-8 bg-secondary rounded-full text-center content-center">
                    {{$creature['level']}}
                </div>
            </div>
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