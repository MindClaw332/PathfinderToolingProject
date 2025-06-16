let partySize = document.getElementById('partySize');
let partyLevel = document.getElementById('partyLevel');

let baseUrl = `/content/${contentId}`;
let chosenCreatures;

// Get data + Make functions globally available
document.addEventListener('DOMContentLoaded', function() {   
    // Make functions globally available
    window.setCreature = setCreature;
    window.removeCreature = removeCreature;
    window.updateCreature = updateCreature;
    window.showCreatureEdit = showCreatureEdit;
    window.hideCreatureEdit = hideCreatureEdit;
    window.calculateXP = calculateXP;
    window.showStatsInfo = showStatsInfo;
    window.hideStatsInfo = hideStatsInfo;

    refreshChosenCreatures ();
    calculateXP();
});

// Add the clicked creature to the correct content array
async function setCreature(creatureId) {
    const response = await axios.post(`${baseUrl}/creatures`, {
        creature_id: creatureId
    });
    if (response.data.success) {
        document.getElementById('creature-list').innerHTML = response.data.html;
        refreshChosenCreatures ();
    };
}

// Show the edit form for creatures
function showCreatureEdit (index) {
    let creature = document.getElementById(`creature-${index}`);
    let edit = document.getElementById(`edit-${index}`);

    creature.classList.remove('block');
    creature.classList.add('hidden');
    edit.classList.remove('hidden');
    edit.classList.add('block');
}

// Hide the edit form for creatures
function hideCreatureEdit (index) {
    let creature = document.getElementById(`creature-${index}`);
    let edit = document.getElementById(`edit-${index}`);
    let level = document.getElementById(`level-${index}`);

    creature.classList.remove('hidden');
    creature.classList.add('block');
    edit.classList.remove('block');
    edit.classList.add('hidden');

    level.value = level.dataset.default;
}

// Update the creature level
async function updateCreature(index) {
    const levelInput = document.getElementById(`level-${index}`);
    const level = levelInput.value;

    const response = await axios.put(`${baseUrl}/creatures/${index}`, {
        level: level,
    });

    if (response.data.success) {
        document.getElementById('creature-list').innerHTML = response.data.html;
        refreshChosenCreatures ();
    };
}

// Remove the creature from the correct content array
async function removeCreature(index) {
    const response = await axios.delete(`${baseUrl}/creatures/${index}`);
    if (response.data.success) {
        document.getElementById('creature-list').innerHTML = response.data.html;
        refreshChosenCreatures ();
    };
}

// Calculate encounter XP
async function calculateXP () {
    const response = await axios.post(`${baseUrl}/calculate`, {
        party_size: partySize.value,
        party_level: partyLevel.value,
    });
    if (response.data.success) {
        document.getElementById('encounterBar').innerHTML = response.data.html;
    }
}

// Show Stats on mouseover
function showStatsInfo (index) {
    let selectedCreature = chosenCreatures[index];
    let hover = document.getElementById(`hover-${index}`);
    hover.classList.remove('hidden');
    hover.classList.add('block');

    hover.innerHTML = `
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
        </div>`
}

// Hide stats on mouseout
function hideStatsInfo (index) {
    let hover = document.getElementById(`hover-${index}`);
    hover.classList.remove('block');
    hover.classList.add('hidden');
}

// Refresh chosen creatures array
function refreshChosenCreatures () {
    const dataContainer = document.getElementById('creatureData-container');
    
    // Get data
    if (dataContainer) {
        chosenCreatures = JSON.parse(dataContainer.dataset.chosenCreatures || '[]');
    }
}