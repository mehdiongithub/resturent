@extends('Layouts.auth');
@section('title')
    Create Inventory
@endsection
@section('style')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <!-- Include Bootstrap for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Include Bootstrap Icons for camera icon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        /* Styling for the rich text editor */

        .supplier_div .select2-container .select2-selection--single {
            height: 40px !important;
            padding-top: 5px
        }

        .supplier_div .select2-selection__arrow {
            top: 5px !important;
        }

        input {
            height: 40px;
        }


        #inventory_table tbody .select2-container .select2-selection--single {
            height: 35px !important;
            padding-top: 5px
        }

        #inventory_table tbody .select2-selection__arrow {
            top: 3px !important;
        }

        #inventory_table tbody input {
            height: 35px;
        }
    </style>
@endsection

@section('content')
    @include('reset-data.reset');
    @include('inventory.supplier_modal');

    <div class="container">
        <div class="page-inner">
            <div class="page-header d-flex justify-content-between align-items-center">
                <h3 class="fw-bold mb-3">Create inventory</h3>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form id="inventory-form" class="row g-3" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-4 supplier_div">
                            <label for="inputName4" class="form-label">Supplier</label>
                            <select class="form-control" id="supplier_id" name="supplier_id">
                                <option>Select Supplier</option>
                                <option value="add_new">Create Supplier</option>
                            </select>
                            <input type="hidden" value="{{ getUserCompanyId() }}" name="company_id" class="form-control"
                                id="inputCompanyId4">
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <label for="date" class="form-label">Invoice Date</label>
                            <input type="text" name="date" class="form-control" id="date">
                            <input type="hidden" value="{{ getUserStoreId() }}" name="store_id" class="form-control"
                                id="inputStoreId4">
                        </div>
                        <div class="table-responsive mt-4">
                            <table class="table" id="inventory_table">
                                <thead>
                                    <tr>
                                        <th style="width: 25%;">Product</th>
                                        <th style="width: 13%;">Qty</th>
                                        <th style="width: 13%;">Price</th>
                                        <th style="width: 13%;">Gross Value</th>
                                        <th style="width: 13%;">Discount Value</th>
                                        <th style="width: 13%;">Net Value</th>
                                        <th style="width: 10%;">
                                            <input type="button" class="text-light btn btn-primary btn-xs add_row"
                                                value="+">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select class="form-control  product_id" id="product_id" name="product_id[]">
                                                <option>Select Product</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input class="form-control" id="qty" name="qty[]">
                                        </td>
                                        <td>
                                            <input class="form-control" id="price" name="price[]">
                                        </td>
                                        <td>
                                            <input class="form-control" disabled id="gross_value" name="gross_value[]">
                                        </td>
                                        <td>
                                            <input class="form-control" id="discount" name="discount[]">
                                        </td>
                                        <td>
                                            <input class="form-control" id="total" name="total[]">
                                        </td>
                                        <td>
                                            <input type="button" class="text-light btn btn-danger btn-xs remove_row"
                                                value="-">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>




                        <div class="col-md-12 d-flex justify-content-end">
                            <input type="button" value="Back" class="btn btn-success btn-sm ms-2" id="back">
                            <button id="clear" type="button" class="btn btn-danger btn-sm ms-2" data-bs-toggle="modal"
                                data-bs-target="#resetModal" data-form-id="inventory-form">
                                Clear
                            </button>
                            <input type="submit" value="Submit" class="btn btn-primary  btn-sm ms-2" id="submit">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var selectedSupplier = 0;
        // Preview the selected image



        $('#back').click(function() {
            // Redirect to the inventories.index route
            window.location.href = '{{ route('inventories.index') }}';
        });

        $('#clear').prop('disabled', true);

        $('input').on('input', function() {
            // Check if all input fields are empty
            let isEmpty = true; // Assume all fields are empty initially

            $('input').each(function() {
                if ($(this).val() !== '') {
                    isEmpty = false; // If any field has a value, set isEmpty to false
                    return false; // Exit the loop early
                }
            });

            // Enable or disable the clear button based on whether all fields are empty
            if (isEmpty) {
                $('#clear').prop('disabled', true); // Disable the clear button if all fields are empty
            } else {
                $('#clear').prop('disabled', false); // Enable the clear button if any field has a value
            }
        });






        // Submit form via AJAX
        $('#inventory-form').submit(function(e) {
            e.preventDefault(); // Prevent the default form submission

            var formData = new FormData(this); // Create a FormData object

            $.ajax({
                url: '{{ route('inventories.store') }}', // URL to send the form data
                method: 'POST',
                data: formData,
                processData: false, // Required for file uploads
                contentType: false, // Required for file uploads
                success: function(response) {
                    // Clear the form after successful submission
                    setTimeout(function() {
                        toastr.success(response.message); // Display success toast
                        location.reload(); // This reloads the page
                    }, 1000);
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) {
                        // Validation error
                        var errors = xhr.responseJSON.errors; // Extract errors from response

                        // Loop through the errors and show each one in a Toastr error
                        $.each(errors, function(key, messages) {
                            messages.forEach(function(message) {
                                toastr.error(message); // Show error message
                            });
                        });
                    } else {
                        // Handle other errors
                        toastr.error('An unexpected error occurred. Please try again.');
                    }
                }
            });
        });



        $(document).ready(function() {
            var selectedSupplier = ''; // Initialize the selected supplier variable

            // Initialize Select2
            $('select').select2();

            // Function to fetch supplier data via AJAX
            function supplierData() {
                $.ajax({
                    url: '{{ route('supplierData') }}',
                    method: 'GET',
                    success: function(response) {
                        console.log(response); // Log the response to check its structure

                        if (Array.isArray(response.data)) {
                            $('#supplier_id').empty();
                            $('#supplier_id').append('<option value="">Select Supplier</option>');
                            $('#supplier_id').append(
                                '<option value="add_new">Add New Supplier</option>');

                            response.data.forEach(function(supplier) {
                                $('#supplier_id').append('<option value="' + supplier.id +
                                    '">' + supplier.name + '</option>');
                            });

                            $('#supplier_id').select2();
                        } else {
                            toastr.error('Suppliers data is invalid or missing.');
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Failed to fetch suppliers. Please try again.');
                    }
                });
            }

            // Call the function immediately after the page loads
            supplierData();

            $('#supplier_id').on('change', function() {
                var selectedValue = $(this).val();
                if (selectedValue === 'add_new') {
                    $('#supplierModal').modal('show');
                } else {
                    selectedSupplier = selectedValue;
                }
            });

            // Submit form via AJAX to add a new supplier
            $('#supplier-form').submit(function(e) {
                e.preventDefault(); // Prevent the default form submission

                var formData = new FormData(this); // Create a FormData object

                $.ajax({
                    url: '{{ route('suppliers.store') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        toastr.success("Supplier successfully added");
                        $('#supplierModal').modal('hide');
                        $('#supplier-form')[0].reset();

                        $('#supplier_id').append(
                            $('<option>', {
                                value: response.supplier.id,
                                text: response.supplier.name
                            })
                        );

                        $('#supplier_id').val(response.supplier.id);
                        $('#inputName4').val(response.supplier.name);
                        $('#inputPhone4').val(response.supplier.phone);

                        selectedSupplier = response.supplier.id;
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, messages) {
                                messages.forEach(function(message) {
                                    toastr.error(message);
                                });
                            });
                        } else {
                            toastr.error('An unexpected error occurred. Please try again.');
                        }
                    }
                });
            });

            // Close the supplier modal and reset selected supplier value
            $('#supplier_close_btn, #btn_close').on('click', function() {
                // Check if selectedSupplier is valid and set value accordingly
                if (selectedSupplier !== '' && selectedSupplier !== 0) {
                    $('#supplier_id').val(selectedSupplier);
                    $('#supplier_id').trigger('change');
                } else {
                    $('#supplier_id').val('');
                    $('#supplier_id').trigger('change');
                }

                // Get the Bootstrap Modal instance and hide it
                var supplierModal = new bootstrap.Modal(document.getElementById('supplierModal'));
                if (supplierModal) {
                    supplierModal.hide(); // This will hide the modal and manage the backdrop correctly
                }
            });


            // Show reset modal when clear button is clicked
            $('#clear').on('click', function(e) {
                e.preventDefault();
                var supplierModal = new bootstrap.Modal(document.getElementById('supplierModal'));
                var supplierResetModal = new bootstrap.Modal(document.getElementById('supplierResetModal'));

                // Show the reset modal and hide the supplier modal
                supplierResetModal.show();
                // supplierModal.hide(); // Hide the supplier modal if it's open
            });

            // Handle form reset on confirmation from reset modal
            $('#confirm-clear').on('click', function() {
    $('#supplier-form')[0].reset(); // Reset the form
    removeImage(); // Remove any image preview

    // Get the instances of both modals
    var supplierResetModal = bootstrap.Modal.getInstance(document.getElementById('supplierResetModal'));
    var supplierModal = bootstrap.Modal.getInstance(document.getElementById('supplierModal'));

    // Close the reset modal and show the main supplier modal
    if (supplierResetModal) {
        supplierResetModal.hide(); // Close the reset modal
    }

    // Ensure the backdrop of the reset modal is hidden
    document.body.classList.remove('modal-open'); // Manually remove the modal-open class
    document.querySelector('.modal-backdrop').classList.remove('show'); // Remove the backdrop element

    // Show the supplier modal
    if (supplierModal) {
        supplierModal.show(); // Open the supplier modal
    }
});

