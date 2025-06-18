<div id="selectThreat">
    @php
        $threatOptions = ['Trivial', 'Low', 'Moderate', 'Severe', 'Extreme'];
        // Only allow options at or above current level
        $currentIndex = array_search($threatLevel, $threatOptions);
        $availableOptions = $currentIndex !== false
            ? array_slice($threatOptions, $currentIndex)
            : $threatOptions;
    @endphp

    <div class="text-lg text-sky-600 m-1">Threat</div>
    <select class="w-full p-3 cursor-pointer">
        @foreach ($availableOptions as $option)
            <option value="{{ $option }}" {{ $option === $threatLevel ? 'selected' : '' }}>
                {{ $option }}-threat
            </option>
        @endforeach
    </select>
</div>