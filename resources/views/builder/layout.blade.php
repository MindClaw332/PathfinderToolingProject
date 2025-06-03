@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/builder.js'])

<div class="bg-primary text-white h-full w-full">
    <!-- selection bar -->
    <div class="p-2">
        <div class="flex flex-row bg-secondary w-full p-2 border border-accent rounded-lg justify-between">
            <!-- show/hide creatures and hazards -->
            <button onclick="toggleCreatureHazard ()">
                creatures/hazards
            </button>
            <a href="randomize">randomize partially</a>
            <a href="encounter">Encounter</a>
            <a href="newcreature">create creature</a>
            <a href="creature">My creatures</a>
            <div>randomize complete</div>
            <div>export</div>
            <div>import</div>
        </div>
    </div>
    <div class="flex flex-row">
        <!-- creature/hazard picker -->
        <div class="flex flex-col w-1/3 p-2 block" id="creatureHazard">
            <!-- creatures -->
            <div class="bg-secondary mb-4 border border-accent rounded-lg block" id="creature">
                <!-- filter -->
                <div class="p-2">
                    <input type="text" class="w-full p-1 bg-tertiary rounded-lg">
                </div>
                <!-- list creatures -->
                <div class="h-84 overflow-y-auto scrollbar-hide divide-y-1 divide-y divide-tertiary">
                    @foreach($creatures as $creature)
                        <div class="p-2">
                            <div class="flex flex-row justify-between">
                                <div class="text-lg">{{$creature->name}}</div>
                                <div class="flex flex-row gap-2">
                                    @foreach($creature->pathfindertraits as $trait)
                                        <div class="bg-tertiary p-1 rounded-lg">{{ $trait->name }}</div>
                                    @endforeach
                                </div>
                                
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- hazards -->
            <div class="bg-secondary mb-1 border border-accent rounded-lg block" id="hazard">
                <!-- filter -->
                <div class="p-2">
                    <input type="text" class="w-full p-1 bg-tertiary rounded-lg">
                </div>
                <!-- list hazard -->
                <div class="h-36 overflow-y-auto scrollbar-hide  divide-y-1 divide-y divide-tertiary">
                    @foreach($hazards as $hazard)             
                        <div class="flex flex-row justify-between p-2">
                            <div>{{$hazard->name}}</div>
                            <div class="bg-tertiary p-1 rounded-lg">{{$hazard->type->name}}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- show/hide buttons -->
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
        </div>
        <!-- hover popup -->
        <div class="bg-secondary w-2/3 m-2 p-2 border border-accent rounded-lg hidden" id="popup">test</div>
        <!-- selected content -->
        <div class="w-2/3" id="content">
            @yield('content')
        </div>
    </div>
</div>