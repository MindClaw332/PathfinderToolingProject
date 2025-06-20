let chosenHazards, chosenCreatures;
let baseUrl = `/content/${contentId}`;

// Make functions globally available
document.addEventListener('DOMContentLoaded', function() { 
    // Make functions globally available
    window.selectedFilter = selectedFilter;
    window.resetSizes = resetSizes;
    window.showFilter = showFilter;
    window.randomize = randomize;
    window.resetRandomize =  resetRandomize;
});

const selectedFilters = {
        trait: null,
        type: null,  
        size: [],    
    };

// Select and set the filters to an array
function selectedFilter (button, filterType, id) {
    if (filterType === 'trait' || filterType === 'type') {
        if (selectedFilters[filterType] === id) {
            // If clicked again, deselect
            selectedFilters[filterType] = null;
            button.classList.remove('bg-accent', 'text-white');
            button.classList.add('bg-tertiary');
        } else {
            // Deselect previous if exists
            if (selectedFilters[filterType] !== null) {
                const prevId = selectedFilters[filterType];
                const prevButton = document.getElementById(`randomize-${filterType}-${prevId}`);
                if (prevButton) {
                    prevButton.classList.remove('bg-accent', 'text-white');
                    prevButton.classList.add('bg-tertiary');
                }
            }
            // Select this one
            selectedFilters[filterType] = id;
            button.classList.remove('bg-tertiary');
            button.classList.add('bg-accent', 'text-white');
        }
    } else if (filterType === 'size') {
        // Toggle selection for sizes
        const index = selectedFilters.size.indexOf(id);
        if (index > -1) {
            // Already selected, so deselect
            selectedFilters.size.splice(index, 1);
            button.classList.remove('bg-accent', 'text-white');
            button.classList.add('bg-tertiary');
        } else {
            // Not selected, so select it
            selectedFilters.size.push(id);
            button.classList.remove('bg-tertiary');
            button.classList.add('bg-accent', 'text-white');
        }
    }

    console.log(selectedFilters);
}

// Reset the sizes filter
function resetSizes() {
    selectedFilters.size.forEach(id => {
        const btn = document.getElementById(`randomize-size-${id}`);
        if (btn) {
            btn.classList.remove('bg-accent', 'text-white');
            btn.classList.add('bg-tertiary');
        }
    });
    selectedFilters.size = [];
    console.log('Sizes reset:', selectedFilters);
}

// Toggle showing filters
function showFilter (type) {
    let sort = document.getElementById(`${type}`);

    // Toggle showing filters
    sort.classList.toggle('hidden');
    sort.classList.toggle('block');

    // Check if sizes, then toggle reset button
    if (type === 'hideSizes') {
        let button = document.getElementById('resetSizes');

        button.classList.toggle('hidden');
        button.classList.toggle('block');
    }
}

// Get data needed to randomize, Show randomized result
async function randomize() {
    try {
        // Get data
        const creatureAmount = Number(document.getElementById('creatureInput').value);
        const hazardAmount = Number(document.getElementById('hazardInput').value);
        const partySize = Number(document.getElementById('partySize').value);
        const partyLevel = Number(document.getElementById('partyLevel').value);
        const threatLevel = document.getElementById('threatLevel').value;
        const creatureDataContainer = document.getElementById('creatureData-container');
        const hazardDataContainer = document.getElementById('hazardData-container');
        
        if (creatureDataContainer) {
            chosenCreatures = JSON.parse(creatureDataContainer.dataset.chosen_creatures || '[]');
        }
        if (hazardDataContainer) {
            chosenHazards = JSON.parse(hazardDataContainer.dataset.chosen_hazards || '[]');
        }

        // Validate required fields
        if (!partyLevel || !partySize) {
            console.error('Party level and size are required');
            return;
        }
        console.log(chosenCreatures)
        console.log(chosenHazards)

        // Set data
        const updateData = {
            partyLevel: partyLevel,
            partySize: partySize,
            creatureAmount: creatureAmount,
            hazardAmount: hazardAmount,
            chosenCreatures: chosenCreatures,
            chosenHazards: chosenHazards,
            threatLevel: threatLevel,
            selectedTrait: selectedFilters.trait,
            selectedType: selectedFilters.type,
            selectedSizes: selectedFilters.size,
        };

        console.log('Sending data:', updateData);

        // Send data - fixed URL to match Laravel route
        const response = await axios.post(`${baseUrl}/randomize`, updateData,);

        // If success, show result
        if (response.data.success) {
            document.getElementById('creatureHTML').innerHTML = response.data.creatureHTML;
            document.getElementById('randomizeButton').innerHTML = response.data.randomize;
            console.log('New creatures:', response.data.newCreatures);
            console.log('New hazards:', response.data.newHazards);
            console.log('XP Budget:', response.data.xpBudget);
            console.log('XP Used:', response.data.xpUsed);
            console.log('XP Remaining:', response.data.xpRemaining);
            
            // Handle the successful response here
            // For example, update the UI with the new creatures and hazards
            
        } else {
            console.error('Request failed:', response.data.message);
        }

    } catch (error) {
        console.error('Error in randomize function:', error);
        
        if (error.response) {
            // Server responded with error status
            console.error('Response data:', error.response.data);
            console.error('Response status:', error.response.status);
        } else if (error.request) {
            // Request was made but no response received
            console.error('No response received:', error.request);
        } else {
            // Something else happened
            console.error('Error message:', error.message);
        }
    }
}

// Reset the entire ranomization form
function resetRandomize () {

}