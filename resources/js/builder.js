// Get elements
let creatureHazard = document.getElementById('creatureHazard');
let creature = document.getElementById('creature');
let hazard = document.getElementById('hazard');
let content = document.getElementById('content');

// Show/hide creatures and hazards
window.toggleCreatureHazard = function () {
    creatureHazard.classList.toggle('hidden');
    creatureHazard.classList.toggle('block');
    content.classList.toggle('w-2/3');
    content.classList.toggle('w-full');
};

// Show/hide creatures
window.toggleCreature = function () {
    creature.classList.toggle('hidden');
    creature.classList.toggle('block');
    if (hazard.classList.contains('hidden')) {
        hazard.classList.remove('hidden');
        hazard.classList.add('block');
    }
};

// Show/hide hazards
window.toggleHazard = function () {
    hazard.classList.toggle('hidden');
    hazard.classList.toggle('block');
    if (creature.classList.contains('hidden')) {
        creature.classList.remove('hidden');
        creature.classList.add('block');
    }
};