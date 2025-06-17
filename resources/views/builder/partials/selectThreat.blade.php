<div id="selectThreat">
    @php
        $threatOptions = ['Trivial', 'Low', 'Moderate', 'Severe', 'Extreme'];
        // Only allow options at or above current level
        $currentIndex = array_search($threatLevel, $threatOptions);
        $availableOptions = $currentIndex !== false
            ? array_slice($threatOptions, $currentIndex)
            : $threatOptions;
    @endphp

    <div class="text-lg m-1">Choose threat</div>
    <select class="w-full p-3">
        @foreach ($availableOptions as $option)
            <option value="{{ $option }}" {{ $option === $threatLevel ? 'selected' : '' }}>
                {{ $option }}-threat
            </option>
        @endforeach
    </select>

    @if($threatLevel)
        <div>{{ $threatLevel }}</div>
    @else
        <div>No threat</div>
    @endif
</div>