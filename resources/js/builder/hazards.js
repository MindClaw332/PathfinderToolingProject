let baseUrl = `/content/${contentId}`;

// Get data + Make functions globally available
document.addEventListener('DOMContentLoaded', function() {
    // Make functions globally available
    window.setHazard = setHazard;
    window.removeHazard = removeHazard;
});


// Add the clicked hazard to the correct content array
async function setHazard(hazardId) {
    const response = await axios.post(`${baseUrl}/hazards`, {
        hazard_id: hazardId
    });
    if (response.data.success) {
        document.getElementById('hazard-list').innerHTML = response.data.html;
        if (response.data.hazardCount > 0) {
            document.getElementById('hazardAmount').innerHTML = '+' + response.data.hazardCount;
        } else {
            document.getElementById('hazardAmount').innerHTML = '';
        }
    };
}

// Remove the hazard from the correct content array
async function removeHazard(index) {
    const response = await axios.delete(`${baseUrl}/hazards/${index}`);
    if (response.data.success) {
        document.getElementById('hazard-list').innerHTML = response.data.html;
        if (response.data.hazardCount > 0) {
            document.getElementById('hazardAmount').innerHTML = '+' + response.data.hazardCount;
        } else {
            document.getElementById('hazardAmount').innerHTML = '';
        }
    };
}