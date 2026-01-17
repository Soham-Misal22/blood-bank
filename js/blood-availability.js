/**
 * Blood Availability Handler
 * Fetches and displays blood inventory from API
 */

document.addEventListener('DOMContentLoaded', function () {
    loadBloodAvailability();
});

async function loadBloodAvailability() {
    const tableBody = document.querySelector('.blood-table tbody');

    if (!tableBody) return;

    try {
        // Show loading state
        tableBody.innerHTML = `
      <tr>
        <td colspan="3" class="text-center">
          <i class="fas fa-spinner fa-spin"></i> Loading blood availability...
        </td>
      </tr>
    `;

        // Fetch inventory from API
        const response = await inventoryAPI.getAll();

        // Clear loading state
        tableBody.innerHTML = '';

        // Sort blood types in a specific order
        const bloodTypeOrder = ['A+', 'O+', 'B+', 'AB+', 'A-', 'O-', 'B-', 'AB-'];
        const sortedInventory = response.data.sort((a, b) => {
            return bloodTypeOrder.indexOf(a.bloodType) - bloodTypeOrder.indexOf(b.bloodType);
        });

        // Populate table
        sortedInventory.forEach((item) => {
            const row = document.createElement('tr');

            // Determine status class
            let statusClass = 'available';
            if (item.status === 'Limited') statusClass = 'limited';
            if (item.status === 'Critical') statusClass = 'critical';

            row.innerHTML = `
        <td class="blood-type-cell">${item.bloodType}</td>
        <td class="${statusClass}">${item.status}</td>
        <td class="units-count">${item.unitsAvailable}</td>
      `;

            tableBody.appendChild(row);
        });

        // Update last updated time
        const lastUpdatedEl = document.querySelector('.section-title p.text-muted');
        if (lastUpdatedEl) {
            const lastUpdated = new Date().toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            });
            lastUpdatedEl.textContent = `Updated daily - Last updated: ${lastUpdated}`;
        }
    } catch (error) {
        console.error('Error loading blood availability:', error);
        tableBody.innerHTML = `
      <tr>
        <td colspan="3" class="text-center text-danger">
          <i class="fas fa-exclamation-triangle"></i> Failed to load blood availability.
          <br>
          <small>Please make sure the backend server is running.</small>
        </td>
      </tr>
    `;
    }
}

// Auto-refresh every 30 seconds
setInterval(loadBloodAvailability, 30000);