$('#supplierResetModalCloseBtn').on('click', function() {
    var supplierResetModal = bootstrap.Modal.getInstance(document.getElementById('supplierResetModal'));
    var supplierModal = bootstrap.Modal.getInstance(document.getElementById('supplierModal'));

    if (supplierResetModal) {
        supplierResetModal.hide(); // Correctly close the supplierResetModal
    }

    // Ensure the backdrop of the reset modal is hidden
    document.body.classList.remove('modal-open'); // Remove the modal-open class from body
    document.querySelector('.modal-backdrop').classList.remove('show'); // Remove the backdrop of the previous modal

    if (supplierModal) {
        supplierModal.show(); // Show the supplier modal
    }
});

        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tableBody = document.querySelector("#inventory_table tbody");

            // Add Row
            document.querySelector(".add_row").addEventListener("click", function() {
                const newRow = `
        <tr>
          <td>
            <select class="form-control" name="product_id[]">
              <option>Select Product</option>
            </select>
          </td>
          <td>
            <input class="form-control" name="qty[]">
          </td>
          <td>
            <input class="form-control" name="price[]">
          </td>
          <td>
            <input class="form-control" disabled name="gross_value[]">
          </td>
          <td>
            <input class="form-control" name="discount[]">
          </td>
          <td>
            <input class="form-control" name="total[]">
          </td>
          <td>
            <input type="button" class="text-light btn btn-danger btn-xs remove_row" value="-">
          </td>
        </tr>
      `;

                // Append new row to table body
                tableBody.insertAdjacentHTML("beforeend", newRow);
                $('select').select2();

            });

            // Remove Row
            tableBody.addEventListener("click", function(e) {
                if (e.target && e.target.classList.contains("remove_row")) {
                    const row = e.target.closest("tr");
                    if (row) row.remove();
                }
            });
        });
    </script>


    <script src="{{ asset('assets/auth/js/reset-data.js') }}"></script>
@endsection
