@extends('admin.layouts.template')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">SneakTech /</span> Shippers </h4>
    <!-- Basic Bootstrap Table -->
    <div class="card">
    
        <div class="col-lg-4 col-md-6">
            <div class="mt-3">
           
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title" id="modal-title">Add Shipper</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="ajaxForm">
                                <div class="modal-body">
                                    <input type="hidden" id="shipper_id">
                                    <div class="form-group mb-3">
                                        <label for="shipper_name" class="form-label">Shipper Name</label>
                                        <input type="text" class="form-control" id="shipper_name" name="shipper_name" placeholder="name">
                                        <label id="shipper_name-error" class="error" for="shipper_name"></label>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="shipper_contact" class="form-label">Shipper Contact Number</label>
                                        <input type="text" class="form-control" id="shipper_contact" name="shipper_contact" placeholder="+63">
                                        <label id="shipper_contact-error" class="error" for="shipper_contact"></label>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="shipper_address" class="form-label">Shipper Address</label>
                                        <input type="text" class="form-control" id="shipper_address" name="shipper_address"  placeholder="address">
                                        <label id="shipper_address-error" class="error" for="shipper_address"></label>
                                    </div>

                                    <div class="form-group mb-3">
                                          <label for="image" class="form-label">Shipper Image</label>
                                        <input type="file" class="form-control" id="image" name="image">
                                     </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" id="saveBtn">Save Category</button>
                                    <button type="button" class="btn btn-primary" id="updateBtn" style="display: none;">Update Category</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="container">
        <h2>Shipper List</h2>
            <table id="shipperTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Shipper Name</th>
                        <th>Shipper Contact Number</th>
                        <th>Shipper Address</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
        
</div>

<script type="text/javascript">
$(document).ready(function () {
    console.log("Document ready");
    var table = $('#shipperTable').DataTable({
        ajax: {
            url: "/api/shipper",
            dataSrc: "data",
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error loading data: ' + textStatus, errorThrown);
            }
        },
        dom: 'Bfrtip',
        buttons: [
            'pdf',
            'excel',
            {
                text: 'Add Shipper',
                className: 'btn btn-primary',
                action: function (e, dt, node, config) {
                    $("#ajaxForm").trigger("reset");
                    $('#shipper_id').val('');
                    $('#exampleModal').modal('show');
                    $('#modal-title').html('Add Shipper');
                    $('#saveBtn').show();
                    $('#updateBtn').hide();
                }
            }
        ],
        columns: [
            { data: 'id' },
            { data: 'shipper_name' },
            { data: 'shipper_contact' },
            { data: 'shipper_address' },
            {
                data: 'image',
                render: function (data, type, row) {
                    return `<img src="/images/${data}" width="50" height="60">`;
                }
            },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-primary btn-sm edit-btn" data-id="${row.id}" data-shipperr_name="${row.shipper_name}" data-shipper_contact="${row.shipper_contact}" data-shipper_address="${row.shipper_address}">Edit</button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">Delete</button>
                    `;
                }
            }
        ]
    });

     // Initialize validation
     $('#ajaxForm').validate({
        rules: {
            shipper_name: {
                required: true,
                minlength: 3
            },
            shipper_contact: {
                required: true,
                minlength: 3,
                number: true
            },
            shipper_address: {
                required: true,
                minlength: 5
            },
        },
        messages: {
            shipper_name: {
                required: "Please enter the shipper name",
                minlength: "Shipper name should be at least 3 characters"
            },
            shipper_contact: {
                required: "Please enter the shipper contact number",
                minlength: "Shipper contact number should be at least 3 characters"
            },
            shipper_address: {
                required: "Please enter the shipper address",
                minlength: "Shipper address should be at least 5 characters"
            }
        },
        errorPlacement: function (error, element) {
            // Display the error message next to the input field
            error.insertAfter(element);
        },
        
    });

    // Handle Save Shipper Button Click
    $("#saveBtn").on('click', function (e) {
        e.preventDefault();
        if ($('#ajaxForm').valid()) {
        var formData = new FormData();
        formData.append('shipper_name', $('#shipper_name').val());
        formData.append('shipper_contact', $('#shipper_contact').val());
        formData.append('shipper_address', $('#shipper_address').val());
        formData.append('image', $('#image')[0].files[0]);


        $.ajax({
            type: "POST",
            url: "/api/create-shipper",
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                $('#exampleModal').modal('hide');
                table.ajax.reload();
            },
            error: function (error) {
                console.log(error);
            }
        });
       }
    });

     // Handle the edit button click
     $('#shipperTable tbody').on('click', 'button.edit-btn', function () {
        var data = table.row($(this).parents('tr')).data();
        
        $('#shipper_id').val(data.id);
        $('#shipper_name').val(data.shipper_name);
        $('#shipper_contact').val(data.shipper_contact);
        $('#shipper_address').val(data.shipper_address);

        $('#exampleModal').modal('show');
        $('#modal-title').html('Edit Shipper');
        $('#saveBtn').hide();
        $('#updateBtn').show();
    });

    // Handle Update Shipper Button Click
    $("#updateBtn").on('click', function (e) {
        e.preventDefault();
        if ($('#ajaxForm').valid()) {
        var formData = new FormData();
        formData.append('shipper_id', $('#shipper_id').val());
        formData.append('shipper_name', $('#shipper_name').val());
        formData.append('shipper_contact', $('#shipper_contact').val());
        formData.append('shipper_address', $('#shipper_address').val());
        formData.append('image', $('#image')[0].files[0]);

        $.ajax({
            type: "POST",
            url: "/api/update-shipper",
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                $('#exampleModal').modal('hide');
                table.ajax.reload();
            },
            error: function (error) {
                console.log(error);
            }
        });

      }
    });

     // Handle Delete Supplier Button Click
     $('#shipperTable tbody').on('click', 'button.delete-btn', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this shipper?')) {
            $.ajax({
                type: "DELETE",
                url: "/api/delete-shipper",
                data: { shipper_id: id },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    table.ajax.reload();
                    alert(data.message);
                },
                error: function (error) {
                    console.log(error);
                    alert("Failed to delete shipper.");
                }
            });
        }
    });


});



</script>
@endsection