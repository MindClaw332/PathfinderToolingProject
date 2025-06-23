<div id="selectThreat">
    @php
        $threatOptions = ['Trivial', 'Low', 'Moderate', 'Severe', 'Extreme'];
        // Only allow options at or above current level
        $currentIndex = array_search($threatLevel, $threatOptions);
        $availableOptions = $currentIndex !== false
            ? array_slice($threatOptions, $currentIndex)
            : $threatOptions;
    @endphp

    <div class="mt-4 ml-2">Desired threat level:</div>
    <select class="w-full m-2 mb-4 cursor-pointer bg-primary text-white" id="threatLevel">
        @foreach ($availableOptions as $option)
            <option value="{{ $option }}" {{ $option === $threatLevel ? 'selected' : '' }}>
                {{ $option }}-threat
            </option>
        @endforeach
    </select>
</div>