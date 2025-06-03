// Get elements
let creatureHazard = document.getElementById('creatureHazard');
let creature = document.getElementById('creature');
let hazard = document.getElementById('hazard');

// Show/hide creatures and hazards
window.toggleCreatureHazard = function () {
    creatureHazard.classList.toggle('hidden');
    creatureHazard.classList.toggle('block');
};

// Show/hide creatures
window.toggleCreature = function () {
    creature.classList.toggle('hidden');
    creature.classList.toggle('block');
};

// Show/hide hazards
window.toggleHazard = function () {
    hazard.classList.toggle('hidden');
    hazard.classList.toggle('block');
};