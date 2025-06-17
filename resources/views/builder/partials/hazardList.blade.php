@foreach($chosenHazards as $index => $hazard)
    <!-- show chosen hazards -->
    <div class="flex flex-row justify-between">
        <div class="p-2">{{$hazard['name']}}</div>
        <button onclick="removeHazard({{ $index }})" class="w-2/12 m-2 p-1 text-sm rounded-lg bg-red-800">Delete</button>
    </div>
@endforeach