@extends('builder/layout')

@section('content')
    <meta name="content-id" content="{{ $contentId }}">
    <div class="p-2">
        <div class="flex flex-row justify-between">
        <!-- title -->
            <div class="text-2xl m-1">Randomize</div>
            <!-- current player party -->
            <div class="flex flex-row gap-4">
                <div>Party size:</div>
                <div>Party level:</div>
            </div>
        </div>
        <!-- add creatures -->
        <div class="flex flex-row justify-between">
            <div class="text-lg m-1">Add creatures</div>
            <!-- choose amount -->
            <div>Amount: {{ count($chosenCreatures) }}</div>
        </div>
        <div class="divide-y-1 divide-y divide-tertiary m-1 max-h-46 overflow-y-auto scrollbar-hide">
            @foreach($chosenCreatures as $index => $creature)
                <div class="flex flex-row justify-between">
                    <div class="p-2">{{$creature['name']}}</div>
                    <button onclick="removeCreature({{ $index }})" class="m-2 p-1 text-sm rounded-lg bg-red-800">Delete</button>
                </div>
            @endforeach
        </div>
        <!-- add hazards -->
        <div class="flex flex-row justify-between">
            <div class="text-lg m-1">Add hazards</div>
            <!-- choose amount -->
            <div>Amount:</div>
        </div>
        <div class="divide-y-1 divide-y divide-tertiary m-1">
            <div class="p-2">hazard</div>
            <div class="p-2">hazard</div>
            <div class="p-2">hazard</div>
        </div>
        <!-- choose difficulty -->
        <div class="text-lg m-1">Choose threat</div>
        <select class="w-full p-3">
            <option>Trivial-threat</option>
            <option>Low-threat</option>
            <option>Moderate-threat</option>
            <option>Severe-threat</option>
            <option>Extreme-threat</option>
        </select>
        <!-- buttons -->
        <div class="flex justify-between gap-4">
            <button class="bg-secondary w-1/2 p-1 rounded-lg">randomize</button>
            <button class="bg-secondary w-1/2 p-1 rounded-lg">clear</button>
        </div>
    </div>
@endsection