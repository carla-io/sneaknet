@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Shopping Cart</h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Product Name</th>
            <th>Image</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody id="cartTableBody">
       
        </tbody>
    </table>
    <button id="checkoutButton" class="btn btn-success">Proceed to Checkout</button>
</div>



@endsection