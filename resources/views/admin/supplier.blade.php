@extends('admin.layouts.template')

@section('content')
<div class="container">
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">SneakTech /</span> Suppliers </h4>
    <!-- Basic Bootstrap Table -->
    <div class="card">
    
        <div class="col-lg-4 col-md-6">
            <div class="mt-3">
           
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <form id="ajaxForm">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="modal-title">Add Supplier</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="supplier_id">
                                    <div class="form-group mb-3">
                                        <label for="supplier_name" class="form-label">Category Name</label>
                                        <input type="text" class="form-control" id="name" placeholder="Example input placeholder">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <input type="text" class="form-control" id="description" placeholder="Another input placeholder">
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
        <h2>Supplier List</h2>
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

});

</script>


@endsection