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
                        <div class="p-2" id="{{$creature->id}}" onmouseover="showCreatureInfo(id)" onmouseout="hideCreatureInfo(id)">
                            <div class="flex flex-row justify-between">
                                <div>{{$creature->name}}</div>
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
                <div class="h-36 overflow-y-auto scrollbar-hide divide-y-1 divide-y divide-tertiary">
                    @foreach($hazards as $hazard)             
                        <div class="flex flex-row justify-between p-2" id="{{$hazard->id}}" onmouseover="showHazardInfo(id)" onmouseout="hideHazardInfo(id)">
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
        <div class="bg-secondary w-2/3 m-2 border border-accent rounded-lg hidden" id="popup"></div>
        <!-- selected content -->
        <div class="w-2/3 m-2" id="content">
            @yield('content')
        </div>
    </div>
</div>

<script>
// Get elements
const hazards = @json($hazards);
const creatures = @json($creatures);
const popup = document.getElementById('popup');
const content = document.getElementById('content');

// Show the complete hazard info
function showHazardInfo(id) {
    let selectedHazard = hazards.find(hazard => hazard.id == id);
    popup.classList.remove('hidden');
    content.classList.add('hidden');
    
    const traitsHtml = selectedHazard.pathfindertraits
        .map(trait => `<div class="bg-tertiary p-1 rounded-lg">${trait.name}</div>`)
        .join('');

    popup.innerHTML = `
        <div class="divide-y-1 divide-y divide-tertiary">
            <div class="p-2">
                <div class="text-2xl p-1">${selectedHazard.name}</div>
                <div class="flex flex-row gap-2 p-1">${traitsHtml}</div>
            </div>
            <div class="p-3 flex flex-row text-center justify-evenly">
                <div>
                    <div class="font-semibold">Complexity</div>
                    <div>${selectedHazard.complexity}</div>
                </div>
                <div>
                    <div class="font-semibold">Type</div>
                    <div>${selectedHazard.type.name}</div>
                </div>
                <div>
                    <div class="font-semibold">Rarity</div>
                    <div>${selectedHazard.rarity.name}</div>
                </div>
            </div>
            <div class="p-3 pt-4">
                <div class="font-semibold pb-1">Source</div>
                <div class="text-justify">${selectedHazard.source}</div>
            </div>
        </div>
    `;
}

// Hide the complete hazard info
function hideHazardInfo(id) {
    popup.classList.add('hidden');
    content.classList.remove('hidden');
}

// Show the complete creature info
function showCreatureInfo(id) {
    let selectedCreature = creatures.find(creature => creature.id == id);
    popup.classList.remove('hidden');
    content.classList.add('hidden');
    
    const traitsHtml = selectedCreature.pathfindertraits
        .map(trait => `<div class="bg-tertiary p-1 rounded-lg">${trait.name}</div>`)
        .join('');

    popup.innerHTML = `
        <div class="divide-y-1 divide-y divide-tertiary">
            <div class="p-2 flex flex-row justify-between">
                <div>
                    <div class="text-2xl p-1">${selectedCreature.name}</div>
                    <div class="flex flex-row gap-2 p-1">${traitsHtml}</div>
                </div>
                <div class="content-center pr-4">
                    <div class="bg-tertiary w-14 h-14 text-center content-center rounded-full text-xl">${selectedCreature.level}</div>
                </div>
            </div>
            <div class="flex flex-col">
                <div class="flex flex-row gap-4 p-2 pt-4 justify-center">
                    <div class="font-semibold">HP</div>
                    <div class="w-1/3 bg-red-900 text-center rounded-lg">${selectedCreature.hp}</div>
                </div>
                <div class="p-3 flex flex-row text-center justify-evenly">
                    <div class="justify-items-center">
                        <div class="font-semibold">AC</div>
                        <div class="bg-tertiary w-8 h-8 mt-1 rounded-lg content-center">${selectedCreature.ac}</div>
                    </div>
                    <div class="justify-items-center">
                        <div class="font-semibold">Fortitude</div>
                        <div class="bg-tertiary w-8 h-8 mt-1 rounded-lg content-center">${selectedCreature.fortitude}</div>
                    </div>
                    <div class="justify-items-center">
                        <div class="font-semibold">Reflex</div>
                        <div class="bg-tertiary w-8 h-8 mt-1 rounded-lg content-center">${selectedCreature.reflex}</div>
                    </div>
                    <div class="justify-items-center">
                        <div class="font-semibold">Will</div>
                        <div class="bg-tertiary w-8 h-8 mt-1 rounded-lg content-center">${selectedCreature.will}</div>
                    </div>
                    <div class="justify-items-center">
                        <div class="font-semibold">Perception</div>
                        <div class="bg-tertiary w-8 h-8 mt-1 rounded-lg content-center">${selectedCreature.perception}</div>
                    </div>
                </div>
            </div>
            <div class="p-3 flex flex-row text-center justify-evenly">
                <div>
                    <div class="font-semibold">Size</div>
                    <div>${selectedCreature.size.name}</div>
                </div>
                <div>
                    <div class="font-semibold">Senses</div>
                    <div>${selectedCreature.senses}</div>
                </div>
                <div>
                    <div class="font-semibold">Speed</div>
                    <div>${selectedCreature.speed}</div>
                </div>
                <div>
                    <div class="font-semibold">Rarity</div>
                    <div>${selectedCreature.rarity.name}</div>
                </div>
            </div>
            <div class="p-3 pt-4">
                <div class="font-semibold pb-1">Source</div>
                <div class="text-justify">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</div>
            </div>
        </div>
    `;
}

// Hide the complete creature info
function hideCreatureInfo(id) {
    popup.classList.add('hidden');
    content.classList.remove('hidden');
}
</script>