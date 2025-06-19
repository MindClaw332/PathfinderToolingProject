@if ($chosenHazards)
    @foreach($chosenHazards as $index => $hazard)
        <!-- show chosen hazards -->
        <div class="flex flex-row justify-between">
            <div class="p-2">{{$hazard['name']}}</div>
            <button onclick="removeHazard({{ $index }})" class="w-2/12 m-2 p-1 text-sm rounded-lg bg-red-800 cursor-pointer">Delete</button>
        </div>
    @endforeach
    <!-- data -->
    <div id="hazardData-container" style="display: none;"
        @if(isset($chosenHazards)) data-chosen_hazards="{{ json_encode($chosenHazards) }}" @endif
        >data</div>
@else
    <div class="m-1">No hazards chosen yet</div>
@endif