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
        <div class="bg-secondary w-2/3 m-2 p-2 border border-accent rounded-lg hidden" id="popup"></div>
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
        .map(trait => `<div>${trait.name}</div>`)
        .join('');

    popup.innerHTML = `
        <div>${selectedHazard.name}</div>
        <div>${selectedHazard.complexity}</div>
        <div>${selectedHazard.type.name}</div>
        <div>${selectedHazard.rarity.name}</div>
        <div class="flex flex-row">${traitsHtml}</div>
        <div>${selectedHazard.source}</div>
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
        .map(trait => `<div>${trait.name}</div>`)
        .join('');

    popup.innerHTML = `
        <div>${selectedCreature.name}</div>
        <div>${selectedCreature.size.name}</div>
        <div>${selectedCreature.level}</div>
        <div>${selectedCreature.hp}</div>
        <div>${selectedCreature.ac}</div>
        <div>${selectedCreature.fortitude}</div>
        <div>${selectedCreature.reflex}</div>
        <div>${selectedCreature.will}</div>
        <div>${selectedCreature.perception}</div>
        <div>${selectedCreature.senses}</div>
        <div>${selectedCreature.speed}</div>
        <div>${selectedCreature.rarity.name}</div>
        <div class="flex flex-row">${traitsHtml}</div>
    `;
}

// Hide the complete creature info
function hideCreatureInfo(id) {
    popup.classList.add('hidden');
    content.classList.remove('hidden');
}
</script>