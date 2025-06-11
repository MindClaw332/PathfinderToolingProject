@foreach($chosenCreatures as $index => $creature)
    <div class="flex flex-row justify-between">
        <div class="p-2">{{$creature['name']}}</div>
        <button onclick="removeCreature({{ $index }})" class="m-2 p-1 text-sm rounded-lg bg-red-800">Delete</button>
    </div>
@endforeach