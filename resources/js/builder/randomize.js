
// Make functions globally available
document.addEventListener('DOMContentLoaded', function() { 
    // Make functions globally available
    window.selectedFilter = selectedFilter;
    window.resetSizes = resetSizes;
    window.showFilter = showFilter;
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