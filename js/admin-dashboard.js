/**
 * Admin Dashboard JavaScript
 * Handles all admin operations for donors, receivers, and inventory
 */

// Protect this page - redirect if not logged in
const admin = protectAdminPage();

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function () {
    // Display admin info
    displayAdminInfo();

    // Load dashboard stats
    loadDashboardStats();

    // Setup navigation
    setupNavigation();

    // Load initial content
    loadDonorsTable();
    loadReceiversTable();
    loadInventoryTable();
});

// Display admin information
function displayAdminInfo() {
    if (admin) {
        document.getElementById('adminName').textContent = admin.name;
        document.getElementById('adminEmail').textContent = admin.email;
        document.getElementById('adminAvatar').textContent = admin.name.charAt(0).toUpperCase();
    }
}

// Load dashboard statistics
async function loadDashboardStats() {
    try {
        const [donors, receivers, inventory] = await Promise.all([
            donorsAPI.getAll(),
            receiversAPI.getAll(),
            inventoryAPI.getAll(),
        ]);

        document.getElementById('totalDonors').textContent = donors.count || 0;
        document.getElementById('totalReceivers').textContent = receivers.count || 0;

        const totalUnits = inventory.data.reduce((sum, item) => sum + item.unitsAvailable, 0);
        document.getElementById('totalUnits').textContent = totalUnits;

        const criticalCount = inventory.data.filter(
            (item) => item.status === 'Critical' || item.status === 'Limited'
        ).length;
        document.getElementById('criticalTypes').textContent = criticalCount;
    } catch (error) {
        console.error('Error loading dashboard stats:', error);
    }
}

// Setup navigation
function setupNavigation() {
    const menuLinks = document.querySelectorAll('.menu-link');
    menuLinks.forEach((link) => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const section = this.dataset.section;
            navigateToSection(section);
        });
    });
}

// Navigate to section
function navigateToSection(sectionName) {
    // Update active menu
    document.querySelectorAll('.menu-link').forEach((link) => {
        link.classList.remove('active');
    });
    document.querySelector(`[data-section="${sectionName}"]`).classList.add('active');

    // Show section
    document.querySelectorAll('.content-section').forEach((section) => {
        section.classList.remove('active');
    });
    document.getElementById(sectionName).classList.add('active');
}

// ======================
// DONORS MANAGEMENT
// ======================

async function loadDonorsTable() {
    const container = document.getElementById('donorsContent');
    container.innerHTML = '<p>Loading donors...</p>';

    try {
        const response = await donorsAPI.getAll();
        const donors = response.data;

        if (donors.length === 0) {
            container.innerHTML = '<p>No donors found.</p>';
            return;
        }

        let html = `
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead class="thead-dark">
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Blood Group</th>
              <th>Contact</th>
              <th>Gender</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
    `;

        donors.forEach((donor) => {
            html += `
        <tr>
          <td>${donor.name}</td>
          <td>${donor.email}</td>
          <td><span class="badge badge-danger">${donor.bloodGroup}</span></td>
          <td>${donor.contact}</td>
          <td>${donor.gender}</td>
          <td><span class="badge badge-${donor.status === 'active' ? 'success' : 'secondary'}">${donor.status}</span></td>
          <td>
            <button class="btn btn-sm btn-info" onclick="viewDonor('${donor._id}')">
              <i class="fas fa-eye"></i>
            </button>
            <button class="btn btn-sm btn-danger" onclick="deleteDonor('${donor._id}', '${donor.name}')">
              <i class="fas fa-trash"></i>
            </button>
          </td>
        </tr>
      `;
        });

        html += '</tbody></table></div>';
        container.innerHTML = html;
    } catch (error) {
        container.innerHTML = `<div class="alert alert-danger">Error loading donors: ${error.message}</div>`;
    }
}

async function viewDonor(id) {
    try {
        const response = await donorsAPI.getById(id);
        const donor = response.data;

        alert(`Donor Details:\n\nName: ${donor.name}\nEmail: ${donor.email}\nBlood Group: ${donor.bloodGroup}\nContact: ${donor.contact}\nGender: ${donor.gender}\nDOB: ${new Date(donor.dob).toLocaleDateString()}\nStatus: ${donor.status}`);
    } catch (error) {
        alert('Error loading donor details: ' + error.message);
    }
}

async function deleteDonor(id, name) {
    if (!confirm(`Are you sure you want to delete donor "${name}"?`)) {
        return;
    }

    try {
        await donorsAPI.delete(id);
        alert('Donor deleted successfully!');
        loadDonorsTable();
        loadDashboardStats();
    } catch (error) {
        alert('Error deleting donor: ' + error.message);
    }
}

// ======================
// RECEIVERS MANAGEMENT
// ======================

