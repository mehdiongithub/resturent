@extends('Layouts.auth');
@section('title')
    Create Inventory
@endsection
@section('style')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    {{-- <!-- Include Bootstrap for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Include Bootstrap Icons for camera icon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet"> --}}

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

        form input {
            height: 40px;
        }

        .page-inner p {
            font-size: 10px !important;
        }


        #inventory_table tbody .select2-container .select2-selection--single {
            height: 30px !important;
            padding-top: 2px
        }

        #inventory_table tbody .select2-selection__arrow {
            top: 3px !important;
        }

        #inventory_table tbody input {
            height: 35px;
        }

        form input {
            font-size: 10px !important;
            font-weight: 400 !important;
        }

        .page-inner .fw-bold {
            font-size: 10px !important;
            font-weight: 400 !important;
        }

        .page-inner table thead th,
        .page-inner table tbody td,
        .page-inner table tfoot th,
        .page-inner table tfoot td {
            font-size: 10px !important;
            font-weight: 400 !important;
        }
    </style>
@endsection

@section('content')
    @include('inventory.inventory_reset_modal');
    @include('inventory.supplier_modal');

    <div class="container">
        <div class="page-inner">
            <div class="page-header d-flex justify-content-between align-items-center">
                <h3 class="fw-bold mb-3" style=" font-size: 20px !important;">Create inventory</h3>
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
                                        <th style="width: 18%;">Product</th>
                                        <th style="width: 10%;">Qty</th>
                                        <th style="width: 14%;">Weight</th>
                                        <th style="width: 10%;">Price</th>
                                        <th style="width: 10%;">expiry Date</th>
                                        <th style="width: 10%;">Gross Value</th>
                                        <th style="width: 10%;">Discount Value</th>
                                        <th style="width: 10%;">Net Value</th>
                                        <th style="width: 8%;">
                                            <input type="button" class="text-light btn btn-primary btn-xs add_row"
                                                value="+">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select class="form-control  product_id" id="product_id" name="product_id[]">
                                                {{-- <option>Select Product</option> --}}
                                            </select>
                                        </td>
                                        <td>
                                            <input class="form-control qty" id="qty" name="qty[]">
                                        </td>
                                        <td>
                                            <select class="form-control weight" id="weight" name="weight[]">
                                                <option value="">Select Weight</option>
                                                <option value="kg">kg</option>
                                                <option value="g">g</option>
                                                <option value="liter">liter</option>
                                                <option value="ml">ml</option>
                                                <option value="dozen">dozen</option>
                                                <option value="unit">unit</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control price" id="price" name="price[]">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control expiry" id="expiry"
                                                name="expiry_date[]">
                                        </td>
                                        <td>
                                            <input class="form-control gross_value" disabled id="gross_value"
                                                name="gross_value[]">
                                        </td>
                                        <td>
                                            <input class="form-control discount" id="discount" name="discount[]">
                                        </td>
                                        <td>
                                            <input class="form-control total" id="total" name="total[]">
                                        </td>
                                        <td>
                                            <input type="button" class="text-light btn btn-danger btn-xs remove_row"
                                                value="-">
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>
                                        </th>
                                        <th>
                                            Total Qty
                                        </th>
                                        <th colspan="3" style="text-align: center"></th>
                                        <th colspan="1" style="text-align: center">Total Value</th>
                                        <th colspan="1" style="text-align: center">Total discount</th>
                                        <th style="text-align: center">Total Amount</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="1"></th>
                                        <th colspan="1">
                                            <p id="para_total_qty">0</p>
                                            <input hidden type="number" name="total_qty" placeholder="0"
                                                class="form-control total_qty_cls" id="footer_total_qty" readonly>
                                        </th>
                                        <th colspan="3"></th>
                                        <th style="text-align: right">
                                            <p id="para_gross_value">0</p>
                                            <input hidden type="number" name="gross_value" placeholder="0"
                                                class="form-control purchase_gross_value_cls"
                                                id="footer_purchase_gross_value" readonly>
                                        </th>
                                        <th colspan="1" style="text-align: center">
                                            <p id="para_total_discount">0</p>
                                            <input hidden type="number" name="total_discount" placeholder="0"
                                                class="form-control total_discount_cls" id="footer_discount" readonly>
                                        </th>
                                        <th style="text-align: right">
                                            <p id="para_total_price">0</p>
                                            <input hidden type="number" name="total_net_value" placeholder="0"
                                                class="form-control total_net_value_cls" id="total_net_value" readonly>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td colspan="6"></td>
                                        <td colspan="1">Paid Amount</td>
                                        <td colspan="1">
                                            <input type="text" class="form-control" id="amount_to_pay"
                                                name="amount_to_pay">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6"></td>
                                        <td colspan="1">Remaining Amount</td>
                                        <td colspan="1">
                                            <input type="hidden" class="form-control" id="remaining_amount"
                                                name="remaining_amount">
                                            <p id="remaining_amount_para">0.00</p>
                                        </td>
                                    </tr>
                                </tfoot>

                            </table>
                        </div>




                        <div class="col-md-12 d-flex justify-content-end">
                            <input type="button" value="Back" class="btn btn-success btn-sm ms-2" id="back">
                            <button id="clear" type="button" class="btn btn-danger btn-sm ms-2"
                                data-bs-toggle="modal" data-bs-target="#resetModal" data-form-id="inventory-form">
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
        let selectedSupplierVal = '';
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
                            console.log('response.data', response.data);

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
            function populateDropdown(dropdown, selectedValue = "") {
                dropdown.empty(); // Clear any existing options

                // Add the "Select Product" option as the first option
                dropdown.append('<option value="">Select Product</option>');

                // Populate with available products
                allProducts.forEach(product => {
                    // Include only products not selected elsewhere or the currently selected product
                    if (!selectedProducts.includes(product.id) || product.id === selectedValue) {
                        dropdown.append(`<option value="${product.id}">${product.name}</option>`);
                    }
                });

                // Set the selected value (if provided), or keep "Select Product" as default
                if (selectedValue) {
                    dropdown.val(selectedValue);
                } else {
                    dropdown.val(""); // Reset to "Select Product"
                }
            }

            $('#back').click(function() {
                // Redirect to the products.index route
                window.location.href = '{{ route('inventories.index') }}';
            });



            // Handle product selection changes
            // Handle product selection changes
            $('#inventory_table').on('change', 'select[name="product_id[]"]', function() {
                const row = $(this).closest('tr');
                const priceInput = row.find('.price');

                const selectedValue = $(this).val();
                if (selectedValue) {
                    $.ajax({
                        url: '{{ url('getSpecific') }}/' +
                            selectedValue, // Dynamic URL to fetch product data
                        method: 'GET',
                        success: function(response) {
                            if (response.data) {
                                console.log('Selected Product:', response.data);

                                if (priceInput.length >= 0) {
                                    console.log('Setting price value:', response.data.price);
                                    priceInput.val(parseFloat(response.data.price) ||
                                        0); // Set the price input value
                                } else {
                                    console.log('Price input not found!');
                                }
                            } else {
                                toastr.error('No product data found for the selected ID.');
                            }
                        },
                        error: function() {
                            toastr.error('Failed to fetch product data. Please try again.');
                        }
                    });
                }

                // Update selected products list to prevent duplicate selections
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
                        </select>
                    </td>
                    <td>
                        <input class="form-control qty" name="qty[]">
                    </td>
                    <td>
                        <select class="form-control weight" name="weight[]">
                            <option value="">Select Weight</option>
                            <option value="kg">kg</option>
                            <option value="g">g</option>
                            <option value="liter">liter</option>
                            <option value="ml">ml</option>
                            <option value="dozen">dozen</option>
                            <option value="unit">unit</option>
                        </select>      
                    </td>
                    <td>
                        <input type="text" class="form-control price" name="price[]">
                    </td>
                     <td>
                        <input type="date" class="form-control expiry" name="expiry[]">
                     </td>
                                       
                    <td>
                        <input class="form-control gross_value" disabled name="gross_value[]">
                    </td>
                    <td>
                        <input class="form-control discount" name="discount[]">
                    </td>
                    <td>
                        <input class="form-control total" name="total[]">
                    </td>
                    <td>
                        <input type="button" class="text-light btn btn-danger btn-xs remove_row" value="-">
                    </td>
                </tr>
                `;
                $("#inventory_table tbody").append(newRow);

                const newDropdown = $("#inventory_table tbody tr:last select[name='product_id[]']");
                const newWeight = $("#inventory_table tbody tr:last select[name='weight[]']");
                populateDropdown(newDropdown,
                    null); // Populate the new dropdown with "Select Product" and options
                newDropdown.select2(); // Reinitialize Select2 for the new dropdown
                newWeight.select2(); // Reinitialize Select2 for the new dropdown

                flatpickr(".expiry", {
                    dateFormat: "Y-m-d", // Format the date (optional)
                    minDate: "today", // Optionally, prevent selecting past dates
                    locale: "en", // Set locale for the calendar
                });
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

            $('body').on('input', '.qty, .price, .discount', function() {
                let row = $(this).closest('tr');
                updateRowValues(row); // Call the calculation function for this row
            });

            $('body').on('change', '.weight', function() {
                let row = $(this).closest('tr');
                updateRowValues(row); // Call the calculation function when weight is changed

            });

            function updateRowValues(row) {
                let qty = row.find('.qty').val();
                let price = row.find('.price').val();
                let weight = row.find('.weight').val();
                let discount = row.find('.discount').val();
                let grossValue = 0;
                let netValue = 0;
                let discountValue = 0;

                if (weight == 'g' || weight == 'ml') {
                    grossValue = qty * price / 1000;
                    discountValue = discount;
                    netValue = grossValue - discountValue;
                } else if (weight == 'unit') {
                    grossValue = qty * price / 12;
                    discountValue = discount;
                    netValue = grossValue - discountValue;
                } else {
                    grossValue = qty * price;
                    discountValue = discount;
                    netValue = grossValue - discountValue;
                }

                row.find('.gross_value').val(grossValue.toFixed(2));
                row.find('.total').val(netValue.toFixed(2));

                updateFooterValues(); // Update footer totals after calculation
            }

            function updateFooterValues() {
                let totalQty = 0;
                let totalGrossValue = 0;
                let totalDiscount = 0;
                let totalNetValue = 0;

                $('#inventory_table tbody tr').each(function(index) {
                    const qty = parseFloat($(this).find('.qty').val()) || 0;
                    const gross_value = parseFloat($(this).find('.gross_value').val()) || 0;
                    const total = parseFloat($(this).find('.total').val()) || 0;
                    const discount = parseFloat($(this).find('.discount').val()) || 0;

                    if (qty > 0) {
                        totalQty++; // Increment the count of rows with non-zero qty
                    }
                    totalGrossValue += gross_value;
                    totalDiscount += discount;
                    totalNetValue += total;
                });

                $('#footer_total_qty').val(totalQty);
                $('#para_total_qty').text(totalQty);
                $('#footer_purchase_gross_value').val(totalGrossValue);
                $('#para_gross_value').text(totalGrossValue);
                $('#footer_discount').val(totalDiscount);
                $('#para_total_discount').text(totalDiscount);
                $('#total_net_value').val(totalNetValue);
                $('#para_total_price').text(totalNetValue);
            }


            $('#inventory-form').submit(function(e) {
                e.preventDefault(); // Prevent default form submission

                var $form = $(this);
                var $submitButton = $('#submit');

                // Prevent multiple submissions
                if ($submitButton.hasClass('is-loading')) {
                    return false;
                }

                // Add loading class and disable button
                $submitButton.addClass('is-loading');
                $submitButton.prop('disabled', true);

                var formData = new FormData(this);

                $.ajax({
                    url: '{{ route('inventories.store') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.addEventListener("abort", function() {
                            $submitButton.removeClass('is-loading');
                            $submitButton.prop('disabled', false);
                        }, false);
                        return xhr;
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Remove loading state on error
                        $submitButton.removeClass('is-loading');
                        $submitButton.prop('disabled', false);

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

            $('#amount_to_pay').on('input', function() {
                // Parse the values as numbers
                let total_amount = parseFloat($("#total_net_value").val()) || 0;
                let amount_to_pay = parseFloat($(this).val()) || 0;

                if (amount_to_pay > total_amount) {
                    // If input exceeds total_amount, slice it to fit within range
                    toastr.error("Amount to pay cannot exceed total amount!");
                    let sliced_value = $(this).val().slice(0, String(total_amount).length - 1); // Limit length
                    $(this).val(sliced_value); // Set the sliced value
                    amount_to_pay = parseFloat(sliced_value) || 0; // Update the variable
                }

                // Calculate the remaining amount
                let remaining_amount = total_amount - amount_to_pay;

                // Update the remaining amount in the paragraph and hidden input
                $("#remaining_amount_para").text(remaining_amount.toFixed(2));
                $("#remaining_amount").val(remaining_amount);
            });




        });

        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#date", {
                // Set default date to today
                defaultDate: new Date(),
                // Enable calendar with date picking
                dateFormat: "Y-m-d", // Format as YYYY-MM-DD
                onReady: function(selectedDates, dateStr, instance) {
                    instance.input.value = dateStr; // Ensure input has the default value
                }
            });

            flatpickr(".expiry", {
                dateFormat: "Y-m-d", // Format the date (optional)
                minDate: "today", // Optionally, prevent selecting past dates
                locale: "en", // Set locale for the calendar
            });



        });


        document.addEventListener("input", function(event) {
            // List of allowed selectors
            const allowedSelectors = [".price", ".qty", ".discount", ".total", "#amount_to_pay"];

            // Check if the event target matches any of the selectors
            if (allowedSelectors.some(selector => event.target.matches(selector))) {
                // Remove any non-numeric characters
                event.target.value = event.target.value.replace(/[^0-9]/g, '');
            }
        });
    </script>


    <script src="{{ asset('assets/auth/js/inventory-reset-data.js') }}"></script>
@endsection
