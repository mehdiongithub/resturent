@extends('Layouts.auth')

@section('title')
Suppliers Account Details
@endsection

@section('style')
<style>
/* Default icon and text color */
.action-icon i {
    color: black;
    font-size: 18px; /* Adjust the icon size as needed */
    margin-right: 5px; /* Space between icon and text */
    transition: color 0.3s ease; /* Smooth color transition */
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

.supplier-info-details-header {
    display: flex;
    justify-content: space-between;
    align-content: center;
    align-items: center;
    margin-top: 1.5vh;
    padding-bottom: 1.5vh;  /* Add space between the content and the bottom border */
    padding-top: 1.5vh;  /* Add space between the content and the bottom border */
    border-top: 1px solid black;
    border-bottom: 1px solid black;
}

.supplier-details-header{
    width: 50%;
    display: flex;
    justify-content: space-around;
    align-content: center;
    text-align: center;
    align-items: center;
}
.supplier-account-detail-header{
    width: 50%;
    display: flex;
    justify-content: space-around;
    align-content: center;
    text-align: center;
    align-items: center;
}

.supplier-info-details {
    display: flex;
    justify-content: space-between;
    align-content: center;
    align-items: center;
    margin-top: 1.5vh;
    padding-bottom: 1.5vh;  /* Add space between the content and the bottom border */
    border-bottom: 1px solid black;
}

.supplier-details{
    width: 50%;
    display: flex;
    justify-content: space-around;
    align-content: center;
    text-align: center;
    align-items: center;
}
.supplier-account-detail{
    width: 50%;
    display: flex;
    justify-content: space-around;
    align-content: center;
    text-align: center;
    align-items: center;
}


</style>
@endsection

@section('content')
<script>
  var account = @json($data);
</script>
<div class="container">
    <div class="page-inner">
        <div class="page-header d-flex justify-content-between align-items-center">
            <h3 class="fw-bold mb-3">Supplier Account Details</h3>
            {{-- <a href="{{route('suppliers.create')}}" class="btn btn-primary">Add Supplier</a> --}}
        </div>      
        <div class="row">
            <div class="col-md-12">
              <table class="table table-bordered table-striped" id="basic-datatables">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Supplier Name</th>
                        <th>Contact No</th>
                        <th>Last Transaction</th>
                        <th>Paid Amount</th>
                        <th>Remaining Balance</th>
                        <th>Total Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $supplierDetail)
                    <tr>
                        <td>
                            <!-- Make image clickable -->
                            <a href="{{ route('account.create', ['supplierId' => $supplierDetail->id]) }}">
                                <img src="{{ asset('assets/auth/img/img_avatar2.png') }}" style="width: 120px; border-radius: 50%" alt="profile">
                            </a>
                        </td>
                        <td>
                            <!-- Make supplier name clickable -->
                            <a href="{{ route('account.create', ['supplierId' => $supplierDetail->id]) }}">
                                {{ $supplierDetail->name }}
                            </a>
                        </td>
                        <td>{{ $supplierDetail->phone }}</td>
                        <td>
                            @if($supplierDetail->supplier_account && $supplierDetail->supplier_account->isNotEmpty() && $supplierDetail->supplier_account->first())
                                {{ $supplierDetail->supplier_account->first()->latest_bill_paid_date }}
                            @else
                                --
                            @endif
                        </td>
                        <td>
                            @if($supplierDetail->supplier_account && $supplierDetail->supplier_account->isNotEmpty() && $supplierDetail->supplier_account->first())
                                {{ $supplierDetail->supplier_account->first()->sum_paid_amount }}
                            @else
                                0.00
                            @endif
                        </td>
                        <td>
                            @php
                                $totalPriceSum = $supplierDetail->invoices->isNotEmpty() 
                                                 ? $supplierDetail->invoices->first()->total_price_sum 
                                                 : 0;
            
                                $sumPaidAmount = $supplierDetail->supplier_account && $supplierDetail->supplier_account->isNotEmpty() && $supplierDetail->supplier_account->first()
                                                 ? $supplierDetail->supplier_account->first()->sum_paid_amount 
                                                 : 0;
            
                                $remainingBalance = $totalPriceSum - $sumPaidAmount;
                            @endphp
            
                            {{ number_format($remainingBalance, 2) }}
                        </td>
                        <td>
                            @if($supplierDetail->invoices->isNotEmpty())
                                {{ $supplierDetail->invoices->first()->total_price_sum }}
                            @else
                                0.00
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            </div>
        </div>
    </div>
</div>
@endsection


@section('script')

<script>
    $(document).ready(function () {

      console.log('account details',account);

      $("#basic-datatables").DataTable({
        "columnDefs": [
          {
            "targets": 0, // Disable sorting for the 'Action' column (index 4)
            "orderable": false
          }
        ]
      });

      $("#multi-filter-select").DataTable({
        pageLength: 5,
        initComplete: function () {
          this.api()
            .columns()
            .every(function () {
              var column = this;
              var select = $(
                '<select class="form-select"><option value=""></option></select>'
              )
                .appendTo($(column.footer()).empty())
                .on("change", function () {
                  var val = $.fn.dataTable.util.escapeRegex($(this).val());

                  column
                    .search(val ? "^" + val + "$" : "", true, false)
                    .draw();
                });

              column
                .data()
                .unique()
                .sort()
                .each(function (d, j) {
                  select.append(
                    '<option value="' + d + '">' + d + "</option>"
                  );
                });
            });
        },
      });

      // Add Row
      $("#add-row").DataTable({
        pageLength: 5,
      });

      var action =
        '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

      $("#addRowButton").click(function () {
        $("#add-row")
          .dataTable()
          .fnAddData([
            $("#addName").val(),
            $("#addPosition").val(),
            $("#addOffice").val(),
            action,
          ]);
        $("#addRowModal").modal("hide");
      });



    });

    var IndexUrl = "{{ route('suppliers.index') }}";


  </script>

@endsection
