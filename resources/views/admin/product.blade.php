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
                                        <input type="text" class="form-control" id="product_name" placeholder="name">
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="price" class="form-label">Price</label>
                                        <input type="text" class="form-control" id="price" placeholder="">
                                    </div>

                                    <div class="form-group mb-3">
                                     <label for="category_id" class="form-label">Category</label>
                                       <select class="form-control" id="category_id" name="category_id">
                                       @foreach ($categories as $category)
                                              <option value="{{ $category->id }}">{{ $category->name }}</option>
                                         @endforeach
                                       </select>
                                     </div>

                                    <div class="form-group mb-3">
                                          <label for="image" class="form-label">Product Image</label>
                                        <input type="file" class="form-control" id="image" name="image">
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
                        <th>Price</th>
                        <th>Category</th>
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
            { data: 'price' },
            {
                data: 'category',
                render: function (data, type, row) {
                    return data ? data.name : '';
                }
            },
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
                        <button class="btn btn-primary btn-sm edit-btn" data-id="${row.id}" data-name="${row.product_name}" data-price="${row.price}" data-category_id="${row.category_id}">Edit</button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">Delete</button>
                    `;
                }
            }
        ]
    });

    // Handle Save Product Button Click
    $("#saveBtn").on('click', function (e) {
        e.preventDefault();
        var formData = new FormData();
        formData.append('product_name', $('#product_name').val());
        formData.append('price', $('#price').val());
        formData.append('category_id', $('#category_id').val());
        formData.append('image', $('#image')[0].files[0]);

        $.ajax({
            type: "POST",
            url: "/api/create-product",
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
    });

    // Handle Edit Product Button Click
    $('#productTable tbody').on('click', 'button.edit-btn', function () {
        var data = table.row($(this).parents('tr')).data();
        $('#product_id').val(data.id);
        $('#product_name').val(data.product_name);
        $('#price').val(data.price);
        $('#category_id').val(data.category_id);
        $('#exampleModal').modal('show');
        $('#modal-title').html('Edit Product');
        $('#saveBtn').hide();
        $('#updateBtn').show();
    });

    // Handle Update Product Button Click
    $("#updateBtn").on('click', function (e) {
        e.preventDefault();
        var formData = new FormData();
        formData.append('product_id', $('#product_id').val());
        formData.append('product_name', $('#product_name').val());
        formData.append('price', $('#price').val());
        formData.append('category_id', $('#category_id').val());
        formData.append('image', $('#image')[0].files[0]);

        $.ajax({
            type: "POST",
            url: "/api/update-product",
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
    });

    // Handle Delete Product Button Click
    $('#productTable tbody').on('click', 'button.delete-btn', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this product?')) {
            $.ajax({
                type: "DELETE",
                url: "/api/delete-product",
                data: { product_id: id },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    table.ajax.reload();
                    alert(data.message);
                },
                error: function (error) {
                    console.log(error);
                    alert("Failed to delete product.");
                }
            });
        }
    });
});
</script>


@endsection
