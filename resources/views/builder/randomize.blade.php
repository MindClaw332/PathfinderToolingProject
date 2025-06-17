@extends('builder/layout')

@section('content')
    <meta name="content-id" content="{{ $contentId }}">
    <div class="p-2">
        <div class="flex flex-row justify-between">
        <!-- title -->
            <div class="text-2xl m-1">Randomize</div>
            <!-- current player party -->
            <div class="flex flex-row content-center">
                <div class="flex flex-row">
                    <div>Party size:</div>
                    <input class="m-2" type="number" id="partySize" value="4" onchange="calculateXP()" min="1" max="12">
                </div>
                <div class="flex flex-row">
                    <div>Party level:</div>
                    <input class="m-2" type="number" id="partyLevel" value="2" onchange="calculateXP()" min="1" max="20">
                </div>
            </div>
        </div>
        <!-- add creatures -->
        <div class="flex flex-row justify-between">
            <div class="text-lg m-1">Add creatures</div>
            <!-- choose amount -->
            <div class="flex flex-row">
                <div>Amount:</div>
                <input type="number">
                <div class="p-1" id="creatureAmount"></div>
            </div>
        </div>
        <!-- select traits and/or sizes -->
        <div class="flex flex-col m-2">
            <div>
                <!-- traits -->
                <button onclick="showFilter('hideTraits')" class="p-1">Trait</button>
                <div class="flex flex-wrap flex-row gap-2 hidden" id="hideTraits">
                    @foreach($traits as $trait)
                        <button onclick="selectedFilter(this, 'trait', {{$trait->id}})" 
                        class="bg-tertiary p-1 rounded-lg cursor-pointer" id="randomize-trait-{{$trait->id}}">
                            {{$trait->name}}
                        </button>
                    @endforeach
                </div>
            </div>
            <div>
                <!-- sizes -->
                <div class="flex flex-row justify-between">
                    <button onclick="showFilter('hideSizes')" class="p-1">Size</button>
                    <button onclick="resetSizes()" class="text-red-800 cursor-pointer hidden" id="resetSizes">
                        Reset
                    </button>
                </div>
                <div class="flex flex-wrap flex-row gap-2 hidden" id="hideSizes">
                    @foreach($sizes as $size)
                        <button onclick="selectedFilter(this, 'size', {{$size->id}})" 
                        class="bg-tertiary p-1 rounded-lg cursor-pointer" id="randomize-size-{{$size->id}}">
                            {{$size->name}}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- list chosen creatures -->
        <div class="divide-y-1 divide-y divide-tertiary m-1 max-h-46 overflow-y-auto scrollbar-hide" id="creature-list">
            @include('builder.partials.creatureList', ['chosenCreatures' => $chosenCreatures])
        </div>
        <!-- add hazards -->
        <div class="flex flex-row justify-between">
            <div class="text-lg m-1">Add hazards</div>
            <!-- choose amount -->
            <div class="flex flex-row">
                <div>Amount:</div>
                <input type="number">
                <div class="p-1" id="hazardAmount"></div>
            </div>
        </div>
        <!-- select types -->
        <div class="m-2">
            <!-- Types -->
            <button onclick="showFilter('hideTypes')" class="p-1">Type</button>
            <div class="flex flex-wrap flex-row gap-2 hidden" id="hideTypes">
                @foreach($types as $type)
                    <button onclick="selectedFilter(this, 'type', {{$type->id}})" 
                    class="bg-tertiary p-1 rounded-lg cursor-pointer" id="randomize-type-{{$type->id}}">
                        {{$type->name}}
                    </button>
                @endforeach
            </div>
        </div>
        <!-- list chosen hazards -->
        <div class="divide-y-1 divide-y divide-tertiary m-1 max-h-46 overflow-y-auto scrollbar-hide" id="hazard-list">
            @include('builder.partials.hazardList', ['chosenHazards' => $chosenHazards])
        </div>
        <!-- choose difficulty -->
        <div>
            @include('builder.partials.selectThreat', ['threatLevel' => $threatLevel])
        </div>
        <!-- buttons -->
        <div class="flex justify-between gap-4">
            <button class="bg-secondary w-1/2 p-1 rounded-lg">randomize</button>
            <button class="bg-secondary w-1/2 p-1 rounded-lg">clear</button>
        </div>
        <!-- encounter threat level -->
        <div class="flex justify-center m-2">
            @include('builder.partials.encounterBudget', ['threatLevel' => $threatLevel, 'skippedCreatures' => $skippedCreatures])
        </div>
    </div>
@endsection