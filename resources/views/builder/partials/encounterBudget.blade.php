@php
    // Define colors, widths and displays for each threat level
    $threatLevels = [
        'None' => ['color' => '', 'width' => '0', 'display' => 'hidden'],
        'Trivial' => ['color' => 'bg-green-400', 'width' => '20%', 'display' => 'block'],
        'Low' => ['color' => 'bg-yellow-400', 'width' => '40%', 'display' => 'block'],
        'Moderate' => ['color' => 'bg-orange-400', 'width' => '60%', 'display' => 'block'],
        'Severe' => ['color' => 'bg-red-500', 'width' => '80%', 'display' => 'block'],
        'Extreme' => ['color' => 'bg-red-800', 'width' => '100%', 'display' => 'block'],
        'Over Extreme' => ['color' => 'bg-red-950', 'width' => '100%', 'display' => 'block']
    ];

    $config = $threatLevels[$threatLevel] ?? null;
@endphp
<!-- threat level of encounter -->
<div class="w-full h-8 bg-secondary rounded-lg" id="encounterBar">
    @if($config)
        <div class="p-1 rounded-lg text-center {{ $config['color'] }} {{ $config['display']}}" style="width: {{ $config['width'] }};">
            {{ $threatLevel }} threat
        </div>
    @else
        <div class="h-6 rounded-lg" style="width: 0;"></div>
    @endif
</div>