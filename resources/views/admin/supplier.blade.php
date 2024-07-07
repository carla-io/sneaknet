@extends('admin.layouts.template')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">SneakTech /</span> Suppliers </h4>
    <!-- Basic Bootstrap Table -->
    <div class="card">
    
        <div class="col-lg-4 col-md-6">
            <div class="mt-3">
           
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                   
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title" id="modal-title">Add Supplier</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="ajaxForm">
                                <div class="modal-body">
                                    <input type="hidden" id="supplier_id">
                                    <div class="mb-3">
                                        <label for="supplier_name" class="form-label">Supplier Name</label>
                                        <input type="text" class="form-control" id="supplier_name" name="supplier_name">
                                        <label id="supplier_name-error" class="error" for="supplier_name"></label>
                                    </div>

                                    <div class="mb-3">
                                        <label for="contact_name" class="form-label">Contact Name</label>
                                        <input type="text" class="form-control" id="contact_name" name="contact_name">
                                        <label id="contact_name-error" class="error" for="contact_name"></label>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="text" class="form-control" id="email" name="email" >
                                        <label id="email" class="error" for="email"></label>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="supplier_phone" class="form-label">Supplier phone</label>
                                        <input type="text" class="form-control" id="supplier_phone" name="supplier_phone" >
                                        <label id="supplier_phone-error" class="error" for="supplier_phone"></label>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="address" class="form-label"> Address </label>
                                        <input type="text" class="form-control" id="address" name="address" >
                                        <label id="address-error" class="error" for="address"></label>
                                    </div>

                                    <div class="form-group mb-3">
                                          <label for="image" class="form-label">Supplier Image</label>
                                        <input type="file" class="form-control" id="image" name="image">
                                        
                                     </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" id="saveBtn">Save Supplier</button>
                                    <button type="button" class="btn btn-primary" id="updateBtn" style="display: none;">Update Supplier</button>
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
        <h2>Supplier List</h2>
            <table id="supplierTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Supplier Name</th>
                        <th>Contact Name</th>
                        <th>Email</th>
                        <th>Supplier Phone</th>
                        <th>Address</th>
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
    var table = $('#supplierTable').DataTable({
        ajax: {
            url: "/api/supplier",
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
                text: 'Add Supplier',
                className: 'btn btn-primary',
                action: function (e, dt, node, config) {
                    $("#ajaxForm").trigger("reset");
                    $('#supplier_id').val('');
                    $('#exampleModal').modal('show');
                    $('#modal-title').html('Add Supplier');
                    $('#saveBtn').show();
                    $('#updateBtn').hide();
                }
            }
        ],
        columns: [
            { data: 'id' },
            { data: 'supplier_name' },
            { data: 'contact_name' },
            { data: 'email' },
            { data: 'supplier_phone' },
            { data: 'address' },
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
                        <button class="btn btn-primary btn-sm edit-btn" data-id="${row.id}" data-supplier_name="${row.supplier_name}" data-contact_name="${row.contact_name}" data-email="${row.email}" data-supplier_phone="${row.supplier_phone}" data-address="${row.address}">Edit</button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">Delete</button>
                    `;
                }
            }
        ]
    });

    
    // Initialize validation
    $('#ajaxForm').validate({
        rules: {
            supplier_name: {
                required: true,
                minlength: 3
            },
            contact_name: {
                required: true,
                minlength: 3
            },
            email: {
                required: true,
                email: true
            },
            supplier_phone: {
                required: true,
                minlength: 6,
                number: true
            },
            address: {
                required: true,
                minlength: 5
            },
            // image: {
            //     required: true,
            //     extension: "jpg|jpeg|png|gif"
            // }
        },
        messages: {
            supplier_name: {
                required: "Please enter the supplier name",
                minlength: "Supplier name should be at least 3 characters"
            },
            contact_name: {
                required: "Please enter the contact name",
                minlength: "Contact name should be at least 3 characters"
            },
            email: {
                required: "Please enter an email address",
                email: "Please enter a valid email address"
            },
            supplier_phone: {
                required: "Please enter a phone number",
                minlength: "Phone number should be at least 6 characters"
            },
            address: {
                required: "Please enter an address",
                minlength: "Address should be at least 5 characters"
            },
            image: {
                required: "Please select an image",
                extension: "Only image files (jpg, jpeg, png, gif) are allowed"
            }
        },
        errorPlacement: function (error, element) {
            // Display the error message next to the input field
            error.insertAfter(element);
        },
        
    });

    // Handle Save Supplier Button Click
    $("#saveBtn").on('click', function (e) {
        e.preventDefault();
        if ($('#ajaxForm').valid()) {
        var formData = new FormData();
        formData.append('supplier_name', $('#supplier_name').val());
        formData.append('contact_name', $('#contact_name').val());
        formData.append('email', $('#email').val());
        formData.append('supplier_phone', $('#supplier_phone').val());
        formData.append('address', $('#address').val());
        formData.append('image', $('#image')[0].files[0]);


        $.ajax({
            type: "POST",
            url: "/api/create-supplier",
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
    $('#supplierTable tbody').on('click', 'button.edit-btn', function () {
        var data = table.row($(this).parents('tr')).data();
        
        $('#supplier_id').val(data.id);
        $('#supplier_name').val(data.supplier_name);
        $('#contact_name').val(data.contact_name);
        $('#email').val(data.email);
        $('#supplier_phone').val(data.supplier_phone);
        $('#address').val(data.address);

        $('#exampleModal').modal('show');
        $('#modal-title').html('Edit Supplier');
        $('#saveBtn').hide();
        $('#updateBtn').show();
    });

    // Handle Update Supplier Button Click
    $("#updateBtn").on('click', function (e) {
        e.preventDefault();
        if ($('#ajaxForm').valid()) {
        var formData = new FormData();
        formData.append('supplier_id', $('#supplier_id').val());
        formData.append('supplier_name', $('#supplier_name').val());
        formData.append('contact_name', $('#contact_name').val());
        formData.append('email', $('#email').val());
        formData.append('supplier_phone', $('#supplier_phone').val());
        formData.append('address', $('#address').val());
        formData.append('image', $('#image')[0].files[0]);

        $.ajax({
            type: "POST",
            url: "/api/update-supplier",
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
    $('#supplierTable tbody').on('click', 'button.delete-btn', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this supplier?')) {
            $.ajax({
                type: "DELETE",
                url: "/api/delete-supplier",
                data: { supplier_id: id },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    table.ajax.reload();
                    alert(data.message);
                },
                error: function (error) {
                    console.log(error);
                    alert("Failed to delete supplier.");
                }
            });
        }
    });

});


</script>




@endsection