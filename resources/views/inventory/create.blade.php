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

        .toast {
            z-index: 1056 !important;
            /* Higher than Bootstrap modals (z-index: 1055) */
            opacity: 1 !important;
            /* Ensure the toast is visible */
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
        $(document).ready(function() {
            let allProducts = []; // Store all products fetched from the API
            let selectedProducts = []; // Store selected products for each row

            // Function to fetch product data via AJAX
            function productData() {
                $.ajax({
                    url: '{{ route('productData') }}',
                    method: 'GET',
                    success: function(response) {
                        if (Array.isArray(response.data)) {
                            allProducts = response.data; // Save fetched products
                            updateProductDropdowns(); // Update all existing dropdowns
                        } else {
                            toastr.error('Product data is invalid or missing.');
                        }
                    },
                    error: function() {
                        toastr.error('Failed to fetch product data. Please try again.');
                    }
                });
            }

            // Function to update the dropdown options in all rows
            function updateProductDropdowns() {
                const rows = $("#inventory_table tbody tr");
                rows.each(function() {
                    const currentDropdown = $(this).find('select[name="product_id[]"]');
                    const selectedValue = currentDropdown.val(); // Preserve current selection
                    populateDropdown(currentDropdown, selectedValue);
                });
            }

            // Function to populate a dropdown with products, excluding selected products
            function populateDropdown(dropdown, selectedValue) {

                allProducts.forEach(product => {
                    if (!selectedProducts.includes(product.id) || product.id === selectedValue) {
                        dropdown.append(`<option value="${product.id}">${product.name}</option>`);
                    }
                });

                dropdown.val(selectedValue); // Restore the previous selection if available
            }

            // Handle product selection changes
            $('#inventory_table').on('change', 'select[name="product_id[]"]', function() {
                const selectedValue = $(this).val();

                // Update selected products list
                selectedProducts = [];
                $('#inventory_table select[name="product_id[]"]').each(function() {
                    const value = $(this).val();
                    if (value) selectedProducts.push(value);
                });

                updateProductDropdowns(); // Refresh dropdowns
            });

            // Add a new row
            $(".add_row").on("click", function() {
                const newRow = `
            <tr>
                <td>
                    <select class="form-control product_id" name="product_id[]">
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
                $("#inventory_table tbody").append(newRow);

                const newDropdown = $("#inventory_table tbody tr:last select[name='product_id[]']");
                populateDropdown(newDropdown, null); // Populate the new dropdown
                newDropdown.select2(); // Reinitialize Select2 for the new dropdown
            });

            // Remove a row
            $("#inventory_table tbody").on("click", ".remove_row", function() {
                const totalRows = $("#inventory_table tbody tr").length; // Count rows

                if (totalRows === 1) {
                    // Display an alert if trying to delete the last row
                    toastr.error("Cannot delete the last row.");
                    return; // Exit without deleting
                }

                const row = $(this).closest("tr");
                const removedValue = row.find('select[name="product_id[]"]').val();
                row.remove();

                if (removedValue) {
                    // Remove the deselected product from the selected list
                    selectedProducts = selectedProducts.filter(id => id !== removedValue);
                    updateProductDropdowns(); // Refresh dropdowns
                }
            });


            // Fetch supplier data (already included in your code)
            function supplierData() {
                $.ajax({
                    url: '{{ route('supplierData') }}',
                    method: 'GET',
                    success: function(response) {
                        if (Array.isArray(response.data)) {
                            $('#supplier_id').empty();
                            $('#supplier_id').append('<option value="">Select Supplier</option>');
                            $('#supplier_id').append(
                                '<option value="add_new">Add New Supplier</option>');

                            response.data.forEach(function(supplier) {
                                $('#supplier_id').append(
                                    `<option value="${supplier.id}">${supplier.name}</option>`
                                    );
                            });

                            $('#supplier_id').select2();
                        } else {
                            toastr.error('Suppliers data is invalid or missing.');
                        }
                    },
                    error: function() {
                        toastr.error('Failed to fetch suppliers. Please try again.');
                    }
                });
            }

            // Handle supplier modal display
            $('#supplier_id').on('change', function() {
                const selectedValue = $(this).val();
                if (selectedValue === 'add_new') {
                    $('#supplierModal').modal('show');
                }
            });

            // Initialize the page
            supplierData(); // Fetch supplier data
            productData(); // Fetch product data

            $('select').select2();

        });
    </script>


    <script src="{{ asset('assets/auth/js/reset-data.js') }}"></script>
@endsection
