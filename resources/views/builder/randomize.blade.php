@extends('builder/layout')

@section('content')
    <meta name="content-id" content="{{ $contentId }}">
    <div class="p-2">
        <div class="flex flex-row justify-between">
        <!-- title -->
            <div class="text-2xl text-sky-50 m-1">Randomize</div>
            <!-- current player party -->
            <div class="flex flex-row content-center">
                <div class="flex flex-row">
                    <div class="content-center">Party size:</div>
                    <input class="m-2" type="number" id="partySize" value="4" onchange="calculateXP()" min="1" max="12">
                </div>
                <div class="flex flex-row">
                    <div class="content-center">Party level:</div>
                    <input class="m-2" type="number" id="partyLevel" value="2" onchange="calculateXP()" min="1" max="20">
                </div>
            </div>
        </div>
        <!-- error message -->
        <div id="error-container" class="hidden"></div>
        <!-- add creatures -->
        <div class="flex flex-row justify-between">
            <div class="text-lg text-sky-600 m-1">Creatures</div>
            <!-- choose amount -->
            <div class="flex flex-row gap-2">
                <div class="content-center">Amount:</div>
                <input type="number" value="1" min="1" max="10" id="creatureInput">
                <div class="content-center" id="creatureAmount"></div>
            </div>
        </div>
        <!-- select traits and/or sizes -->
        <div class="flex flex-col m-2">
            <div>
                <!-- traits -->
                <button onclick="showFilter('hideTraits')" class="text-sky-200">Trait</button>
                <div class="flex flex-wrap flex-row mt-1 mb-1 gap-2 hidden" id="hideTraits">
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
                    <button onclick="showFilter('hideSizes')" class="text-sky-200">Size</button>
                    <button onclick="resetSizes()" class="text-red-800 cursor-pointer hidden" id="resetSizes">
                        Reset
                    </button>
                </div>
                <div class="flex flex-wrap flex-row mt-1 mb-1 gap-2 hidden" id="hideSizes">
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
            @include('builder.partials.creatureList', ['chosenCreatures' => $chosenCreatures, 'newCreatures' => $newCreatures])
        </div>
        <!-- add hazards -->
        <div class="flex flex-row justify-between">
            <div class="text-lg text-sky-600 m-1">Hazards</div>
            <!-- choose amount -->
            <div class="flex flex-row gap-2">
                <div class="content-center">Amount:</div>
                <input type="number" value="0" min="0" max="2" id="hazardInput">
                <div class="content-center" id="hazardAmount">
                    @if($hazardCount > 0) + {{$hazardCount}} @endif
                </div>
            </div>
        </div>
        <!-- select types -->
        <div class="m-2">
            <!-- Types -->
            <button onclick="showFilter('hideTypes')" class="text-sky-200">Type</button>
            <div class="flex flex-wrap flex-row mt-1 mb-1 gap-2 hidden " id="hideTypes">
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
        <div class="text-lg text-sky-600 m-1">Threat</div>
        <!-- encounter threat level -->
        <div class="m-2">
            @include('builder.partials.encounterBudget', ['threatLevel' => $threatLevel, 'skippedCreatures' => $skippedCreatures])
        </div>
        <div>
            @include('builder.partials.selectThreat', ['threatLevel' => $threatLevel])
        </div>
        <!-- buttons -->
        <div class="flex flex-row">
            @include('builder.partials.randomize', ['skippedCreatures' => $skippedCreatures])
            <button onclick="resetRandomize()" class="bg-secondary w-1/2 m-2 p-1 rounded-lg">clear</button>
        </div>
        <div>
            @include('builder.partials.export')
        </div>
    </div>
@endsection