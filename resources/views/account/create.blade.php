@extends('Layouts.auth')

@section('title')
    Suppliers Account Details
@endsection

@section('style')
    <style>
        /* Default icon and text color */
        .action-icon i {
            color: black;
            font-size: 18px;
            /* Adjust the icon size as needed */
            margin-right: 5px;
            /* Space between icon and text */
            transition: color 0.3s ease;
            /* Smooth color transition */
        }

        /* Default text color */
        .action-icon {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Change color to blue on hover for both icon and text */
        .action-icon:hover i,
        .action-icon:hover {
            color: blue;
        }

        /* Optional: Button hover effect for delete */
        button.action-icon:hover i {
            color: blue;
        }

        .supplier-details {
            display: flex;
            justify-content: space-around;
            align-content: center;
            align-items: center;
            padding: 1.5vh 0;
            border-bottom: 1px solid black;
            width: 100%;
        }

        .table td,
        .table th {
            text-align: center;
        }
    </style>
@endsection

<script>
    var accountDetail = @json($data);
</script>
@include('delete-modal.delete-modal') <!-- Include Modal here -->

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header d-flex justify-content-between align-items-center">
                <div class="supplier-details">
                    <div class="supplier-img">
                        <img src="{{ asset('assets/auth/img/img_avatar2.png') }}" style="width: 120px; border-radius: 50%"
                            alt="profile">
                    </div>
                    <div class="supplier-name">
                        {{ $data->name }} <!-- Supplier's name from the database -->
                    </div>
                    <div class="supplier-phone">
                        {{ $data->phone }} <!-- Supplier's phone from the database -->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive mt-4 row">
                        <div class="col-md-6">
                            <h4>Account Transactions (Payments)</h4>
                            <table class="table" id="account_table">
                                <thead>
                                    <tr>
                                        <th style="width: 33%;">Date</th>
                                        <th style="width: 25%;">Paid Amount</th>
                                        <th style="width: 25%;">Remaining Balance</th>
                                        <th style="width: 17%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalPaidAmount = 0;
                                        $totalInvoiceAmount = $data->invoices->sum('total_price');
                                    @endphp
                                    @foreach ($data->supplier_account as $account)
                                        <tr>
                                            <td>{{ $account->bill_paid_date }}</td>
                                            <td>{{ number_format($account->paid_amount, 2) }}</td>
                                            @php
                                                $totalPaidAmount += $account->paid_amount;
                                                $remainingBalance = $totalInvoiceAmount - $totalPaidAmount;
                                            @endphp
                                            <td>{{ number_format($remainingBalance, 2) }}</td>
                                            <td>

                                                <!-- Edit Link with Icon and Text -->
                                                <a class="action-icon edit_btn" data-transaction-id="{{ $account->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <!-- Delete Form with Icon and Text -->
                                                <form id="deleteForm{{ $account->id }}"
                                                    action="{{ route('accounts.destroy', $account->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal"
                                                        data-bs-target="#exampleModal" data-title="Account Transction"
                                                        data-form-id="deleteForm{{ $account->id }}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-1"></div>

                        <div class="col-md-5">
                            <h4>Invoice Transactions (Purchases)</h4>
                            <table class="table" id="account_table">
                                <thead>
                                    <tr>
                                        <th style="width: 33%;">Invoice Date</th>
                                        <th style="width: 33%;">Total Purchase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data->invoices as $invoice)
                                        <tr>
                                            <td>{{ $invoice->invoice_date }}</td>
                                            <td>{{ number_format($invoice->total_price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                        <div class="col-md-12 mt-4 d-flex justify-content-end">
                            <input type="button" value="Back" class="btn btn-success btn-sm ms-2" id="back">

                            <input type="button" value="Add Payment" class="btn btn-primary  btn-sm ms-2"
                                id="add_trancstion">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Modal of transction --}}

    <div class="modal fade" id="addTransctionModal" tabindex="-1" aria-labelledby="addTransctionModalLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addTransctionModalLabel">{{ $data->name }} Payment </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="add-tranction-form">
                    @csrf
                    <div class="modal-body">
                        <div class="col-md-12 mt-4">
                            <label for="date" class="form-label">Transaction Date</label>
                            <input type="text" name="bill_paid_date" class="form-control" id="bill_paid_date">
                            <input type="hidden" value="{{ getUserStoreId() }}" name="store_id" class="form-control"
                                id="inputStoreId4">
                            <input type="hidden" value="{{ getUserCompanyId() }}" name="company_id"
                                class="form-control" id="inputCompanyId4">
                            <input type="hidden" value="{{ $data->id }}" name="supplier_id" class="form-control"
                                id="supplier_id">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mt-4">
                                <label for="date" class="form-label">Paid Amount</label>
                                <input type="text" name="paid_amount" class="form-control" id="paid_amount">

                            </div>
                            <div class="col-md-6 mt-4">
                                <label for="date" class="form-label">Remaining Amount</label>
                                <input type="hidden" value="{{ $remainingBalance }}" name="account_balance"
                                    class="form-control" id="account_balance">
                                <p id="account_balance_para">{{ number_format($remainingBalance, 2) }}</p>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="addTransctionModalCloseBtn"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirm-clear">Clear</button>
                        <input type="submit" value="Submit" class="btn btn-primary" id="submit">

                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Modal of Transction --}}

    <div class="modal fade" id="editTransctionModal" tabindex="-1" aria-labelledby="editTransctionModalLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editTransctionModalLabel">Edit Payment</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit-tranction-form">
                    @csrf
                    <div class="modal-body">
                        <div class="col-md-12 mt-4">
                            <label for="edit_bill_paid_date" class="form-label">Transaction Date</label>
                            <input type="text" name="bill_paid_date" class="form-control" id="edit_bill_paid_date">
                            <input type="hidden" name="transaction_id" id="transaction_id">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mt-4">
                                <label for="edit_paid_amount" class="form-label">Paid Amount</label>
                                <input type="text" name="paid_amount" class="form-control" id="edit_paid_amount">

                                <input type="hidden" value="{{ getUserStoreId() }}" name="store_id" class="form-control"
                                id="edit_inputStoreId4">
                            <input type="hidden" value="{{ getUserCompanyId() }}" name="company_id"
                                class="form-control" id="edit_inputCompanyId4">
                            <input type="hidden" value="{{ $data->id }}" name="supplier_id" class="form-control"
                                id="edit_supplier_id">

                            </div>
                            <div class="col-md-6 mt-4">
                                <label for="edit_account_balance" class="form-label">Remaining Amount</label>
                                <input type="hidden" value="{{ $remainingBalance }}" name="account_balance"
                                class="form-control" id="edit_account_balance">
                                <p id="edit_account_balance_para"></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <input type="submit" id="update" value="Update" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>


    
