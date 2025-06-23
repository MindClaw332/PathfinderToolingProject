@extends('builder/layout')

@section('content')
    <meta name="content-id" content="{{ $contentId }}">
    <div class="p-2">
        <div class="flex flex-row justify-between">
        <!-- title -->
            <div class="flex flex-row gap-2">
                <div class="text-2xl text-sky-50 m-1">Encounter</div>
                <button class="text-xl" onclick="completeRandomize()">
                    🎲
                </button>
            </div>
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
        <!-- current creatures -->
        <div class="text-lg m-1">Creatures</div>
        <div class="divide-y-1 divide-y divide-tertiary m-1 max-h-52 overflow-y-auto scrollbar-hide" id="creature-list">
            <!-- list chosen creatures -->
            @include('builder.partials.creatureList', ['chosenCreatures' => $chosenCreatures])
        </div>
        <!-- current hazards -->
        <div class="text-lg m-1">Hazards</div>
        <div class="divide-y-1 divide-y divide-tertiary m-1 max-h-52 overflow-y-auto scrollbar-hide" id="hazard-list">
            <!-- list chosen hazards -->
            @include('builder.partials.hazardList', ['chosenHazards' => $chosenHazards])
        </div>
        <!-- current threat level -->
        <div class="text-lg m-1">Threat</div>
        <!-- Encounter threat level -->
        <div class="flex justify-center m-2">
            @include('builder.partials.encounterBudget', [
                'threatLevel' => $threatLevel,
                'skippedCreatures' => $skippedCreatures,
            ])
        </div>
        {{-- <div>
            @include('builder.partials.export')
        </div> --}}
    </div>
@endsection
