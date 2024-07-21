@extends('layouts.template')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
              <!-- Search -->
        <input type="text" id="searchBar" class="form-control mb-4" placeholder="Search for products...">
              <!-- /Search -->


            <select id="categorySelect" class="form-control mb-4">
                <option value="all">All Categories</option>
               
            </select>
            
            <div class="row" id="productContainer">
                <!-- Products will be appended here dynamically -->
            </div>
        </div>
    </div>
</div>
<button id="floatingCartButton" class="btn btn-primary">
    View Cart (<span id="cartCount">0</span>)
</button>
@endsection
