<div class="divide-y-1 divide-y divide-tertiary" id="hazardHTML">
    @foreach($newHazards as $index => $hazard)
        <!-- show randomized creatures -->
        <div class="flex flex-row justify-between block">
            <div class="text-accent p-2">{{$hazard['name']}}</div>
        </div>
    @endforeach
    <!-- data -->
    <div id="newHazardData-container" class="hidden"
        data-new_creatures="{{ isset($newHazards) ? json_encode($newHazards) : '[]' }}">
        data
        </div>
</div>