// Listen for when the modal is shown
$('#resetModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var formId = button.data('form-id'); // Get the form ID to reset dynamically

    // Store the form ID in the modal for later use
    var modal = $(this);
    modal.data('form-id', formId); // Store form ID in modal data attribute
});

// When the confirm button is clicked, reset the form
$('#confirmResetBtn').off('click').on('click', function() {
    // Retrieve the form ID from the modal data
    var modal = $('#resetModal');
    var formId = modal.data('form-id');

    // Reset the form with the corresponding ID
    $('#' + formId)[0].reset(); // Reset the form using the dynamic form ID
    removeImage(); // Remove the image preview


    // Close the modal after resetting the form
    $('#resetModal').modal('hide');


});