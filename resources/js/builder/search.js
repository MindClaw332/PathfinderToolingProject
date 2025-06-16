// Get elements
const inputCreature = document.getElementById('inputCreature');
const inputHazard = document.getElementById('inputHazard');
const filterCreature = document.getElementById('filterCreature');
const filterHazard = document.getElementById('filterHazard');
const creatureList = document.getElementById('creatureList');
const hazardList = document.getElementById('hazardList');

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
    window.resetCreatureFilters = resetCreatureFilters;
    window.resetHazardFilters = resetHazardFilters;

    // Initial render creatures/hazards
    renderCreatures(creatures);
    renderHazards(hazards);
});

// Show the creature filters
function toggleFilterCreature () {
    filterCreature.classList.toggle('hidden');
    filterCreature.classList.toggle('block');

    // Set heights
    const isH140 = creatureList.classList.contains('h-140');
    const isH88 = creatureList.classList.contains('h-94');
    const isH84 = creatureList.classList.contains('h-84');
    const isH38 = creatureList.classList.contains('h-38');

    // Check heights
    if (isH84) {
        creatureList.classList.remove('h-84');
        creatureList.classList.add('h-38');
    }
    if (isH38) {
        creatureList.classList.remove('h-38');
        creatureList.classList.add('h-84');
    }
    if (isH140) {
        creatureList.classList.remove('h-140');
        creatureList.classList.add('h-94');
    }
    if (isH88) {
        creatureList.classList.remove('h-94');
        creatureList.classList.add('h-140');
    }
}

// Show the hazard filters
function toggleFilterHazard () {
    filterHazard.classList.toggle('hidden');
    filterHazard.classList.toggle('block');
    hazardList.classList.toggle('h-36');
    hazardList.classList.toggle('h-4');
}

// General function to select/deselect filters for creatures and hazards
function toggleSelection({ id, dataSource, selectedArray, elementPrefix }) {
    const item = dataSource.find(el => el.id == id);
    const index = selectedArray.findIndex(el => el.id == id);
    const element = document.getElementById(`${elementPrefix}-${id}`);

    // filter selected
    if (index !== -1) {
        selectedArray.splice(index, 1);
        element.classList.remove('bg-accent');
        element.classList.add('bg-tertiary');
    } 
    // filter not selected
    else {
        selectedArray.push(item);
        element.classList.remove('bg-tertiary');
        element.classList.add('bg-accent');
    }
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

    updateFilteredCreatures();
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

    updateFilteredCreatures();
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

    updateFilteredCreatures();
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

    updateFilteredHazards();
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

    updateFilteredHazards();
}

// Oninput event for the search bars
inputCreature.addEventListener('input', updateFilteredCreatures);
inputHazard.addEventListener('input', updateFilteredHazards);

// Filters the creature data
function updateFilteredCreatures() {
    const search = inputCreature.value.toLowerCase();

    const filtered = creatures.filter(creature => {
        // filter input
        const matchesSearch = creature.name.toLowerCase().includes(search);

        // filter traits
        const matchesTraits =
            selectedTraits.length === 0 ||
            selectedTraits.every(trait => creature.pathfindertraits?.some(t => t.id === trait.id));

        // filter sizes
        const matchesSizes =
            selectedSizes.length === 0 ||
            selectedSizes.some(sz => creature.size_id === sz.id);

        // filter rarities
        const matchesRarity =
            selectedRaritiesC.length === 0 ||
            selectedRaritiesC.some(r => creature.rarity_id === r.id);

        return matchesSearch && matchesTraits && matchesSizes && matchesRarity;
    });

    renderCreatures(filtered);
}

// Filters the hazard data
function updateFilteredHazards() {
    const search = inputHazard.value.toLowerCase();

    const filtered = hazards.filter(hazard => {
        // filter input
        const matchesSearch = hazard.name.toLowerCase().includes(search);

        // filter types
        const matchesTypes =
            selectedTypes.length === 0 ||
            selectedTypes.some(t => hazard.type_id === t.id);

        // filter rarities
        const matchesRarity =
            selectedRaritiesH.length === 0 ||
            selectedRaritiesH.some(r => hazard.rarity_id === r.id);

        return matchesSearch && matchesTypes && matchesRarity;
    });

    renderHazards(filtered);
}

// Renders the list of filtered creatures
function renderCreatures(list) {
    // Get element + clear
    const container = document.getElementById('creatureList');
    container.innerHTML = '';

    // Render each result
    list.forEach(creature => {
        // make wrapper
        const wrapper = document.createElement('div');
        wrapper.className = 'p-2';
        wrapper.id = creature.id;
        // add hover
        wrapper.setAttribute('onmouseover', `showCreatureInfo(${creature.id})`);
        wrapper.setAttribute('onmouseout', `hideCreatureInfo(${creature.id})`);
        // add onclick
        wrapper.onclick = async () => {
            await setCreature(creature.id);
            await calculateXP();
        }; 

        // make row
        const row = document.createElement('div');
        row.className = 'flex flex-row justify-between';

        // name
        const name = document.createElement('div');
        name.textContent = creature.name;

        // traits
        const traitContainer = document.createElement('div');
        traitContainer.className = 'flex flex-row gap-2';
        creature.pathfindertraits?.forEach(trait => {
            const traitEl = document.createElement('div');
            traitEl.className = 'bg-tertiary p-1 rounded-lg';
            traitEl.textContent = trait.name;
            traitContainer.appendChild(traitEl);
        });

        // add name and traits, fill wrapper then container
        row.appendChild(name);
        row.appendChild(traitContainer);
        wrapper.appendChild(row);
        container.appendChild(wrapper);
    });
}

// Renders the list of filtered hazards
function renderHazards(list) {
    // Get element + clear
    const container = document.getElementById('hazardList');
    container.innerHTML = '';

    // Render each result
    list.forEach(hazard => {
        // make wrapper
        const wrapper = document.createElement('div');
        wrapper.className = 'flex flex-row justify-between p-2';
        wrapper.id = hazard.id;
        // add hover
        wrapper.setAttribute('onmouseover', `showHazardInfo(${hazard.id})`);
        wrapper.setAttribute('onmouseout', 'hideHazardInfo()');
        // add onclick
        wrapper.setAttribute('onclick', `setHazard(${hazard.id})`);

        // name
        const name = document.createElement('div');
        name.textContent = hazard.name;

        // type
        const type = document.createElement('div');
        type.className = 'bg-tertiary p-1 rounded-lg';
        type.textContent = hazard.type.name;

        // add name and type, fill container
        wrapper.appendChild(name);
        wrapper.appendChild(type);
        container.appendChild(wrapper);
    });
}

// Resets all filters for creatures
function resetCreatureFilters() {
    selectedTraits = [];
    selectedSizes = [];
    selectedRaritiesC = [];

    // Reset button styles
    document.querySelectorAll('[id^="trait-"], [id^="size-"], [id^="rarityC-"]').forEach(el => {
        el.classList.remove('bg-accent');
        el.classList.add('bg-tertiary');
    });

    // Clear search input
    inputCreature.value = '';

    // Refresh creature list
    updateFilteredCreatures();
}

// Resets all filters for hazards
function resetHazardFilters() {
    selectedTypes = [];
    selectedRaritiesH = [];

    // Reset button styles
    document.querySelectorAll('[id^="type-"], [id^="rarityH-"]').forEach(el => {
        el.classList.remove('bg-accent');
        el.classList.add('bg-tertiary');
    });

    // Clear search input
    inputHazard.value = '';

    // Refresh hazard list
    updateFilteredHazards();
}
