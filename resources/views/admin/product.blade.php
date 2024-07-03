@extends('admin.layouts.template')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">SneakTech /</span> Products</h4>
    <!-- Basic Bootstrap Table -->
    <div class="card">
    
        <div class="col-lg-4 col-md-6">
            <div class="mt-3">
           
            <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Add product
        </button> -->
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <form id="ajaxForm">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="modal-title">Add Product</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="product_id">
                                    <div class="form-group mb-3">
                                        <label for="product_name" class="form-label">Product Name</label>
                                        <input type="text" class="form-control" id="product_name" placeholder="Example input placeholder">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="quantity" class="form-label">Quantity</label>
                                        <input type="text" class="form-control" id="quantity" placeholder="Another input placeholder">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="price" class="form-label">Price</label>
                                        <input type="text" class="form-control" id="price" placeholder="Another input placeholder">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <input type="text" class="form-control" id="description" placeholder="Another input placeholder">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" id="saveBtn">Save Product</button>
                                    <button type="button" class="btn btn-primary" id="updateBtn" style="display: none;">Update Product</button>
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
        <h2>Product List</h2>
            <table id="productTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
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



<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> -->
<script type="text/javascript">
$(document).ready(function () {
    var table = $('#productTable').DataTable({
        ajax: {
            url: "/api/products",
            dataSrc: "data"
        },
        dom: 'Bfrtip',
        buttons: [
            'pdf',
            'excel',    
            {
                text: 'Add Product',
                className: 'btn btn-primary',
                action: function (e, dt, node, config) {
                    $("#ajaxForm").trigger("reset");
                    $('#product_id').val('');
                    $('#exampleModal').modal('show');
                    $('#modal-title').html('Add Product');
                    $('#saveBtn').show();
                    $('#updateBtn').hide();
                }
            }
        ],
        columns: [
            { data: 'id' },
            { data: 'product_name' },
            { data: 'quantity' },
            { data: 'price' },
            { data: 'description' },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-primary btn-sm edit-btn" data-id="${row.id}" data-name="${row.product_name}" data-quantity="${row.quantity}" data-price="${row.price}" data-description="${row.description}">Edit</button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">Delete</button>
                    `;
                }
            }
        ]
    });

    // Handle the save button click
    $("#saveBtn").on('click', function (e) {
        e.preventDefault();
        var formData = {
            product_name: $('#product_name').val(),
            quantity: $('#quantity').val(),
            price: $('#price').val(),
            description: $('#description').val()
        };
        $.ajax({
            type: "POST",
            url: "/api/create-product",
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
    });

    // Handle the edit button click
    $('#productTable tbody').on('click', 'button.edit-btn', function () {
        var data = table.row($(this).parents('tr')).data();
        
        $('#product_id').val(data.id);
        $('#product_name').val(data.product_name);
        $('#quantity').val(data.quantity);
        $('#price').val(data.price);
        $('#description').val(data.description);
        
        $('#exampleModal').modal('show');
        $('#modal-title').html('Edit Product');
        $('#saveBtn').hide();
        $('#updateBtn').show();
    });

    // Handle the update button click
    $("#updateBtn").on('click', function (e) {
        e.preventDefault();
        var id = $('#product_id').val();
        var formData = {
            product_id: id,
            product_name: $('#product_name').val(),
            quantity: $('#quantity').val(),
            price: $('#price').val(),
            description: $('#description').val()
        };
        $.ajax({
            type: "POST",
            url: "/api/update-product",
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
    });

    // Handle the delete button click
    $('#productTable tbody').on('click', 'button.delete-btn', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this product?')) {
            $.ajax({
                type: "DELETE",
                url: "/api/delete-product",
                data: {
                    product_id: id
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

@endsection
