$(document).ready(function() {
    const token = localStorage.getItem('token');

    if (!token) {
        console.error('Token missing or expired');
        return;
    }

    $.ajax({
        url: '/api/orders', // Your endpoint
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + token
        },
        success: function(data) {
            const labels = data.map(order => order.product_name);
            const quantities = data.map(order => order.total_quantity);
            const sales = data.map(order => order.total_sales);

            const ctx = document.getElementById('salesChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar', // Change to 'line' or other types as needed
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Quantity',
                        data: quantities,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Total Sales',
                        data: sales,
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('Chart data fetch failed:', status, error);
            alert('Failed to load chart data. Please try again later.');
        }
    });
});
