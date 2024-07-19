@extends('layouts.template')

@section('content')
<div class="container center-content">
    <div class="row">
        <div class="col-12">
            <select id="categorySelect" class="form-control mb-4">
                <option value="all">All Categories</option>
                <!-- Categories will be appended here dynamically -->
            </select>
            <input type="text" id="searchBar" class="form-control mb-4" placeholder="Search for products...">
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
