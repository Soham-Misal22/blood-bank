/**
 * Donor Form Handler
 * Handles donor registration form submission
 */

document.addEventListener('DOMContentLoaded', function () {
    const donorForm = document.querySelector('form[name="donorForm"]');

    if (donorForm) {
        donorForm.addEventListener('submit', handleDonorSubmit);
    }
});

async function handleDonorSubmit(e) {
    e.preventDefault();

    const form = e.target;
    const submitButton = form.querySelector('button[type="submit"]');

    // Get form data
    const formData = new FormData(form);
    const donorData = {
        name: formData.get('name'),
        email: formData.get('email'),
        contact: formData.get('contact'),
        dob: formData.get('dob'),
        gender: formData.get('gender'),
        bloodGroup: formData.get('blood_group').toUpperCase(),
        height: formData.get('height') ? parseInt(formData.get('height')) : undefined,
        weight: formData.get('weight') ? parseInt(formData.get('weight')) : undefined,
        address: formData.get('address'),
        donationCount: formData.get('donation_count')
            ? parseInt(formData.get('donation_count'))
            : 0,
        lastDonationDate: formData.get('last_donation_date') || undefined,
        healthInfo: {
            recentIllness: formData.get('recent_illness') || 'no',
            substanceUse: formData.get('substance_use') || 'no',
            medicalProcedures: formData.get('medical_procedures') || 'no',
            chronicDiseases: formData.get('chronic_diseases') || 'no',
            pregnancy: formData.get('pregnancy') || 'N/A',
        },
    };

    // Show loading state
    const originalButtonText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML =
        '<i class="fas fa-spinner fa-spin mr-1"></i> Submitting...';

    try {
        // Submit to API
        const response = await donorsAPI.create(donorData);

        // Show success message
        ui.showSuccess(response.message || 'Donor registered successfully!');

        // Reset form
        form.reset();

        // Optional: Redirect to home or thank you page after 2 seconds
        setTimeout(() => {
            // window.location.href = 'Index.html';
        }, 2000);
    } catch (error) {
        // Show error message
        ui.showError(error.message || 'Failed to submit donor registration');
    } finally {
        // Restore button
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
    }
}

// Remove old validation function as it's now handled by the API
