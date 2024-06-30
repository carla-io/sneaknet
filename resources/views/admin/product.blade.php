@extends('admin.layouts.template')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="py-3 mb-4"><span class="text-muted fw-light">Sneaknet /</span> Products</h4>

              <!-- Basic Bootstrap Table -->
              <div class="card">
                <h5 class="card-header">Products</h5>
                <div class="col-lg-4 col-md-6">
                      <div class="mt-3">
                        <!-- Button trigger modal -->
                        <button
                          type="button"
                          class="btn btn-primary"
                          data-bs-toggle="modal"
                          data-bs-target="#basicModal">
                          Create new product
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel1">Add new product</h5>
                                <button
                                  type="button"
                                  class="btn-close"
                                  data-bs-dismiss="modal"
                                  aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameBasic" class="form-label">Name</label>
                                    <input type="text" id="nameBasic" class="form-control" placeholder="Enter Name" />
                                  </div>
                                </div>
                                <div class="row g-2">
                                  <div class="col mb-0">
                                    <label for="emailBasic" class="form-label">Email</label>
                                    <input
                                      type="email"
                                      id="emailBasic"
                                      class="form-control"
                                      placeholder="xxxx@xxx.xx" />
                                  </div>
                                  <div class="col mb-0">
                                    <label for="dobBasic" class="form-label">DOB</label>
                                    <input type="date" id="dobBasic" class="form-control" />
                                  </div>
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                  Close
                                </button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                
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
              <!--/ Basic Bootstrap Table -->

              <script type="text/javascript">
$(document).ready(function() {
    $('#productTable').DataTable({
        "ajax": {
            "url": "/api/products", // URL to your API endpoint
            "type": "GET",
            "dataSrc": "data" // Adjust this according to your API response
        },
        "columns": [
            { "data": "id" },
            { "data": "product_name" },
            { "data": "quantity" },
            { "data": "price" },
            { "data": "description" },
            {
                "data": null,
                "defaultContent": '<button class="editBtn">Edit</button> <button class="deleteBtn">Delete</button>'
            }
        ]
    });

    // Handle Edit Button Click
    $('#productTable tbody').on('click', '.editBtn', function() {
        var data = $('#productTable').DataTable().row($(this).parents('tr')).data();
        alert('Edit product with ID: ' + data.id);
        // Implement edit functionality here
    });

    // Handle Delete Button Click
    $('#productTable tbody').on('click', '.deleteBtn', function() {
        var data = $('#productTable').DataTable().row($(this).parents('tr')).data();
        if (confirm('Are you sure you want to delete product with ID: ' + data.id + '?')) {
            $.ajax({
                url: '/api/products/delete',
                type: 'DELETE',
                data: { product_id: data.id },
                success: function(response) {
                    alert('Product deleted successfully');
                    $('#productTable').DataTable().ajax.reload();
                },
                error: function(response) {
                    alert('Error deleting product');
                }
            });
        }
    });
});

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>


@endsection