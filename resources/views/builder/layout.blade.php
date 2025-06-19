@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/builder/builder.js', 'resources/js/builder/search.js', 'resources/js/builder/hazards.js', 'resources/js/builder/creatures.js'])

<div class="bg-primary text-white h-full w-full">
    <!-- selection bar -->
    <div class="p-2">
        <div class="flex flex-row bg-secondary w-full p-2 border border-accent rounded-lg justify-between">
            <!-- show/hide creatures and hazards -->
            @if ($creatures || $hazards)
                <button onclick="toggleCreatureHazard ()">
                    creatures/hazards
                </button>
            @endif
            <a href="{{ route('builder.randomize', ['contentId' => $contentId]) }}">randomize partially</a>
            <a href="{{ route('builder.encounter', ['contentId' => $contentId]) }}">Encounter</a>
            <a href="{{ route('builder.newcreature', ['contentId' => $contentId]) }}">create creature</a>
            <a href="{{ route('builder.creature', ['contentId' => $contentId]) }}">My creatures</a>
            <button onclick="toggleTheme()">Toggle Theme</button>
            <div>randomize complete</div>
            <div>export</div>
            <div>import</div>
        </div>
    </div>
    <div class="flex flex-row">
        <!-- creature/hazard picker -->
        <div class="{{ !empty($creatures) || !empty($hazards) ? 'flex flex-col w-1/3 p-2 block' : '' }}"
            id="creatureHazard">
            <!-- creatures -->
            @if ($creatures)
                <div class="bg-secondary mb-4 border border-accent rounded-lg block" id="creature">
                    <!-- filter -->
                    <div class="flex flex-row p-2 gap-2">
                        <!-- search -->
                        <input type="text" class="w-full p-1 bg-tertiary rounded-lg" id="inputCreature">
                        <button onclick="toggleFilterCreature()">Filter</button>
                    </div>
                    <!-- select -->
                    <div class="p-2 hidden" id="filterCreature">
                        <div>
                            <!-- traits -->
                            <div class="flex flex-row justify-between">
                                <div>Trait</div>
                                <button onclick="resetCreatureFilters()" class="text-red-700">Reset</button>
                            </div>
                            <div class="flex flex-row gap-2">
                                @foreach ($traits as $trait)
                                    <button onclick="toggleSelectCreatureTrait({{ $trait->id }})"
                                        class="bg-tertiary p-1 rounded-lg" id="trait-{{ $trait->id }}">
                                        {{ $trait->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <!-- size -->
                            <div>Size</div>
                            <div class="flex flex-row gap-2">
                                @foreach ($sizes as $size)
                                    <button onclick="toggleSelectedCreatureSize({{ $size->id }})"
                                        class="bg-tertiary p-1 rounded-lg" id="size-{{ $size->id }}">
                                        {{ $size->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <!-- rarity -->
                            <div>Rarity</div>
                            <div class="flex flex-row gap-2">
                                @foreach ($rarities as $rarity)
                                    <button onclick="toggleSelectedCreatureRarity({{ $rarity->id }})"
                                        class="bg-tertiary p-1 rounded-lg" id="rarityC-{{ $rarity->id }}">
                                        {{ $rarity->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!-- list of creatures -->
                    <div class="{{ !empty($hazards) ? 'h-84' : 'h-140' }} overflow-y-auto scrollbar-hide divide-y-1 divide-y divide-tertiary"
                        id="creatureList"></div>
                </div>
            @endif
            <!-- hazards -->
            @if ($hazards)
                <div class="bg-secondary mb-1 border border-accent rounded-lg block" id="hazard">
                    <!-- filter -->
                    <div class="flex flex-row p-2 gap-2">
                        <!-- search -->
                        <input type="text" class="w-full p-1 bg-tertiary rounded-lg" id="inputHazard">
                        <button onclick="toggleFilterHazard()">Filter</button>
                    </div>
                    <!-- select -->
                    <div class="p-2 hidden" id="filterHazard">
                        <div>
                            <!-- type -->
                            <div class="flex flex-row justify-between">
                                <div>Type</div>
                                <button onclick="resetHazardFilters()" class="text-red-700">Reset</button>
                            </div>
                            <div class="flex flex-row gap-2">
                                @foreach ($types as $type)
                                    <button onclick="toggleSelectedHazardType({{ $type->id }})"
                                        class="bg-tertiary p-1 rounded-lg" id="type-{{ $type->id }}">
                                        {{ $type->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <!-- rarity -->
                            <div>Rarity</div>
                            <div class="flex flex-row gap-2">
                                @foreach ($rarities as $rarity)
                                    <button onclick="toggleSelectedHazardRarity({{ $rarity->id }})"
                                        class="bg-tertiary p-1 rounded-lg" id="rarityH-{{ $rarity->id }}">
                                        {{ $rarity->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!-- list of hazard -->
                    <div class="h-36 overflow-y-auto scrollbar-hide divide-y-1 divide-y divide-tertiary"
                        id="hazardList"></div>
                </div>
            @endif
            <!-- show/hide buttons -->
            @if ($creatures && $hazards)
                <div class="flex justify-end gap-2">
                    <!-- show/hide creatures -->
                    <button onclick="toggleCreature ()">
                        creatures
                    </button>
                    <!-- show/hide hazards -->
                    <button onclick="toggleHazard ()">
                        hazards
                    </button>
                </div>
            @endif
        </div>
        <!-- hover popup -->
        <div class="bg-secondary w-2/3 m-2 border border-accent rounded-lg hidden" id="popup"></div>
        <!-- selected content -->
        <div class="{{ !empty($creatures) || !empty($hazards) ? 'w-2/3 m-2' : 'w-full m-2' }}" id="content">
            @yield('content')
        </div>
    </div>
</div>
<!-- data for popup creatures/hazards -->
<div id="data-container" style="display: none;"
    @if (isset($hazards)) data-hazards="{{ json_encode($hazards) }}" @endif
    @if (isset($creatures)) data-creatures="{{ json_encode($creatures) }}" @endif
    @if (isset($traits)) data-traits="{{ json_encode($traits) }}" @endif
    @if (isset($sizes)) data-sizes="{{ json_encode($sizes) }}" @endif
    @if (isset($rarities)) data-rarities="{{ json_encode($rarities) }}" @endif
    @if (isset($types)) data-types="{{ json_encode($types) }}" @endif
    data-content-id="{{ $contentId ?? '' }}">data</div>

<script>
    let contentId = "{{ $contentId ?? '' }}";
</script>
