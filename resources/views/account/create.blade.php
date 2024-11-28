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

.supplier-details {
    display: flex;
    justify-content: space-around;
    align-content: center;
    align-items: center;
    padding: 1.5vh 0;
    border-bottom: 1px solid black;
    width: 100%;
}

.table td, .table th {
    text-align: center;
}

</style>
@endsection

<script>
  var accountDetail = @json($data);
</script>

@section('content')
<div class="container">
  <div class="page-inner">
      <div class="page-header d-flex justify-content-between align-items-center">
            <div class="supplier-details">
              <div class="supplier-img">
                <img src="{{ asset('assets/auth/img/img_avatar2.png') }}" style="width: 120px; border-radius: 50%" alt="profile">
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
              <div class="col-md-5">
                <h4>Account Transactions (Payments)</h4>
                <table class="table" id="account_table">
                  <thead>
                      <tr>
                          <th style="width: 33%;">Date</th>
                          <th style="width: 33%;">Paid Amount</th>
                          <th style="width: 33%;">Remaining Balance</th>
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
                      </tr>
                      @endforeach
                  </tbody>
              </table>
              </div>
              
              <div class="col-md-2"></div>
              
              <div class="col-md-5">
                <h4>Invoice Transactions (Purchases)</h4>
                <table class="table" id="inventory_table">
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
          </div>
          </div>
      </div>
  </div>
</div>
@endsection

@section('script')
<script>
  console.log('accountDetail', accountDetail)
</script>
@endsection
