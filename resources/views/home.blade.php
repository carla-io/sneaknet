@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="text-center">Store</h1>

    <!-- Search Bar -->
    <div class="input-group mb-3">
        <input type="text" id="searchBar" class="form-control" placeholder="Search for products...">
        <button class="btn btn-outline-secondary" type="button" id="searchButton">Search</button>
    </div>

    <!-- Categories -->
    <div class="mb-3">
        <select id="categorySelect" class="form-select">
            <option value="all">All Categories</option>
        </select>
    </div>

    <!-- Products -->
    <div id="productContainer" class="row"></div>

    <!-- Shopping Cart -->
    <div class="fixed-bottom p-3 text-end">
        <button type="button" class="btn btn-primary position-relative" id="cartButton">
            Cart <span class="cart-count" id="cartCount">0</span>
        </button>
    </div>
</div>

<script>
    let products = [];
    let cart = [];

    $(document).ready(function () {
        // Fetch categories
        $.ajax({
            url: '/api/category',
            method: 'GET',
            success: function (response) {
                console.log('Categories data:', response);
                const categories = response.data; // Access the 'data' property
                if (Array.isArray(categories)) {
                    categories.forEach(category => {
                        $('#categorySelect').append(new Option(category.name, category.id));
                    });
                } else {
                    console.error('Categories data is not an array:', categories);
                }
            },
            error: function (xhr, status, error) {
                console.error('Failed to fetch categories:', status, error);
            }
        });

        // Fetch products
        fetchProducts();

        // Search functionality
        $('#searchBar').on('input', function () {
            const query = $(this).val().toLowerCase();
            if (query.length > 2) { // Start searching only after 3 characters
                $.ajax({
                    url: '/api/search',
                    method: 'GET',
                    data: { query: query },
                    success: function (response) {
                        const searchResults = response.data; // Access the 'data' property
                        if (Array.isArray(searchResults)) {
                            displayProducts(searchResults);
                        } else {
                            console.error('Search results are not an array:', searchResults);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Failed to search products:', status, error);
                    }
                });
            } else {
                // Reset products if query is too short
                displayProducts(products);
            }
        });

        // Category filter functionality
        $('#categorySelect').on('change', function () {
            const categoryId = $(this).val();
            if (categoryId === 'all') {
                displayProducts(products);
            } else {
                displayProducts(products.filter(product => product.category_id == categoryId));
            }
        });

        // Shopping cart button
        $('#cartButton').on('click', function () {
            alert('Items in cart: ' + JSON.stringify(cart));
        });
    });

    function fetchProducts() {
        $.ajax({
            url: '/api/products',
            method: 'GET',
            success: function (response) {
                console.log('Products data:', response);
                const productsData = response.data; // Access the 'data' property
                if (Array.isArray(productsData)) {
                    products = productsData;
                    displayProducts(products);
                } else {
                    console.error('Products data is not an array:', productsData);
                }
            },
            error: function (xhr, status, error) {
                console.error('Failed to fetch products:', status, error);
            }
        });
    }

    function displayProducts(products) {
        $('#productContainer').empty();
        if (Array.isArray(products)) {
            products.forEach(product => {
                const productCard = `
                    <div class="col-md-4 mb-4">
                        <div class="card">
                          <img src="/images/${product.image}" class="card-img-top" alt="${product.product_name}">
                            <div class="card-body">
                                <h5 class="card-title">${product.product_name}</h5>
                                <p class="card-text">${product.price}</p>
                                <button class="btn btn-primary" onclick="addToCart(${product.id})">Add to Cart</button>
                            </div>
                        </div>
                    </div>
                `;
                $('#productContainer').append(productCard);
            });
        } else {
            console.error('Products is not an array:', products);
        }
    }

    function addToCart(productId) {
        const product = products.find(p => p.id == productId);
        if (product) {
            cart.push(product);
            $('#cartCount').text(cart.length);
        } else {
            console.error('Product not found:', productId);
        }
    }
</script>

@endsection
