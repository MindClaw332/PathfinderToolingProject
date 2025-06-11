@foreach($chosenCreatures as $index => $creature)
    <!-- show chosen creatures -->
    <div class="flex flex-row justify-between block" id="creature-{{ $index }}">
        <div class="w-10/12 p-2" onclick="showCreatureEdit({{ $index }})">{{$creature['name']}} {{$creature['level']}}</div>
        <button onclick="removeCreature({{ $index }})" class="w-2/12 m-2 p-1 text-sm rounded-lg bg-red-800" type="button">
            Delete
        </button>
    </div>
    <!-- edit form creatures -->
    <form class="flex flex-row justify-between hidden" id="edit-{{ $index }}">
        <input class="m-1 p-1" id="level-{{ $index }}" type="number" name="level" 
            value={{$creature['level']}} data-default="{{ $creature['level'] }}" placeholder="level">
        <div class="flex flex-row w-1/3 gap-2 justify-end">
            <button onclick="updateCreature({{ $index }})" class="w-1/2 mt-2 mb-2 p-1 text-sm rounded-lg bg-accent" type="button">
                Edit
            </button>
            <button onclick="hideCreatureEdit({{ $index }})" class="w-1/2 mt-2 mr-2 mb-2 p-1 text-sm rounded-lg bg-accent" type="button">
                Cancel
            </button>
        </div>
    </form>
@endforeach