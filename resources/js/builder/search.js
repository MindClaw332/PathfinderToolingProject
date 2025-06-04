// Get elements
const inputCreature = document.getElementById('inputCreature');
const inputHazard = document.getElementById('inputHazard');
const filterCreature = document.getElementById('filterCreature');
const filterHazard = document.getElementById('filterHazard');

let hazards, creatures, traits, sizes, rarities, types;

// Get data + Make functions globally available
document.addEventListener('DOMContentLoaded', function() {
    const dataContainer = document.getElementById('data-container');
    
    // Get data
    if (dataContainer) {
        hazards = JSON.parse(dataContainer.dataset.hazards || '[]');
        creatures = JSON.parse(dataContainer.dataset.creatures || '[]');
        traits = JSON.parse(dataContainer.dataset.traits || '[]');
        sizes = JSON.parse(dataContainer.dataset.sizes || '[]');
        rarities = JSON.parse(dataContainer.dataset.rarities || '[]');
        types = JSON.parse(dataContainer.dataset.types || '[]');
    }
    
    // Make functions globally available
    window.toggleFilterCreature = toggleFilterCreature;
    window.toggleFilterHazard = toggleFilterHazard;
    window.toggleSelectCreatureTrait = toggleSelectCreatureTrait;
    window.toggleSelectedCreatureSize = toggleSelectedCreatureSize;
    window.toggleSelectedCreatureRarity = toggleSelectedCreatureRarity;
    window.toggleSelectedHazardType = toggleSelectedHazardType;
    window.toggleSelectedHazardRarity = toggleSelectedHazardRarity;
});

// Show the creature filters
function toggleFilterCreature () {
    filterCreature.classList.toggle('hidden');
    filterCreature.classList.toggle('block');
}

// Show the hazard filters
function toggleFilterHazard () {
    filterHazard.classList.toggle('hidden');
    filterHazard.classList.toggle('block');
}

// General function to select/deselect filters for creatures and hazards
function toggleSelection({ id, dataSource, selectedArray, elementPrefix }) {
    const item = dataSource.find(el => el.id == id);
    const index = selectedArray.findIndex(el => el.id == id);
    const element = document.getElementById(`${elementPrefix}-${id}`);

    if (index !== -1) {
        selectedArray.splice(index, 1);
        element.classList.remove('bg-accent');
        element.classList.add('bg-tertiary');
    } else {
        selectedArray.push(item);
        element.classList.remove('bg-tertiary');
        element.classList.add('bg-accent');
    }

    // Optional: update filtered view here
    // updateFilteredCreatures();
}

let selectedTraits = [];

// select/deselect traits for creatures
function toggleSelectCreatureTrait(id) {
    toggleSelection({
        id,
        dataSource: traits,
        selectedArray: selectedTraits,
        elementPrefix: 'trait'
    });
}


let selectedSizes = [];

// select/deselect sizes for creatures
function toggleSelectedCreatureSize(id) {
    toggleSelection({
        id,
        dataSource: sizes,
        selectedArray: selectedSizes,
        elementPrefix: 'size'
    });
}

let selectedRaritiesC = [];

// select/deselect rarities for creatures
function toggleSelectedCreatureRarity(id) {
    toggleSelection({
        id,
        dataSource: rarities,
        selectedArray: selectedRaritiesC,
        elementPrefix: 'rarityC'
    });
}

let selectedTypes = [];

// select/deselect rarities for hazards
function toggleSelectedHazardType(id) {
    toggleSelection({
        id,
        dataSource: types,
        selectedArray: selectedTypes,
        elementPrefix: 'type'
    });
}

let selectedRaritiesH = [];

// select/deselect rarities for hazards
function toggleSelectedHazardRarity(id) {
    toggleSelection({
        id,
        dataSource: rarities,
        selectedArray: selectedRaritiesH,
        elementPrefix: 'rarityH'
    });
}