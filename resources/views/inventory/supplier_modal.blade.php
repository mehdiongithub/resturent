<!-- Include Quill.js for rich text editing -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<!-- Include Bootstrap for styling -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Include Bootstrap Icons for camera icon -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Custom CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

<style>
  /* Styling for the rich text editor */
  
  /* Center the image preview within the modal */
  .photo-upload-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: relative;
  }

  /* Hidden file input */
  .photo-input {
    display: none !important;
  }

  /* Circular button for photo upload */
  .photo-select-circle {
    width: 50%; /* Set the width relative to the modal */
    height: 150px; /* Fixed height */
    border-radius: 8px;
    border: 2px solid #ccc;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    background-color: #f0f0f0;
    transition: background-color 0.3s;
  }

  .photo-select-circle:hover {
    background-color: #e0e0e0;
  }

  .photo-select-circle i {
    font-size: 24px;
    color: #666;
  }

  /* Adjust the preview container */
  .photo-preview {
    width: 50%; /* Half the modal width */
    height: 150px; /* Fixed height */
    object-fit: cover;
    border: 2px dashed #ccc;
    border-radius: 8px;
    background-color: #f0f0f0;
    position: absolute;
    display: none; /* Initially hidden */
    top: 0;
  }

  .photo-preview img {
    width: 100%; /* Match the width of the preview container */
    height: 100%; /* Maintain aspect ratio */
    border-radius: 8px;
  }

  .remove-image-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    background-color: rgba(255, 255, 255, 0.8);
    border: none;
    cursor: pointer;
    border-radius: 50%;
    padding: 5px;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .remove-image-btn i {
    font-size: 16px;
    color: #666;
  }

  label {
    font-weight: 800;
  }

  
</style>

<!-- Modal -->
<!-- Supplier Modal -->
<div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="supplierModalLabel">Supplier Modal</h1>
        <button type="button" class="btn-close" id="supplier_close_btn" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body font-weight-bolder">
        <form id="supplier-form" class="row g-3" enctype="multipart/form-data">
          @csrf
          <!-- Name Input -->
          <div class="col-md-12 mb-3">
            <label for="inputName4" class="form-label">Name</label>
            <input type="text" placeholder="Supplier Name" name="name" class="form-control" id="inputName4">
            <input type="hidden" value="{{ getUserCompanyId() }}" name="company_id" class="form-control" id="inputCompanyId4">
          </div>
          <!-- Phone Input -->
          <div class="col-md-12 mb-3">
            <label for="inputPhone4" class="form-label">Phone No</label>
            <input type="number" name="phone" placeholder="Supplier Phone No" class="form-control" id="inputPhone4">
            <input type="hidden" value="{{ getUserStoreId() }}" name="store_id" class="form-control" id="inputStoreId4">
          </div>
          <!-- Photo Input -->
          <div class="col-md-12 mb-3">
            <label for="photo" class="form-label">Photo</label>
            <div class="photo-upload-container">
              <input type="file" id="photo" name="photo" accept="image/*" class="photo-input" onchange="previewImage(event)">
              <label for="photo" class="photo-select-circle">
                <i class="bi bi-camera"></i>
              </label>
              <div id="photo-preview" class="photo-preview">
                <img id="image-preview" src="" alt="Image Preview">
                <button type="button" class="remove-image-btn" onclick="removeImage()">
                  <i class="bi bi-x"></i>
                </button>
              </div>
            </div>
          </div>
          <!-- Buttons -->
          <div class="col-md-12 d-flex justify-content-center mb-4">
            <input type="button" value="Close" class="btn btn-secondary" id="btn_close" data-bs-dismiss="modal">
            <button id="clear" type="button" class="btn btn-danger btn-sm ms-2">
              Clear
            </button>
            <input type="submit" value="Submit" class="btn btn-primary btn-sm ms-2" id="submit">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Reset Modal -->
<div class="modal fade" id="supplierResetModal" tabindex="-1" aria-labelledby="supplierResetModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered custom-width modal-sm">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h1 class="modal-title fs-5" id="supplierResetModalLabel">Supplier Reset Confirmation</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to clear the form?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="supplierResetModalCloseBtn" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirm-clear">Clear</button>
      </div>
    </div>
  </div>
</div>


<script src="{{ asset('assets/auth/js/core/jquery-3.7.1.min.js') }}"></script>
{{-- Toast JS File --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
  // Preview the selected image
  function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById('photo-preview').style.display = 'block';
        document.getElementById('image-preview').src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  }

  // Remove the selected image
  function removeImage() {
    document.getElementById('photo').value = '';
    document.getElementById('photo-preview').style.display = 'none';
  }

  


</script>
<!-- Your modal HTML code -->

<script>
  // Image preview logic here...

  // The new JavaScript logic to disable/enable inputs
  let = selectedSupplier = '';
  function disableSupplierModalInputs() {
    const inputs = document.querySelectorAll("#supplier-form input, #supplier-form select, #supplier-form textarea");
    inputs.forEach(input => {
      input.disabled = false;
    });
  }

  function enableSupplierModalInputs() {
    const inputs = document.querySelectorAll("#supplier-form input, #supplier-form select, #supplier-form textarea");
    inputs.forEach(input => {
      input.disabled = false;
    });
  }

  document.getElementById("clear").addEventListener("click", function() {
    const resetModal = new bootstrap.Modal(document.getElementById("supplierResetModal"));
    resetModal.show();
    disableSupplierModalInputs();
  });

  document.getElementById("confirm-clear").addEventListener("click", function() {
  // Reset the supplier form
  document.getElementById("supplier-form").reset();

  // Disable inputs (if needed as part of your logic)
  disableSupplierModalInputs();

  // Hide the reset modal
  const resetModalElement = document.getElementById("supplierResetModal");
  const resetModalInstance = bootstrap.Modal.getInstance(resetModalElement); // Get the existing instance
  if (resetModalInstance) {
    resetModalInstance.hide(); // Hide the modal
    removeImage()
  }
});

  document.getElementById("supplierResetModalCloseBtn").addEventListener("click", function() {
    enableSupplierModalInputs();
    const resetModal = new bootstrap.Modal(document.getElementById("supplierResetModal"));
    resetModal.hide();
  });
  
  $(document).ready(function(){
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

            $('#supplier_close_btn, #btn_close').on('click', function() {
                console.log("Closing supplier modal...");

                if (selectedSupplier !== '') {
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

                // Manually ensure the backdrop is removed and the body is unlocked
                document.body.classList.remove('modal-open');
                var modalBackdrop = document.querySelector('.modal-backdrop');
                if (modalBackdrop) {
                    modalBackdrop.classList.remove('show');
                }

                // Ensure the form fields are enabled again
                console.log("Enabling form fields...");
                $('input, select, textarea').prop('disabled', false);
            });

  })
</script>
