@extends('builder/layout')

@section('content')
    <meta name="content-id" content="{{ $contentId }}">
    <div class="p-2">
        <div class="flex flex-row justify-between">
        <!-- title -->
            <div class="text-2xl m-1">Encounter</div>
            <!-- current player party -->
            <div class="flex flex-row gap-4">
                <div>Party size:</div>
                <div>Party level:</div>
            </div>
        </div>
        <!-- current threat level -->
        <div class="text-lg m-1">threat</div>
        <!-- current creatures -->
        <div class="text-lg m-1">creatures</div>
        <div class="divide-y-1 divide-y divide-tertiary m-1 max-h-52 overflow-y-auto scrollbar-hide" id="creature-list">
            @include('builder.partials.creatureList', ['chosenCreatures' => $chosenCreatures])
        </div>
        <!-- current hazards -->
        <div class="text-lg m-1">hazards</div>
        <div class="divide-y-1 divide-y divide-tertiary m-1">
            <div class="p-2">hazard</div>
            <div class="p-2">hazard</div>
            <div class="p-2">hazard</div>
        </div>
    </div>
@endsection