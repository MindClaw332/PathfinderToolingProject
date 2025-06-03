@extends('builder/layout')

@section('content')
    <div class="p-2">
        <!-- title -->
        <div class="text-2xl m-1">Randomize</div>
        <!-- add creatures -->
        <div class="text-lg m-1">Add creatures</div>
        <div class="divide-y-1 divide-y divide-tertiary m-1">
            <div class="p-2">creature</div>
            <div class="p-2">creature</div>
            <div class="p-2">creature</div>
        </div>
        <!-- add hazards -->
        <div class="text-lg m-1">Add hazards</div>
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