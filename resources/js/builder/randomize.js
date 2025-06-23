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
        // Clear previous errors
        clearErrorMessage();
        showLoadingState();

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
            document.getElementById('hazardHTML').innerHTML = response.data.hazardHTML;
            console.log('New creatures:', response.data.newCreatures);
            console.log('New hazards:', response.data.newHazards);
            console.log('XP Budget:', response.data.xpBudget);
            console.log('XP Used:', response.data.xpUsed);
            console.log('XP Remaining:', response.data.xpRemaining);
            
        } else {
            console.error('Request failed:', response.data.message);
            showErrorMessage(response.data.message, response.data.details?.suggestions);
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
    } finally {
        hideLoadingState();
    }
}

// Reset the entire ranomization form
function resetRandomize () {

}

// Show error message
function showErrorMessage(message, suggestions = []) {
    const errorContainer = document.getElementById('error-container');
    
    let html = `
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <div class="flex-1">
                    <h3 class="font-medium">No Results Found</h3>
                    <p class="mt-1">${message}</p>`;
    
    if (suggestions && suggestions.length > 0) {
        html += `
                    <div class="mt-3">
                        <p class="font-medium text-sm">Try these suggestions:</p>
                        <ul class="mt-1 list-disc list-inside text-sm space-y-1">`;
        suggestions.forEach(suggestion => {
            html += `<li>${suggestion}</li>`;
        });
        html += `
                        </ul>
                    </div>`;
    }
    
    html += `
                </div>
            </div>
        </div>`;
    
    errorContainer.innerHTML = html;
    errorContainer.classList.remove('hidden');
}

// Hide error message
function clearErrorMessage() {
    const errorContainer = document.getElementById('error-container');
    errorContainer.innerHTML = '';
    errorContainer.classList.add('hidden');
}

// Show generating 
function showLoadingState() {
    const button = document.querySelector('[onclick="randomize()"]');
    if (button) {
        button.disabled = true;
        button.textContent = 'Generating...';
        button.classList.add('opacity-50');
    }
}

// Hide generating
function hideLoadingState() {
    const button = document.querySelector('[onclick="randomize()"]');
    if (button) {
        button.disabled = false;
        button.textContent = 'randomize';
        button.classList.remove('opacity-50');
    }
}