@endsection

@section('script')
    <script>

        let edit_id = '';
        let edit_paid_amount = 0;
        document.getElementById("add_trancstion").addEventListener("click", function() {
            const resetModal = new bootstrap.Modal(document.getElementById("addTransctionModal"));
            resetModal.show();
            // disableSupplierModalInputs();
        });





        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#bill_paid_date", {
                // Set default date to today
                defaultDate: new Date(),
                // Enable calendar with date picking
                dateFormat: "Y-m-d", // Format as YYYY-MM-DD
                onReady: function(selectedDates, dateStr, instance) {
                    instance.input.value = dateStr; // Ensure input has the default value
                }
            });
            flatpickr("#edit_bill_paid_date", {
                // Enable calendar with date picking
                dateFormat: "Y-m-d", // Format as YYYY-MM-DD
                onReady: function(selectedDates, dateStr, instance) {
                    instance.input.value = dateStr; // Ensure input has the default value
                }
            });

        });
        $(document).ready(function() {
            $('#paid_amount').on('input', function() {
                // Parse the values as numbers
                let total_amount = parseFloat($("#account_balance").val()) || 0;
                let amount_to_pay = parseFloat($(this).val()) || 0;
                console.log('total_amount', total_amount);

                if (amount_to_pay > total_amount) {
                    // If input exceeds total_amount, slice it to fit within range
                    toastr.error("paid Amount cannot exceed total remaining amount!");
                    let sliced_value = $(this).val().slice(0, String(total_amount).length -
                    1); // Limit length
                    $(this).val(sliced_value); // Set the sliced value
                    amount_to_pay = parseFloat(sliced_value) || 0; // Update the variable
                }

                // Calculate the remaining amount
                let remaining_amount = total_amount - amount_to_pay;

                // Update the remaining amount in the paragraph and hidden input
                $("#account_balance_para").text(remaining_amount.toFixed(2));
            });

            $('#add-tranction-form').submit(function(e) {
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
                    url: '{{ route('accounts.store') }}',
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

                        toastr.error('An unexpected error occurred. Please try again.');

                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Attach click event listeners to all edit buttons
            document.querySelectorAll('.edit_btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    const transactionId = this.getAttribute(
                    'data-transaction-id'); // Replace with the appropriate attribute
                    let total_amount = parseFloat($("#account_balance").val()) || 0;


                    // Make AJAX call to fetch transaction details
                    $.ajax({
                        url: '{{ route('accounts.edit', ':id') }}'.replace(':id',
                            transactionId),
                        method: 'GET',
                        success: function(response) {
                            // Populate modal fields with the response data
                            document.getElementById('transaction_id').value = response
                                .data.id;
                            document.getElementById('edit_bill_paid_date').value =
                                response.data.bill_paid_date || '';
                            document.getElementById('edit_paid_amount').value = response
                                .data.paid_amount || '';
                            document.getElementById('edit_account_balance_para')
                                .innerText =
                                parseFloat(total_amount || 0).toFixed(2);

                            // Show the edit modal
                            const editModal = new bootstrap.Modal(document
                                .getElementById('editTransctionModal'));
                            editModal.show();

                            edit_id = response.data.id;
                            edit_paid_amount = response.data.paid_amount;
                        },
                        error: function(xhr) {
                            // Handle errors (e.g., invalid ID or server error)
                            toastr.error(
                                'Unable to fetch transaction details. Please try again.'
                                );
                        }
                    });
                });
            });

            // Initialize Flatpickr for the edit modal date input
            flatpickr("#edit_bill_paid_date", {
                dateFormat: "Y-m-d",
            });
        });


        $(document).ready(function() {
            $('#edit_paid_amount').on('input', function() {
                // Parse the values as numbers
                let amount_to_pay = parseFloat($(this).val()) || 0;
                let total_amount = parseFloat($("#edit_account_balance").val()) + parseFloat(edit_paid_amount) || 0;
                
                if (amount_to_pay > total_amount) {
                    // If input exceeds total_amount, slice it to fit within range
                    toastr.error("paid Amount cannot exceed total remaining amount!");
                    let sliced_value = $(this).val().slice(0, String(total_amount).length -
                    1); // Limit length
                    $(this).val(sliced_value); // Set the sliced value
                    amount_to_pay = parseFloat(sliced_value) || 0; // Update the variable
                }

                // Calculate the remaining amount
                let remaining_amount = total_amount - amount_to_pay;

                // Update the remaining amount in the paragraph and hidden input
                $("#edit_account_balance_para").text(remaining_amount.toFixed(2));
            });

            $('#edit-tranction-form').submit(function(e) {
                e.preventDefault(); // Prevent default form submission

                var $form = $(this);
                var $submitButton = $('#update');

                // Prevent multiple submissions
                if ($submitButton.hasClass('is-loading')) {
                    return false;
                }

                // Add loading class and disable button
                $submitButton.addClass('is-loading');
                $submitButton.prop('disabled', true);
                
                var formData = new FormData(this);

                $.ajax({
                    url: '{{ route('update-account', ':id') }}'.replace(':id', edit_id),
                   

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

                        toastr.error('An unexpected error occurred. Please try again.');

                    }
                });
            });

            $('#back').click(function() {
                // Redirect to the products.index route
                window.location.href = '{{ route('accounts.index') }}';
            });
        });

        var IndexUrl = window.location.href;
;

    </script>
@endsection
