$(document).ready(function () {
    let cart = JSON.parse(sessionStorage.getItem('cart')) || [];
    displayCart(cart);
    updateCartCount();

    $('#checkoutButton').on('click', function () {
        $.ajax({
            url: '{{ route("cart.checkout") }}',
            method: 'POST',
            data: JSON.stringify({ cart: cart }),
            contentType: 'application/json',
            success: function (response) {
                alert('Checkout successful!');
                sessionStorage.setItem('cart', JSON.stringify([]));
                displayCart([]);
                updateCartCount();
            },
            error: function (xhr, status, error) {
                console.error('Checkout failed:', status, error);
            }
        });
    });
});

function displayCart(cart) {
    $('#cartTableBody').empty();
    let totalQuantity = 0;
    let totalPrice = 0;

    if (Array.isArray(cart) && cart.length > 0) {
        cart.forEach((item, index) => {
            const itemTotal = item.quantity * item.price;
            totalQuantity += item.quantity;
            totalPrice += itemTotal;

            $('#cartTableBody').append(`
                <tr>
                    <td>${item.product_name}</td>
                    <td><img src="/images/${item.image}" alt="${item.product_name}" width="50"></td>
                    <td>
                        <button class="btn btn-sm btn-secondary" onclick="changeQuantity(${index}, -1)">-</button>
                        ${item.quantity}
                        <button class="btn btn-sm btn-secondary" onclick="changeQuantity(${index}, 1)">+</button>
                    </td>
                    <td>${itemTotal.toFixed(2)}</td>
                    <td>
                        <button class="btn btn-danger" onclick="removeFromCart(${index})">Remove</button>
                    </td>
                </tr>
            `);
        });

        $('#cartTableBody').append(`
            <tr>
                <td colspan="2"><strong>Total</strong></td>
                <td><strong>${totalQuantity}</strong></td>
                <td><strong>$${totalPrice.toFixed(2)}</strong></td>
                <td colspan="2"></td>
            </tr>
        `);
    } else {
        $('#cartTableBody').append('<tr><td colspan="7" class="text-center">No items in the cart.</td></tr>');
    }
}

function updateCartCount() {
    let cart = JSON.parse(sessionStorage.getItem('cart')) || [];
    let totalItems = cart.reduce((acc, item) => acc + item.quantity, 0);
    $('#cartCount').text(totalItems);
}

function changeQuantity(index, change) {
    let cart = JSON.parse(sessionStorage.getItem('cart')) || [];
    if (cart[index].quantity + change > 0) {
        cart[index].quantity += change;
        sessionStorage.setItem('cart', JSON.stringify(cart));
        displayCart(cart);
        updateCartCount();
    }
}

function removeFromCart(index) {
    let cart = JSON.parse(sessionStorage.getItem('cart')) || [];
    cart.splice(index, 1);
    sessionStorage.setItem('cart', JSON.stringify(cart));
    displayCart(cart);
    updateCartCount();
}

function addToCart(productId) {
    let cart = JSON.parse(sessionStorage.getItem('cart')) || [];
    const product = products.find(p => p.id == productId);
    if (product) {
        const existingProduct = cart.find(p => p.id == productId);
        if (existingProduct) {
            existingProduct.quantity += 1;
        } else {
            product.quantity = 1;
            product.username = '{{ Auth::user()->name }}';
            product.email = '{{ Auth::user()->email }}';
            cart.push(product);
        }
        sessionStorage.setItem('cart', JSON.stringify(cart));
        updateCartCount();
    } else {
        console.error('Product not found:', productId);
    }
}
