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

    $.ajax({
        url: '/api/orders', // Your API endpoint
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + token
        },
        success: function(data) {
            // Prepare data for the pie chart
            const productNames = data.map(order => order.product_name);
            const quantities = data.map(order => order.total_quantity);

            // Create a new Chart.js instance
            const ctx = document.getElementById('productPieChart').getContext('2d');

            if (!ctx) {
                console.error('Could not find canvas context');
                return;
            }

            new Chart(ctx, {
                type: 'pie', // Pie chart type
                data: {
                    labels: productNames,
                    datasets: [{
                        label: 'Most Sold Products',
                        data: quantities,
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return `${tooltipItem.label}: ${tooltipItem.raw} units`;
                                }
                            }
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