async function loadReceiversTable() {
    const container = document.getElementById('receiversContent');
    container.innerHTML = '<p>Loading blood requests...</p>';

    try {
        const response = await receiversAPI.getAll();
        const receivers = response.data;

        if (receivers.length === 0) {
            container.innerHTML = '<p>No blood requests found.</p>';
            return;
        }

        let html = `
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead class="thead-dark">
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Blood Group</th>
              <th>Hospital</th>
              <th>Units Needed</th>
              <th>Urgency</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
    `;

        receivers.forEach((receiver) => {
            const urgencyBadge =
                receiver.urgencyLevel === 'critical'
                    ? 'danger'
                    : receiver.urgencyLevel === 'urgent'
                        ? 'warning'
                        : 'info';

            html += `
        <tr>
          <td>${receiver.name}</td>
          <td>${receiver.email}</td>
          <td><span class="badge badge-danger">${receiver.bloodGroup}</span></td>
          <td>${receiver.hospital}</td>
          <td>${receiver.unitsNeeded}</td>
          <td><span class="badge badge-${urgencyBadge}">${receiver.urgencyLevel}</span></td>
          <td><span class="badge badge-secondary">${receiver.status}</span></td>
          <td>
            <button class="btn btn-sm btn-info" onclick="viewReceiver('${receiver._id}')">
              <i class="fas fa-eye"></i>
            </button>
            <button class="btn btn-sm btn-success" onclick="updateReceiverStatus('${receiver._id}', 'fulfilled')">
              <i class="fas fa-check"></i>
            </button>
            <button class="btn btn-sm btn-danger" onclick="deleteReceiver('${receiver._id}', '${receiver.name}')">
              <i class="fas fa-trash"></i>
            </button>
          </td>
        </tr>
      `;
        });

        html += '</tbody></table></div>';
        container.innerHTML = html;
    } catch (error) {
        container.innerHTML = `<div class="alert alert-danger">Error loading requests: ${error.message}</div>`;
    }
}

async function viewReceiver(id) {
    try {
        const response = await receiversAPI.getById(id);
        const receiver = response.data;

        alert(`Blood Request Details:\n\nName: ${receiver.name}\nEmail: ${receiver.email}\nBlood Group: ${receiver.bloodGroup}\nHospital: ${receiver.hospital}\nUnits Needed: ${receiver.unitsNeeded}\nUrgency: ${receiver.urgencyLevel}\nStatus: ${receiver.status}\nMedical Condition: ${receiver.medicalCondition || 'N/A'}`);
    } catch (error) {
        alert('Error loading request details: ' + error.message);
    }
}

async function updateReceiverStatus(id, status) {
    try {
        await receiversAPI.update(id, { status });
        alert('Request status updated successfully!');
        loadReceiversTable();
    } catch (error) {
        alert('Error updating status: ' + error.message);
    }
}

async function deleteReceiver(id, name) {
    if (!confirm(`Are you sure you want to delete request from "${name}"?`)) {
        return;
    }

    try {
        await receiversAPI.delete(id);
        alert('Request deleted successfully!');
        loadReceiversTable();
        loadDashboardStats();
    } catch (error) {
        alert('Error deleting request: ' + error.message);
    }
}

// ======================
// INVENTORY MANAGEMENT
// ======================

async function loadInventoryTable() {
    const container = document.getElementById('inventoryContent');
    container.innerHTML = '<p>Loading inventory...</p>';

    try {
        const response = await inventoryAPI.getAll();
        const inventory = response.data;

        let html = `
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead class="thead-dark">
            <tr>
              <th>Blood Type</th>
              <th>Units Available</th>
              <th>Status</th>
              <th>Last Updated</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
    `;

        inventory.forEach((item) => {
            const statusBadge =
                item.status === 'Available'
                    ? 'success'
                    : item.status === 'Limited'
                        ? 'warning'
                        : 'danger';

            html += `
        <tr>
          <td><strong>${item.bloodType}</strong></td>
          <td><h5>${item.unitsAvailable}</h5></td>
          <td><span class="badge badge-${statusBadge}">${item.status}</span></td>
          <td>${new Date(item.lastUpdated).toLocaleDateString()}</td>
          <td>
            <button class="btn btn-sm btn-success" onclick="showUpdateInventory('${item.bloodType}', ${item.unitsAvailable}, 'add')">
              <i class="fas fa-plus"></i> Add
            </button>
            <button class="btn btn-sm btn-warning" onclick="showUpdateInventory('${item.bloodType}', ${item.unitsAvailable}, 'remove')">
              <i class="fas fa-minus"></i> Remove
            </button>
            <button class="btn btn-sm btn-info" onclick="showUpdateInventory('${item.bloodType}', ${item.unitsAvailable}, 'set')">
              <i class="fas fa-edit"></i> Set
            </button>
          </td>
        </tr>
      `;
        });

        html += '</tbody></table></div>';
        container.innerHTML = html;
    } catch (error) {
        container.innerHTML = `<div class="alert alert-danger">Error loading inventory: ${error.message}</div>`;
    }
}

function showUpdateInventory(bloodType, current, action) {
    let message, units;

    if (action === 'add') {
        units = prompt(`Add units to ${bloodType} (Current: ${current}):`);
        if (units) updateInventory(bloodType, 'add', parseInt(units));
    } else if (action === 'remove') {
        units = prompt(`Remove units from ${bloodType} (Current: ${current}):`);
        if (units) updateInventory(bloodType, 'remove', parseInt(units));
    } else if (action === 'set') {
        units = prompt(`Set ${bloodType} units (Current: ${current}):`);
        if (units) updateInventory(bloodType, 'set', parseInt(units));
    }
}

async function updateInventory(bloodType, action, units) {
    if (isNaN(units) || units <= 0) {
        alert('Please enter a valid number');
        return;
    }

    try {
        if (action === 'add') {
            await inventoryAPI.addUnits(bloodType, units);
            alert(`Added ${units} units to ${bloodType}`);
        } else if (action === 'remove') {
            await inventoryAPI.removeUnits(bloodType, units);
            alert(`Removed ${units} units from ${bloodType}`);
        } else if (action === 'set') {
            await inventoryAPI.update(bloodType, units);
            alert(`Set ${bloodType} to ${units} units`);
        }

        loadInventoryTable();
        loadDashboardStats();
    } catch (error) {
        alert('Error updating inventory: ' + error.message);
    }
}
