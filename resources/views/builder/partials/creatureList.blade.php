@if($chosenCreatures)
    @foreach($chosenCreatures as $index => $creature)
        <!-- show chosen creatures -->
        <div class="flex flex-row justify-between block cursor-pointer" id="creature-{{ $index }}">
            <div class="w-10/12" onclick="showCreatureEdit({{ $index }})" 
            onmouseover="showStatsInfo({{ $index }}, 'chosenCreatures')" onmouseout="hideStatsInfo({{ $index }}, 'chosenCreatures')">
                <div class="flex flex-row w-2/5 p-2 justify-between">
                    <div class="content-center">
                        {{$creature['name']}} 
                    </div>
                    <div class="w-8 h-8 bg-secondary rounded-full text-center content-center">
                        {{$creature['level']}}
                    </div>
                </div>
            </div>
            <button onclick="(async () => { await removeCreature({{ $index }}); await calculateXP(); })()" 
            class="w-2/12 m-2 p-1 text-sm rounded-lg bg-red-800 cursor-pointer" type="button">
                Delete
            </button>
        </div>
        <!-- edit form creatures -->
        <form class="flex flex-row justify-between hidden" id="edit-{{ $index }}">
            <!-- level -->
            <div class="w-2/3">
                <input class="w-1/6 m-1 p-1 text-center" id="level-{{ $index }}" type="number" name="level" 
                    value={{$creature['level']}} data-default="{{ $creature['level'] }}" placeholder="level" min="-1" readonly>
                <!-- +1 -->
                <button onclick="addLevel({{ $index }})" type="button" class="w-1/12 p-1 bg-tertiary rounded-lg cursor-pointer">
                    +1
                </button>
                <!-- -1 -->
                <button onclick="subtractLevel({{ $index }})" type="button" class="w-1/12 p-1 bg-tertiary rounded-lg cursor-pointer">
                    -1
                </button>
            </div>
            <!-- buttons -->
            <div class="flex flex-row w-1/3 gap-2 justify-end">
                <!-- edit -->
                <button onclick="(async () => { await updateCreature({{ $index }}); await calculateXP(); })()" 
                class="w-1/2 mt-2 mb-2 p-1 text-sm rounded-lg bg-accent cursor-pointer" type="button">
                    Edit
                </button>
                <!-- cancel -->
                <button onclick="hideCreatureEdit({{ $index }})" 
                class="w-1/2 mt-2 mr-2 mb-2 p-1 text-sm rounded-lg bg-accent cursor-pointer" type="button">
                    Cancel
                </button>
            </div>
        </form>
        <!-- show stats -->
            <!-- hover -->
            <div class="flex flex-col hidden" id="chosenCreatures-{{ $index }}"></div>
            <!-- edit -->
            <div class="flex flex-col hidden" id="stat-{{ $index }}"></div>
    <!-- data -->
    <div id="creatureData-container" style="display: none;"
        @if(isset($chosenCreatures)) data-chosen-creatures="{{ json_encode($chosenCreatures) }}" @endif
        @if(isset($chosenCreatures)) data-chosen_creatures="{{ json_encode($chosenCreatures) }}" @endif
        >data</div>
    @endforeach
@else
    <div class="m-1">No creatures selected yet</div>
@endif
@include('builder.partials.newCreature', ['newCreatures' => $newCreatures])