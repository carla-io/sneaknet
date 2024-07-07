@extends('admin.layouts.template')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">SneakTech /</span> Categories </h4>
    <!-- Basic Bootstrap Table -->
    <div class="card">
    
        <div class="col-lg-4 col-md-6">
            <div class="mt-3">
           
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title" id="modal-title">Add Category</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="ajaxForm">
                                <div class="modal-body">
                                    <input type="hidden" id="category_id">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Category Name</label>
                                        <input type="text" class="form-control" id="name" name="name">
                                        <label id="name-error" class="error" for="name"></label>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <input type="text" class="form-control" id="description" name="description">
                                        <label id="decription-error" class="error" for="description"></label>
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
        <h2>Category List</h2>
            <table id="categoryTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function () {
var table = $('#categoryTable').DataTable({
        ajax: {
            url: "/api/category",
            dataSrc: "data"
        },
        dom: 'Bfrtip',
        buttons: [
            'pdf',
            'excel',    
            {
                text: 'Add Category',
                className: 'btn btn-primary',
                action: function (e, dt, node, config) {
                    $("#ajaxForm").trigger("reset");
                    $('#category_id').val('');
                    $('#exampleModal').modal('show');
                    $('#modal-title').html('Add Category');
                    $('#saveBtn').show();
                    $('#updateBtn').hide();
                }
            }
        ],
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'description' },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-primary btn-sm edit-btn" data-id="${row.id}" data-name="${row.name}" data-description="${row.description}">Edit</button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">Delete</button>
                    `;
                }
            }
        ]
    });

     // Initialize validation
     $('#ajaxForm').validate({
        rules: {
            name: {
                required: true,
                minlength: 3
            },
            description: {
                required: true,
                minlength: 3
            }
        },
        messages: {
            name: {
                required: "Please enter the category name",
                minlength: "Product name should be at least 3 characters"
            },
            description: {
                required: "Please enter description",
                 minlength: "Description should be at least 5 characters"
            }
        },
        errorPlacement: function (error, element) {
            // Display the error message next to the input field
            error.insertAfter(element);
        },
        
    });

    // Handle the save button click
    $("#saveBtn").on('click', function (e) {
        e.preventDefault();
        if ($('#ajaxForm').valid()) {
        var formData = {
            name: $('#name').val(),
            description: $('#description').val()
        };
        $.ajax({
            type: "POST",
            url: "/api/create-category",
            data: formData,
            dataType: "json",
            success: function (data) {
                $('#exampleModal').modal('hide');
                $('.modal-backdrop').remove();
                table.ajax.reload();
            },
            error: function (error) {
                console.log(error);
            }
        });

       }
    });

      // Handle the edit button click
      $('#categoryTable tbody').on('click', 'button.edit-btn', function () {
        var data = table.row($(this).parents('tr')).data();
        
        $('#category_id').val(data.id);
        $('#name').val(data.name);
        $('#description').val(data.description);
        
        $('#exampleModal').modal('show');
        $('#modal-title').html('Edit Category');
        $('#saveBtn').hide();
        $('#updateBtn').show();
    });

    // Handle the update button click
    $("#updateBtn").on('click', function (e) {
        e.preventDefault();
        if ($('#ajaxForm').valid()) {
        var id = $('#category_id').val();
        var formData = {
            category_id: id,
            name: $('#name').val(),
            description: $('#description').val()
        };
        $.ajax({
            type: "POST",
            url: "/api/update-category",
            data: formData,
            dataType: "json",
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

     // Handle the delete button click
     $('#categoryTable tbody').on('click', 'button.delete-btn', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this category?')) {
            $.ajax({
                type: "DELETE",
                url: "/api/delete-category",
                data: {
                    category_id: id
                 },
                 headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function (data) {
                    table.ajax.reload();
                    alert(data.message);
                },
                error: function (error) {
                    console.log(error);
                    alert("Failed to delete product.")
                }
            });
        }
    });

});

</script>



@endsection