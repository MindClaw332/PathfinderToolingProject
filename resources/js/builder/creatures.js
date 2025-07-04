let partySize = document.getElementById('partySize');
let partyLevel = document.getElementById('partyLevel');

const buttonPressCount = {};
const editCreature = {};

let baseUrl = `/content/${contentId}`;
let chosenCreatures, newCreatures;

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
    window.addLevel = addLevel;
    window.subtractLevel = subtractLevel;

    refreshChosenCreatures();
    calculateXP();
});

// Add the clicked creature to the correct content array
async function setCreature(creatureId) {
    const response = await axios.post(`${baseUrl}/creatures`, {
        creature_id: creatureId
    });
    if (response.data.success) {
        document.getElementById('creature-list').innerHTML = response.data.html;
        refreshChosenCreatures();
    };
}

// Show the edit form for creatures
function showCreatureEdit(index) {
    let creature = document.getElementById(`creature-${index}`);
    let edit = document.getElementById(`edit-${index}`);
    let stat = document.getElementById(`stat-${index}`);

    let selectedCreature = chosenCreatures[index];

    creature.classList.remove('block');
    creature.classList.add('hidden');
    edit.classList.remove('hidden');
    edit.classList.add('block');
    stat.classList.remove('hidden');
    stat.classList.add('block');

    stat.innerHTML = `
        <div class="flex flex-row gap-12 p-2 pt-4 justify-evenly">
            <div class="flex flex-row gap-3">
                <div class="bg-tertiary w-10 h-10 text-center content-center rounded-full text-lg">${selectedCreature.level}</div>
                <div class="text-center content-center text-accent hidden" id="preview-level-${index}"></div>
            </div>
            <div class="w-1/3 flex flex-row gap-4">
                <div class="font-semibold content-center">HP</div>
                <div class="flex flex-row w-11/12 gap-3">
                    <div class="w-full h-6 bg-red-900 text-center self-center rounded-lg">${selectedCreature.hp}</div>
                    <div class="text-center content-center text-accent hidden" id="preview-hp-${index}"></div>
                </div>
            </div>
        </div>
        <div class="p-3 flex flex-row text-center justify-evenly">
            <div class="justify-items-center">
                <div class="font-semibold">AC</div>
                <div class="flex flex-row gap-3">
                    <div class="bg-tertiary w-8 h-8 mt-1 rounded-lg content-center">${selectedCreature.ac}</div>
                    <div class="text-center content-center text-accent hidden" id="preview-ac-${index}"></div>
                </div>
            </div>
            <div class="justify-items-center">
                <div class="font-semibold">Fortitude</div>
                <div class="flex flex-row gap-3">
                    <div class="bg-tertiary w-8 h-8 mt-1 rounded-lg content-center">${selectedCreature.fortitude}</div>
                    <div class="text-center content-center text-accent hidden" id="preview-fortitude-${index}"></div>
                </div>
            </div>
            <div class="justify-items-center">
                <div class="font-semibold">Reflex</div>
                <div class="flex flex-row gap-3">
                    <div class="bg-tertiary w-8 h-8 mt-1 rounded-lg content-center">${selectedCreature.reflex}</div>
                    <div class="text-center content-center text-accent hidden" id="preview-reflex-${index}"></div>
                </div>
            </div>
            <div class="justify-items-center">
                <div class="font-semibold">Will</div>
                <div class="flex flex-row gap-3">
                    <div class="bg-tertiary w-8 h-8 mt-1 rounded-lg content-center">${selectedCreature.will}</div>
                    <div class="text-center content-center text-accent hidden" id="preview-will-${index}"></div>
                </div>
            </div>
            <div class="justify-items-center">
                <div class="font-semibold">Perception</div>
                <div class="flex flex-row gap-3">
                    <div class="bg-tertiary w-8 h-8 mt-1 rounded-lg content-center">${selectedCreature.perception}</div>
                    <div class="text-center content-center text-accent hidden" id="preview-perception-${index}"></div>
                </div>
            </div>
        </div>
        <div class="text-center hidden" id="warning-${index}"></div>`;
}

// Hide the edit form for creatures
function hideCreatureEdit(index) {
    let creature = document.getElementById(`creature-${index}`);
    let edit = document.getElementById(`edit-${index}`);
    let level = document.getElementById(`level-${index}`);
    let stat = document.getElementById(`stat-${index}`);

    creature.classList.remove('hidden');
    creature.classList.add('block');
    edit.classList.remove('block');
    edit.classList.add('hidden');
    stat.classList.remove('block');
    stat.classList.add('hidden');

    level.value = level.dataset.default;
    buttonPressCount[index] = 0;
    delete editCreature[index];
}

