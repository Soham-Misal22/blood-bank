/**
 * Receiver Form Handler
 * Handles blood request form submission
 */

document.addEventListener('DOMContentLoaded', function () {
    const receiverForm = document.querySelector('form[name="receiverForm"]');

    if (receiverForm) {
        receiverForm.addEventListener('submit', handleReceiverSubmit);
    }
});

async function handleReceiverSubmit(e) {
    e.preventDefault();

    const form = e.target;
    const submitButton = form.querySelector('button[type="submit"]');

    // Get form data
    const formData = new FormData(form);
    const receiverData = {
        name: formData.get('name'),
        email: formData.get('email'),
        contact: formData.get('contact'),
        bloodGroup: formData.get('blood_group').toUpperCase(),
        hospital: formData.get('hospital'),
        address: formData.get('address'),
        medicalCondition: formData.get('medical_condition'),
        urgencyLevel: formData.get('urgency_level') || 'normal',
        unitsNeeded: parseInt(formData.get('units_needed')),
        notes: formData.get('notes'),
    };

    // Show loading state
    const originalButtonText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML =
        '<i class="fas fa-spinner fa-spin mr-1"></i> Submitting Request...';

    try {
        // Submit to API
        const response = await receiversAPI.create(receiverData);

        // Show success message
        ui.showSuccess(
            response.message || 'Blood request submitted successfully! We will contact you soon.'
        );

        // Reset form
        form.reset();

        // Optional: Redirect to home page after 2 seconds
        setTimeout(() => {
            // window.location.href = 'Index.html';
        }, 2000);
    } catch (error) {
        // Show error message
        ui.showError(error.message || 'Failed to submit blood request');
    } finally {
        // Restore button
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
    }
}
