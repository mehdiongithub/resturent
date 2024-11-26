@extends('Layouts.auth');
@section('style')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <!-- Include Bootstrap Icons for camera icon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        /* Styling for the rich text editor */
        .ql-editor {
            min-height: 100%;
            /* Set a minimum height for the editor */
            font-size: 16px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

         .unit_of_mesu .select2-container .select2-selection--single {
            height: 37px !important;
            padding-top: 5px
        }

        .unit_of_mesu .select2-selection__arrow {
            top: 5px !important;
        }

        /* Styling for photo upload container */
        .photo-upload-container {
            position: relative;
        }

        /* The actual file input (hidden) */
        .photo-input {
            display: none;
        }

        /* Circular button for photo upload */
        .photo-select-circle {
            width: 50%;
            height: 80%;
            border-radius: 5px;
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

        /* Preview container for the uploaded image */
        .photo-preview {
            width: 50%;
            height: 100%;
            object-fit: cover;
            border: 2px dashed #ccc;
            border-radius: 8px;
            background-color: #f0f0f0;
            position: absolute;
            top: 0;
        }

        /* Cross button to remove image */
        .remove-image-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(255, 255, 255, 0.7);
            border: none;
            border-radius: 50%;
            padding: 5px;
            cursor: pointer;
        }

        .remove-image-btn i {
            font-size: 18px;
            color: #ff0000;
        }

        #image-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        label {
            font-weight: 800;
        }
    </style>
@endsection

@section('content')
    @include('reset-data.reset');
    <div class="container">
        <div class="page-inner">
            <div class="page-header d-flex justify-content-between align-items-center">
                <h3 class="fw-bold mb-3">Create Product</h3>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form id="product-form" class="row g-3" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-6">
                            <label for="inputName4" class="form-label">Name</label>
                            <input type="text" placeholder="Product Name" name="name" class="form-control"
                                id="inputName4">
                            <input type="hidden" value="{{ getUserCompanyId() }}" name="company_id" class="form-control"
                                id="inputCompanyId4">
                        </div>
                        <div class="col-md-6">
                            <label for="inputPrice4" class="form-label">Price</label>
                            <input type="number" placeholder="Price" name="price" class="form-control" id="inputPrice4">
                            <input type="hidden" value="{{ getUserStoreId() }}" name="store_id" class="form-control"
                                id="inputStoreId4">
                        </div>

                        <div class="col-md-6 unit_of_mesu">
                            <label for="uom" class="form-label">Unit Of Measurement</label>
                            <select name="uom" class="form-control" id="uom">
                                <option>Select Unit</option>
                                <option value="kg">KG</option>
                                <option value="liter">Liter</option>
                                <option value="dozen">Dozen</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 unit_of_mesu">
                            <label for="product_type" class="form-label">Unit Of Measurement</label>
                            <select name="product_type" class="form-control" id="product_type">
                                <option>Select Product Type</option>
                                <option value="long_term">Long Term</option>
                                <option value="short_term">Short Term</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="description" rows="6" placeholder="Write something here..."></textarea>
                        </div>

                        <div class="col-md-6 align-content-center">
                            <label for="photo" class="form-label">Photo</label>
                            <div class="photo-upload-container">
                                <input type="file" id="photo" name="photo" accept="image/*" class="photo-input"
                                    onchange="previewImage(event)">
                                <label for="photo" class="photo-select-circle">
                                    <i class="bi bi-camera"></i> <!-- Optional: You can use an icon like a camera here -->
                                </label>

                                <div id="photo-preview" class="photo-preview" style="display: none;">
                                    <img id="image-preview" src="" alt="Image Preview">
                                    <button type="button" class="remove-image-btn" onclick="removeImage()">
                                        <i class="bi bi-x"></i> <!-- Cross icon to remove image -->
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 d-flex justify-content-end">
                            <input type="button" value="Back" class="btn btn-success btn-sm ms-2" id="back">
                            <button id="clear" type="button" class="btn btn-danger btn-sm ms-2" data-bs-toggle="modal"
                                data-bs-target="#resetModal" data-form-id="product-form">
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
        // Preview the selected image
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Show the preview div
                    document.getElementById('photo-preview').style.display = 'block';
                    // Set the preview image source
                    document.getElementById('image-preview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        // Remove the selected image
        function removeImage() {
            // Reset the file input
            document.getElementById('photo').value = '';
            // Hide the preview div
            document.getElementById('photo-preview').style.display = 'none';
        }


        $('#back').click(function() {
            // Redirect to the products.index route
            window.location.href = '{{ route('products.index') }}';
        });

        $('#clear').prop('disabled', true);

        $('input, textarea').on('input', function() {
            // Check if all input and textarea fields are empty
            let isEmpty = true; // Assume all fields are empty initially

            $('input, textarea').each(function() {
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
        $('#product-form').submit(function(e) {
            e.preventDefault(); // Prevent the default form submission

            var formData = new FormData(this); // Create a FormData object

            $.ajax({
                url: '{{ route('products.store') }}', // URL to send the form data
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

            // Initialize Select2
            $('#uom').select2();
            $('#product_type').select2();

        })
    </script>
    <script src="{{ asset('assets/auth/js/reset-data.js') }}"></script>
@endsection