// Update the creature level
async function updateCreature(index) {
    // Check if values are present
    if (!editCreature[index]) {
        console.error('No edit data found for creature', index);
        return;
    }

    // Set the values
    const updateData = {
        ...editCreature[index]
    };

    // Send the values
    const response = await axios.put(`${baseUrl}/creatures/${index}`, updateData);

    // If success rerender/reset everything
    if (response.data.success) {
        document.getElementById('creature-list').innerHTML = response.data.html;
        refreshChosenCreatures();
        buttonPressCount[index] = 0;
        delete editCreature[index];
        calculateXP();
    };
}

// Remove the creature from the correct content array
async function removeCreature(index) {
    const response = await axios.delete(`${baseUrl}/creatures/${index}`);
    if (response.data.success) {
        document.getElementById('creature-list').innerHTML = response.data.html;
        refreshChosenCreatures();
    };
}

// Calculate encounter XP
async function calculateXP() {
    const response = await axios.post(`${baseUrl}/calculate`, {
        party_size: partySize.value,
        party_level: partyLevel.value,
    });
    if (response.data.success) {
        document.getElementById('encounterBar').innerHTML = response.data.html;
        document.getElementById('selectThreat').innerHTML = response.data.select;
        document.getElementById('randomizeButton').innerHTML = response.data.randomize;
        if (response.data.creatureCount > 0) {
            document.getElementById('creatureAmount').innerHTML = '+' + response.data.creatureCount;
        } else {
            document.getElementById('creatureAmount').innerHTML = '';
        }
    }
}

