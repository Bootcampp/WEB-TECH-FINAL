document.addEventListener('DOMContentLoaded', function() {
    // Optional: Add any dynamic interactions or client-side enhancements

    // Example: Highlight key metrics
    function highlightKeyMetrics() {
        const totalDesigns = document.getElementById('total-designs');
        const totalSold = document.getElementById('total-sold');
        const totalRevenue = document.getElementById('total-revenue');

        // Add visual indicator for performance
        if (parseInt(totalSold.textContent) > 0) {
            totalSold.classList.add('positive-metric');
        }

        if (parseFloat(totalRevenue.textContent.replace('$', '')) > 0) {
            totalRevenue.classList.add('revenue-metric');
        }
    }

    // Example: Periodic data refresh (optional)
    function refreshAnalytics() {
        // Could implement AJAX call to refresh analytics data
        // This is a placeholder for potential future implementation
        console.log('Analytics refresh triggered');
    }

    // Example: Sales table hover effect
    function enhanceSalesTable() {
        const salesTable = document.getElementById('sales-table');
        if (salesTable) {
            salesTable.addEventListener('mouseover', function(e) {
                if (e.target.tagName === 'TD') {
                    e.target.parentElement.classList.add('row-highlight');
                }
            });

            salesTable.addEventListener('mouseout', function(e) {
                if (e.target.tagName === 'TD') {
                    e.target.parentElement.classList.remove('row-highlight');
                }
            });
        }
    }

    // Initialize functions
    highlightKeyMetrics();
    enhanceSalesTable();

    // Optional: Add event listeners or additional interactions
});

// Optional error tracking
window.addEventListener('error', function(event) {
    console.error('An error occurred:', event.error);
    // Could send error to server-side logging
});