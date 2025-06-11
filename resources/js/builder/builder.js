// Get elements
let creatureHazard = document.getElementById('creatureHazard');
let creature = document.getElementById('creature');
let hazard = document.getElementById('hazard');
let content = document.getElementById('content');
let popup = document.getElementById('popup');

let hazards, creatures;

// Get data + Make functions globally available
document.addEventListener('DOMContentLoaded', function() {
    const dataContainer = document.getElementById('data-container');
    
    // Get data
    if (dataContainer) {
        hazards = JSON.parse(dataContainer.dataset.hazards || '[]');
        creatures = JSON.parse(dataContainer.dataset.creatures || '[]');
    }
    
    // Make functions globally available
    window.toggleCreatureHazard = toggleCreatureHazard;
    window.toggleCreature = toggleCreature;
    window.toggleHazard = toggleHazard;
    window.showHazardInfo = showHazardInfo;
    window.hideHazardInfo = hideHazardInfo;
    window.showCreatureInfo = showCreatureInfo;
    window.hideCreatureInfo = hideCreatureInfo;
});


// Show/hide creatures and hazards
function toggleCreatureHazard () {
    creatureHazard.classList.toggle('hidden');
    creatureHazard.classList.toggle('block');
    content.classList.toggle('w-2/3');
    content.classList.toggle('w-full');
}

// Show/hide creatures
function toggleCreature () {
    creature.classList.toggle('hidden');
    creature.classList.toggle('block');
    if (hazard.classList.contains('hidden')) {
        hazard.classList.remove('hidden');
        hazard.classList.add('block');
    }
};

// Show/hide hazards
function toggleHazard () {
    hazard.classList.toggle('hidden');
    hazard.classList.toggle('block');
    if (creature.classList.contains('hidden')) {
        creature.classList.remove('hidden');
        creature.classList.add('block');
    }
};

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
function hideHazardInfo() {
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
function hideCreatureInfo() {
    popup.classList.add('hidden');
    content.classList.remove('hidden');
}