@extends('Layouts.auth')

@section('title')
Suppliers
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

</style>
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
      <div class="page-header d-flex justify-content-between align-items-center">
        <h3 class="fw-bold mb-3">Supplier</h3>
        <a href="{{route('suppliers.create')}}" class="btn btn-primary">Add Supplier</a>
      </div>      
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">Suppliers Data</h4>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table
                  id="basic-datatables"
                  class="display table table-striped table-hover"
                >
                  <thead>
                    <tr>
                      <th>S:No</th>
                      <th>Name</th>
                      <th>Phone No</th>
                      <th>Photo</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @include('delete-modal.delete-modal') <!-- Include Modal here -->
                    
                    @foreach ($data as $supplier)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->phone }}</td>
                            <td>
                                @if($supplier->photo)
                                    <img src="{{ asset('storage/' . $supplier->photo) }}" alt="Supplier Photo" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('assets/auth/img/img_avatar2.png') }}" alt="Default Avatar" style="width: 50px; height: 50px; object-fit: cover;">
                                @endif
                            </td>
                            
                            <td>
                                <!-- Show Link with Icon and Text -->
                                <a href="{{ route('suppliers.show', $supplier->id) }}" class="action-icon">
                                    <i class="fas fa-eye"></i>
                                </a>
                
                                <!-- Edit Link with Icon and Text -->
                                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="action-icon">
                                    <i class="fas fa-edit"></i>
                                </a>
                
                                <!-- Delete Form with Icon and Text -->
                                <form id="deleteForm{{ $supplier->id }}" action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                        data-title="supplier" data-form-id="deleteForm{{ $supplier->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </form>
                            </td>
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
</div>

@endsection

@section('script')

<script>
    $(document).ready(function () {
      $("#basic-datatables").DataTable({
        "columnDefs": [
          {
            "targets": 4, // Disable sorting for the 'Action' column (index 4)
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
