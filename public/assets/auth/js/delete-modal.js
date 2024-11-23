// Listen for when the modal is shown
$('#exampleModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var title = button.data('title'); // Extract title from data-title attribute

    // Capitalize the first letter of the title
    title = title.charAt(0).toUpperCase() + title.slice(1);
    var formId = button.data('form-id'); // Get the form ID to trigger on confirmation

    // Update the modal title with dynamic title
    var modal = $(this);
    modal.find('.modal-title').text('Delete ' + title);

    // When the confirm button is clicked, submit the form and handle the response
    $('#confirmDeleteBtn').off('click').on('click', function() {
        // Send the form via AJAX
        var form = $('#' + formId);
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(), // Serialize the form data (including CSRF token)
            success: function(response) {
                // If deletion is successful, show success toast and redirect
                if (response.status === 'success') {
                    // Close the modal after successful deletion
                    $('#exampleModal').modal('hide');

                    // Show the success toast
                    toastr.success(response.message);

                    // Redirect after the toast is shown for 3 seconds
                    setTimeout(function() {
                        window.location.href = IndexUrl;  // Use the variable with the correct URL
                    }, 1500); // 3 seconds delay
                } else {
                    toastr.error(response.message); // Show error message if deletion failed
                }
            },
            error: function() {
                // Handle error if needed (optional)
                toastr.error('Error occurred while deleting the product.');
            }
        });
    });
});