// Show Stats on mouseover
function showStatsInfo (index, arrayName) {
    refreshNewCreatures ();
    let selectedCreature = window[arrayName][index];
    let hover = document.getElementById(`${arrayName}-${index}`);
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
function hideStatsInfo (index, arrayName) {
    let hover = document.getElementById(`${arrayName}-${index}`);
    hover.classList.remove('block');
    hover.classList.add('hidden');
}

// Refresh chosen creatures array
function refreshChosenCreatures() {
    const dataContainer = document.getElementById('creatureData-container');

    // Get data
    if (dataContainer) {
        chosenCreatures = JSON.parse(dataContainer.dataset.chosenCreatures || '[]');
        window.chosenCreatures = chosenCreatures;
    }
}

function refreshNewCreatures () {
    const newDataContainer = document.getElementById('newCreatureData-container');

    if (newDataContainer) {
        newCreatures = JSON.parse(newDataContainer.dataset.new_creatures || '[]');
        window.newCreatures = newCreatures;
    }
}

// Add a level to a creature
function addLevel(index) {
    const levelInput = document.getElementById(`level-${index}`);

    // Prevent going above 25
    if (levelInput.value === "25") {
        return;
    }

    if (!buttonPressCount[index]) {
        buttonPressCount[index] = 0;
    }

    // Change input value
    if (levelInput.value <= 0) {
        levelInput.stepUp();
        levelInput.stepUp();
        buttonPressCount[index] += 1;
    } else {
        levelInput.stepUp();
        buttonPressCount[index] += 1;
    }

    // Calculate and show adjustments for this specific creature
    calculateAdjustments(index);
}

// Subtract a level for a creature
function subtractLevel(index) {
    const levelInput = document.getElementById(`level-${index}`);
    const creature = chosenCreatures[index];
    const originalLevel = creature.original_level || creature.level;

    // Prevent going below -1
    if (levelInput.value === "-1") {
        return;
    }

    if (!buttonPressCount[index]) {
        buttonPressCount[index] = 0;
    }

    // Change input value
    if (levelInput.value === "1") {
        levelInput.stepDown();
        levelInput.stepDown();
        buttonPressCount[index] -= 1;
    } else if (levelInput.value === "2" && originalLevel === 0) {
        levelInput.stepDown();
        levelInput.stepDown();
        buttonPressCount[index] -= 1;
    } else {
        levelInput.stepDown();
        buttonPressCount[index] -= 1;
    }

    // Calculate and show adjustments for this specific creature
    calculateAdjustments(index);
}

function calculateAdjustments(index) {
    const levelInput = document.getElementById(`level-${index}`);
    const creature = chosenCreatures[index];

    // Calculate level based on difference from original
    const adjustmentCount = Number(levelInput.value) - creature.level;

    // Calculate HP and other stats based on buttons pressed
    let hpDiff = 0;
    let statDiff = 0;

    if (buttonPressCount[index] > 0) {
        // Elite adjustments
        for (let i = 0; i < buttonPressCount[index]; i++) {
            const currentLevel = creature.level + i;

            // HP adjustment
            if (currentLevel <= 1) {
                hpDiff += 10;
            } else if (currentLevel >= 2 && currentLevel <= 4) {
                hpDiff += 15;
            } else if (currentLevel >= 5 && currentLevel <= 19) {
                hpDiff += 20;
            } else if (currentLevel >= 20) {
                hpDiff += 30;
            }

            // Stats: +2 per adjustment
            statDiff += 2;
        }

    } else if (buttonPressCount[index] < 0) {
        // Weak adjustments
        for (let i = 0; i < Math.abs(buttonPressCount[index]); i++) {
            const currentLevel = creature.level - i;

            // HP adjustment
            if (currentLevel >= 1 && currentLevel <= 2) {
                hpDiff -= 10;
            } else if (currentLevel >= 3 && currentLevel <= 5) {
                hpDiff -= 15;
            } else if (currentLevel >= 6 && currentLevel <= 20) {
                hpDiff -= 20;
            } else if (currentLevel >= 21) {
                hpDiff -= 30;
            }

            // Stats: -2 per adjustment
            statDiff -= 2;
        }
    }

    // Set stat changes for updateCreature()
    editCreature[index] = {
        originalLevel: creature.level,
        level: Number(levelInput.value),
        hp: creature.hp + hpDiff,
        ac: creature.ac + statDiff,
        fortitude: creature.fortitude + statDiff,
        reflex: creature.reflex + statDiff,
        will: creature.will + statDiff,
        perception: creature.perception + statDiff,
    };

    // Show changes for this specific creature
    showStatDiff(index, adjustmentCount, hpDiff, statDiff);
}

// Show +/- values that will apply for changing the level
function showStatDiff(index, adjustmentCount, hpDiff, statDiff) {
    // Get elements
    const levelPreview = document.getElementById(`preview-level-${index}`);
    const hpPreview = document.getElementById(`preview-hp-${index}`);
    const acPreview = document.getElementById(`preview-ac-${index}`);
    const fortitudePreview = document.getElementById(`preview-fortitude-${index}`);
    const reflexPreview = document.getElementById(`preview-reflex-${index}`);
    const willPreview = document.getElementById(`preview-will-${index}`);
    const perceptionPreview = document.getElementById(`preview-perception-${index}`);
    const warning = document.getElementById(`warning-${index}`);
    const levelInput = document.getElementById(`level-${index}`);
    const creature = chosenCreatures[index];

    const originalLevel = creature.original_level || creature.level;
    const currentInputLevel = Number(levelInput.value);

    const diffLevel = currentInputLevel - originalLevel;

    // Set warning
    if (Math.abs(diffLevel) > 2) {
        warning.innerHTML = `
            Multiple elite/weak adjustments are not recommended. 
            Consider using the creature builder for building creatures instead.`;
    } else {
        warning.innerHTML = '';
    }

    // Signs
    let levelSign = adjustmentCount > 0 ? '+' : '';
    let hpSign = hpDiff > 0 ? '+' : '';
    let statSign = statDiff > 0 ? '+' : '';

    // Set signs and numbers
    levelPreview.textContent = (adjustmentCount !== 0 ? ` ${levelSign}${adjustmentCount}` : '');
    hpPreview.textContent = (hpDiff !== 0 ? ` ${hpSign}${hpDiff}` : '');
    acPreview.textContent = (statDiff !== 0 ? ` ${statSign}${statDiff}` : '');
    fortitudePreview.textContent = (statDiff !== 0 ? `${statSign}${statDiff}` : '');
    reflexPreview.textContent = (statDiff !== 0 ? `${statSign}${statDiff}` : '');
    willPreview.textContent = (statDiff !== 0 ? `${statSign}${statDiff}` : '');
    perceptionPreview.textContent = (statDiff !== 0 ? `${statSign}${statDiff}` : '');

    // Show signs and numbers
    const elements = [
        `preview-level-${index}`, `preview-hp-${index}`, `preview-ac-${index}`,
        `preview-fortitude-${index}`, `preview-reflex-${index}`, `preview-will-${index}`,
        `preview-perception-${index}`, `warning-${index}`
    ];

    elements.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.classList.remove('hidden');
            element.classList.add('block');
        }
    });
}
