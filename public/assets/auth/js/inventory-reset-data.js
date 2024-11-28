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

    var firstRow = $('#inventory_table tbody tr:first');
    $('#inventory_table tbody tr').not(firstRow).remove(); // Remove all rows except the first one

    // Reset all input fields and select options in the first row
    firstRow.find('input, select').each(function() {
        if ($(this).is('select')) {
            $(this).val('').trigger('change'); // Reset the select field and trigger change for select2
        } else {
            $(this).val(''); // Reset the input fields
        }
    });

    // Reinitialize the Select2 for the remaining row's select inputs
    firstRow.find('select').each(function() {
        $(this).select2();
    });

    $('#supplier_id').val('');
    $('#supplier_id').val('').trigger('change'); // Reset the select field and trigger change for select2
    
    // Reset the form with the corresponding ID
    $('#' + formId)[0].reset(); // Reset the form using the dynamic form ID
    $('#inputPhone4').val('');
    $('#inputName4').val('');
    selectedSupplier = '';
    removeImage(); // Remove the image preview

    // Set the current date in the date input before closing the modal
    $('#date').val(new Date().toISOString().split('T')[0]); // Set current date in YYYY-MM-DD format

    // Close the modal after resetting the form
    $('#resetModal').modal('hide');
});
