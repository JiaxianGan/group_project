// Search Functionality
function handleSearch() {
    const searchInput = document.querySelector('.search-container input');
    const searchButton = document.querySelector('.search-container button');
    searchButton.addEventListener('click', () => {
        const query = searchInput.value;
        // Perform AJAX request to search endpoint
        fetch(`/search?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => console.log('Search results:', data));
    });
}

// User Management
function handleUserActions() {
    document.querySelectorAll('.edit-user').forEach(button => {
        button.addEventListener('click', () => {
            const userId = button.dataset.userId;
            console.log('Edit user:', userId);
            // Open edit modal or send AJAX request
        });
    });
    document.querySelectorAll('.delete-user').forEach(button => {
        button.addEventListener('click', () => {
            const userId = button.dataset.userId;
            console.log('Delete user:', userId);
            // Confirm and send AJAX request to delete user
        });
    });
}

// Role Management
function handleRoleChange() {
    const roleForm = document.querySelector('#role-form');
    roleForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const formData = new FormData(roleForm);
        fetch('/change-role', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => console.log('Role change response:', data));
    });
}

// System Overview
function updateSystemOverview() {
    fetch('/system-status')
        .then(response => response.json())
        .then(data => {
            document.getElementById('system-status').textContent = data.status;
            console.log('System status:', data);
        });
}

// Recent Orders
function handleOrderView() {
    document.querySelectorAll('.view-order').forEach(button => {
        button.addEventListener('click', () => {
            const orderId = button.dataset.orderId;
            console.log('View order:', orderId);
            // Open order details modal or fetch order details
        });
    });
}

// Product Approval
function handleProductApproval() {
    document.querySelectorAll('.approve-product').forEach(button => {
        button.addEventListener('click', () => {
            const productId = button.dataset.productId;
            console.log('Approve product:', productId);
            // Send AJAX request to approve product
        });
    });
    document.querySelectorAll('.reject-product').forEach(button => {
        button.addEventListener('click', () => {
            const productId = button.dataset.productId;
            console.log('Reject product:', productId);
            // Send AJAX request to reject product
        });
    });
}

// Dynamic Data Loading
function loadDynamicData() {
    fetch('/dashboard-data')
        .then(response => response.json())
        .then(data => {
            console.log('Dynamic data:', data);
            // Update dashboard with dynamic data
        });
}

// Initialize all functions
document.addEventListener('DOMContentLoaded', () => {
    handleSearch();
    handleUserActions();
    handleRoleChange();
    updateSystemOverview();
    handleOrderView();
    handleProductApproval();
    loadDynamicData();
}); 