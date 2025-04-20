document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips and popovers
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // NEW: Check for URL query parameters to display alerts
    const urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('message');
    const type = urlParams.get('type');
    if (message && type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.querySelector('.dashboard-container').prepend(alertDiv);
        // Clear URL parameters
        window.history.replaceState({}, document.title, window.location.pathname);
    }
    // Show loading modal
    function showLoading(message = 'Loading data...') {
        document.getElementById('loading-message').textContent = message;
        const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
        loadingModal.show();
        return loadingModal;
    }

    // Show toast notification
    function showToast(message, type = 'success') {
        const toastContainer = document.getElementById('toastContainer');
        const toastId = 'toast-' + Date.now();
        const iconClass = type === 'success' ? 'fa-check-circle' : 
                         type === 'error' ? 'fa-exclamation-circle' : 
                         type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';
        
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-white bg-${type}" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas ${iconClass} me-2"></i> ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 5000 });
        toast.show();
        
        toastElement.addEventListener('hidden.bs.toast', function() {
            toastElement.remove();
        });
    }

    // Format date
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString();
    }

    // ======================================
    // USER MANAGEMENT FUNCTIONALITY
    // ======================================

    // Add User Modal
    const addUserBtn = document.getElementById('addUserBtn');
    if (addUserBtn) {
        addUserBtn.addEventListener('click', function() {
            const addUserModal = new bootstrap.Modal(document.getElementById('addUserModal'));
            addUserModal.show();
        });
    }

    // Edit User Modal
    const editButtons = document.querySelectorAll('.edit-user');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const username = this.getAttribute('data-username');
            const email = this.getAttribute('data-email') || '';
            const phone = this.getAttribute('data-phone') || '';
            const role = this.getAttribute('data-role');

            document.getElementById('edit-user-id').value = userId;
            document.getElementById('edit-username').value = username;
            document.getElementById('edit-email').value = email;
            document.getElementById('edit-phone').value = phone;
            document.getElementById('edit-role').value = role;

            const editUserModal = new bootstrap.Modal(document.getElementById('editUserModal'));
            editUserModal.show();
        });
    });

    // Delete User Confirmation
    const deleteButtons = document.querySelectorAll('.delete-user');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    let userIdToDelete = null;
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            userIdToDelete = this.getAttribute('data-user-id');
            const username = this.closest('tr').cells[1].textContent;
            
            document.getElementById('delete-username').textContent = username;
            document.getElementById('delete-user-id').value = userIdToDelete;
            
            const deleteConfirmModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            deleteConfirmModal.show();
        });
    });

    // CHANGE: Modified confirmDeleteBtn to submit form
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            document.getElementById('delete-user-form').submit();
        });
    }

    // Reset Password Modal
    const resetPasswordButtons = document.querySelectorAll('.reset-password');
    resetPasswordButtons.forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const username = this.closest('tr').cells[1].textContent;
            
            document.getElementById('reset-user-id').value = userId;
            document.getElementById('reset-username').value = username;
            
            const resetPasswordModal = new bootstrap.Modal(document.getElementById('resetPasswordModal'));
            resetPasswordModal.show();
        });
    });

    // User search functionality
    const userSearchInput = document.getElementById('userSearchInput');
    if (userSearchInput) {
        userSearchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const userRows = document.querySelectorAll('#usersTable tbody tr');
            
            userRows.forEach(row => {
                const username = row.cells[1].textContent.toLowerCase();
                const role = row.cells[2].textContent.toLowerCase();
                
                if (username.includes(searchTerm) || role.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Add user to table
    function addUserToTable(user) {
        const tableBody = document.querySelector('#usersTable tbody');
        
        const newRow = document.createElement('tr');
        newRow.setAttribute('data-user-id', user.user_id);
        
        // CHANGE: Updated table row to exclude status column
        newRow.innerHTML = `
            <td>${user.user_id}</td>
            <td>${user.username}</td>
            <td>${user.email}</td>
            <td>${user.phone}</td>
            <td>${user.role}</td>
            <td>${user.last_login}</td>
            <td>
                <button class="btn btn-sm btn-primary edit-user" 
                        data-user-id="${user.user_id}"
                        data-username="${user.username}"
                        data-email="${user.email}"
                        data-phone="${user.phone}"
                        data-role="${user.role}">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger delete-user" data-user-id="${user.user_id}">
                    <i class="fas fa-trash"></i>
                </button>
                <button class="btn btn-sm btn-info reset-password" data-user-id="${user.user_id}">
                    <i class="fas fa-key"></i>
                </button>
            </td>
        `;
        
        tableBody.prepend(newRow);
        
        // Add event listeners to new buttons
        newRow.querySelector('.edit-user').addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const username = this.getAttribute('data-username');
            const email = this.getAttribute('data-email');
            const phone = this.getAttribute('data-phone');
            const role = this.getAttribute('data-role');

            document.getElementById('edit-user-id').value = userId;
            document.getElementById('edit-username').value = username;
            document.getElementById('edit-email').value = email;
            document.getElementById('edit-phone').value = phone;
            document.getElementById('edit-role').value = role;

            const editUserModal = new bootstrap.Modal(document.getElementById('editUserModal'));
            editUserModal.show();
        });
        
        newRow.querySelector('.delete-user').addEventListener('click', function() {
            userIdToDelete = this.getAttribute('data-user-id');
            const username = this.closest('tr').cells[1].textContent;
            
            document.getElementById('delete-username').textContent = username;
            
            const deleteConfirmModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            deleteConfirmModal.show();
        });
        
        newRow.querySelector('.reset-password').addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const username = this.closest('tr').cells[1].textContent;
            
            document.getElementById('reset-user-id').value = userId;
            document.getElementById('reset-username').value = username;
            
            const resetPasswordModal = new bootstrap.Modal(document.getElementById('resetPasswordModal'));
            resetPasswordModal.show();
        });
    }
    
    // Update user count
    function updateUserCount(change) {
        const totalUsersElement = document.getElementById('total-users');
        if (totalUsersElement) {
            const currentCount = parseInt(totalUsersElement.textContent, 10);
            totalUsersElement.textContent = currentCount + change;
        }
    }
    // ======================================
    // SYSTEM OVERVIEW FUNCTIONALITY
    // ======================================
    
    // Refresh system status
    const refreshSystemStatusBtn = document.getElementById('refreshSystemStatus');
    if (refreshSystemStatusBtn) {
        refreshSystemStatusBtn.addEventListener('click', function() {
            updateSystemStatus();
        });
    }
    
    // Update system status
    function updateSystemStatus() {
        const loadingModal = showLoading('Fetching system status...');
        
        setTimeout(function() {
            // Simulate random values for demonstration
            const cpuUsage = Math.floor(Math.random() * 70) + 10; // 10-80%
            const memoryUsage = Math.floor(Math.random() * 60) + 30; // 30-90%
            const diskUsage = Math.floor(Math.random() * 50) + 10; // 10-60%
            
            // Update CPU progress
            document.getElementById('cpu-usage').textContent = cpuUsage + '%';
            const cpuProgress = document.getElementById('cpu-progress');
            cpuProgress.style.width = cpuUsage + '%';
            
            if (cpuUsage < 50) {
                cpuProgress.className = 'progress-bar bg-success';
            } else if (cpuUsage < 80) {
                cpuProgress.className = 'progress-bar bg-warning';
            } else {
                cpuProgress.className = 'progress-bar bg-danger';
            }
            
            // Update Memory progress
            document.getElementById('memory-usage').textContent = memoryUsage + '%';
            const memoryProgress = document.getElementById('memory-progress');
            memoryProgress.style.width = memoryUsage + '%';
            
            if (memoryUsage < 60) {
                memoryProgress.className = 'progress-bar bg-success';
            } else if (memoryUsage < 85) {
                memoryProgress.className = 'progress-bar bg-warning';
            } else {
                memoryProgress.className = 'progress-bar bg-danger';
            }
            
            // Update Disk progress
            document.getElementById('disk-usage').textContent = diskUsage + '%';
            const diskProgress = document.getElementById('disk-progress');
            diskProgress.style.width = diskUsage + '%';
            
            if (diskUsage < 70) {
                diskProgress.className = 'progress-bar bg-success';
            } else if (diskUsage < 90) {
                diskProgress.className = 'progress-bar bg-warning';
            } else {
                diskProgress.className = 'progress-bar bg-danger';
            }
            
            // Update last updated time
            document.getElementById('last-updated').textContent = new Date().toLocaleString();
            
            // Update system status
            const systemStatus = document.getElementById('system-status');
            if (cpuUsage > 80 || memoryUsage > 90) {
                systemStatus.textContent = 'Warning';
                systemStatus.className = 'badge bg-warning';
            } else {
                systemStatus.textContent = 'Online';
                systemStatus.className = 'badge bg-success';
            }
            
            // Update active users
            const activeUsers = Math.floor(Math.random() * 20) + 15; // 15-35
            document.getElementById('active-users').textContent = activeUsers;
            document.getElementById('active-users-count').textContent = activeUsers;
            
            // Update server load
            const serverLoad = (cpuUsage > 70) ? 'High' : (cpuUsage > 40) ? 'Medium' : 'Low';
            const serverLoadElement = document.getElementById('server-load');
            serverLoadElement.textContent = serverLoad;
            
            if (serverLoad === 'High') {
                serverLoadElement.className = 'badge bg-danger rounded-pill';
            } else if (serverLoad === 'Medium') {
                serverLoadElement.className = 'badge bg-warning rounded-pill';
            } else {
                serverLoadElement.className = 'badge bg-success rounded-pill';
            }
            
            // Update database status
            const dbStatus = (Math.random() > 0.05) ? 'Connected' : 'Error';
            const dbStatusElement = document.getElementById('db-status');
            dbStatusElement.textContent = dbStatus;
            dbStatusElement.className = (dbStatus === 'Connected') ? 'badge bg-success rounded-pill' : 'badge bg-danger rounded-pill';
            
            // Update system metrics chart
            updateSystemMetricsChart(cpuUsage, memoryUsage, diskUsage);
            
            // Update system logs
            updateSystemLogs();
            
            loadingModal.hide();
            showToast('System status updated', 'success');
        }, 1000);
    }
    
    // System metrics chart
    let systemMetricsChart;
    function initSystemMetricsChart() {
        const ctx = document.getElementById('systemMetricsChart');
        if (!ctx) return;
        
        systemMetricsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: getLast24Hours(),
                datasets: [
                    {
                        label: 'CPU Usage',
                        data: generateRandomData(24, 10, 80),
                        borderColor: 'rgba(40, 167, 69, 1)',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Memory Usage',
                        data: generateRandomData(24, 30, 90),
                        borderColor: 'rgba(255, 193, 7, 1)',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Disk Usage',
                        data: generateRandomData(24, 10, 60),
                        borderColor: 'rgba(23, 162, 184, 1)',
                        backgroundColor: 'rgba(23, 162, 184, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
    
    // Update system metrics chart
    function updateSystemMetricsChart(cpuUsage, memoryUsage, diskUsage) {
        if (!systemMetricsChart) return;
        
        // Shift data points left and add new values
        systemMetricsChart.data.datasets[0].data.shift();
        systemMetricsChart.data.datasets[0].data.push(cpuUsage);
        
        systemMetricsChart.data.datasets[1].data.shift();
        systemMetricsChart.data.datasets[1].data.push(memoryUsage);
        
        systemMetricsChart.data.datasets[2].data.shift();
        systemMetricsChart.data.datasets[2].data.push(diskUsage);
        
        // Update chart labels
        systemMetricsChart.data.labels.shift();
        const now = new Date();
        systemMetricsChart.data.labels.push(now.getHours() + ':' + (now.getMinutes() < 10 ? '0' : '') + now.getMinutes());
        
        systemMetricsChart.update();
    }
    
    // System logs
    function updateSystemLogs() {
        const tableBody = document.querySelector('#systemLogsTable tbody');
        if (!tableBody) return;
        
        // Clear existing logs (optional)
        // tableBody.innerHTML = '';
        
        // Add new log entries
        const logTypes = ['info', 'warning', 'error'];
        const logSources = ['System', 'Database', 'User Auth', 'Application'];
        const logMessages = [
            'System startup complete',
            'Database connection established',
            'User login successful',
            'User logout',
            'Failed login attempt',
            'Memory usage exceeds threshold',
            'CPU load high',
            'Database query timeout',
            'Cache cleared',
            'Scheduled maintenance started',
            'Security scan completed',
            'File upload successful',
            'File download'
        ];
        
        // Simulate new log entries (1-3 new entries)
        const newEntriesCount = Math.floor(Math.random() * 3) + 1;
        
        for (let i = 0; i < newEntriesCount; i++) {
            const logType = logTypes[Math.floor(Math.random() * logTypes.length)];
            const logSource = logSources[Math.floor(Math.random() * logSources.length)];
            const logMessage = logMessages[Math.floor(Math.random() * logMessages.length)];
            
            const now = new Date();
            const timeStr = now.toLocaleTimeString();
            
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${timeStr}</td>
                <td><span class="badge bg-${logType === 'info' ? 'info' : logType === 'warning' ? 'warning' : 'danger'}">${logType}</span></td>
                <td>${logMessage}</td>
                <td>${logSource}</td>
            `;
            
            tableBody.prepend(newRow);
            
            // Keep only the latest 10 log entries
            if (tableBody.children.length > 10) {
                tableBody.removeChild(tableBody.lastChild);
            }
        }
    }
    
    // Log filter functionality
    const logFilter = document.getElementById('log-filter');
    if (logFilter) {
        logFilter.addEventListener('change', function() {
            const filterValue = this.value;
            const logRows = document.querySelectorAll('#systemLogsTable tbody tr');
            
            logRows.forEach(row => {
                const logType = row.cells[1].textContent.toLowerCase();
                
                if (filterValue === 'all' || logType === filterValue) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // ======================================
    // DYNAMIC DATA FUNCTIONALITY
    // ======================================
    
    // Sales chart
    let salesChart;
    function initSalesChart() {
        const ctx = document.getElementById('salesChart');
        if (!ctx) return;
        
        salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Sales',
                    data: generateRandomData(12, 5000, 15000),
                    backgroundColor: 'rgba(40, 167, 69, 0.7)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
    
    // Login traffic chart
    let loginTrafficChart;
    function initLoginTrafficChart() {
        const ctx = document.getElementById('loginTrafficChart');
        if (!ctx) return;
        
        loginTrafficChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: getLast7Days(),
                datasets: [{
                    label: 'Logins',
                    data: generateRandomData(7, 10, 50),
                    borderColor: 'rgba(13, 110, 253, 1)',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
    
    // User roles chart
    let userRolesChart;
    function initUserRolesChart() {
        const ctx = document.getElementById('userRolesChart');
        if (!ctx) return;
        
        userRolesChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Admin', 'Vendor', 'Staff', 'User'],
                datasets: [{
                    data: [4, 8, 15, 30],
                    backgroundColor: [
                        'rgba(220, 53, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(23, 162, 184, 0.8)',
                        'rgba(40, 167, 69, 0.8)'
                    ],
                    borderColor: [
                        'rgba(220, 53, 69, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(23, 162, 184, 1)',
                        'rgba(40, 167, 69, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'right',
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
    
    // Helper functions for generating random data and time labels
    function generateRandomData(count, min, max) {
        const data = [];
        for (let i = 0; i < count; i++) {
            data.push(Math.floor(Math.random() * (max - min + 1)) + min);
        }
        return data;
    }
    
    function getLast7Days() {
        const days = [];
        for (let i = 6; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            days.push(date.toLocaleDateString('en-US', { weekday: 'short' }));
        }
        return days;
    }
    
    function getLast24Hours() {
        const hours = [];
        const now = new Date();
        const currentHour = now.getHours();
        
        for (let i = 23; i >= 0; i--) {
            const hour = (currentHour - i + 24) % 24;
            hours.push(hour + ':00');
        }
        return hours;
    }
    
    // Initialize charts if they exist on the page
    if (document.getElementById('systemMetricsChart')) initSystemMetricsChart();
    if (document.getElementById('salesChart')) initSalesChart();
    if (document.getElementById('loginTrafficChart')) initLoginTrafficChart();
    if (document.getElementById('userRolesChart')) initUserRolesChart();
    
    // Initial system status update if on system dashboard
    if (document.getElementById('refreshSystemStatus')) {
        updateSystemStatus();
    }
    
    // ======================================
    // SETTINGS FUNCTIONALITY
    // ======================================
    
    // Save settings form
    const settingsForm = document.getElementById('settings-form');
    if (settingsForm) {
        settingsForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Simulate saving settings
            const loadingModal = showLoading('Saving settings...');
            
            setTimeout(function() {
                loadingModal.hide();
                showToast('Settings saved successfully', 'success');
            }, 1500);
        });
    }
    
    // Toggle system maintenance mode
    const maintenanceSwitch = document.getElementById('maintenance-mode');
    if (maintenanceSwitch) {
        maintenanceSwitch.addEventListener('change', function() {
            const isEnabled = this.checked;
            const message = isEnabled ? 'Enabling maintenance mode...' : 'Disabling maintenance mode...';
            
            const loadingModal = showLoading(message);
            
            setTimeout(function() {
                loadingModal.hide();
                showToast(`Maintenance mode ${isEnabled ? 'enabled' : 'disabled'}`, isEnabled ? 'warning' : 'success');
                
                // Update maintenance status in UI if present
                const maintenanceStatus = document.getElementById('maintenance-status');
                if (maintenanceStatus) {
                    maintenanceStatus.textContent = isEnabled ? 'Enabled' : 'Disabled';
                    maintenanceStatus.className = isEnabled ? 'badge bg-warning' : 'badge bg-success';
                }
            }, 1500);
        });
    }
    
    // Reset application cache
    const resetCacheBtn = document.getElementById('reset-cache');
    if (resetCacheBtn) {
        resetCacheBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to clear the application cache?')) {
                const loadingModal = showLoading('Clearing cache...');
                
                setTimeout(function() {
                    loadingModal.hide();
                    showToast('Application cache cleared successfully', 'success');
                }, 2000);
            }
        });
    }
    
    // Export system logs
    const exportLogsBtn = document.getElementById('export-logs');
    if (exportLogsBtn) {
        exportLogsBtn.addEventListener('click', function() {
            const loadingModal = showLoading('Preparing logs export...');
            
            setTimeout(function() {
                loadingModal.hide();
                
                // Simulate file download by creating and clicking a temporary link
                const tempLink = document.createElement('a');
                tempLink.href = 'data:text/plain;charset=utf-8,' + encodeURIComponent('System Logs Export\n' + new Date().toISOString() + '\n\nThis is a simulated log export file.');
                tempLink.setAttribute('download', 'system_logs_' + new Date().toISOString().split('T')[0] + '.txt');
                tempLink.style.display = 'none';
                document.body.appendChild(tempLink);
                tempLink.click();
                document.body.removeChild(tempLink);
                
                showToast('System logs exported successfully', 'success');
            }, 2000);
        });
    }
    
    // ======================================
    // NOTIFICATIONS FUNCTIONALITY
    // ======================================
    
    // Mark all notifications as read
    const markAllReadBtn = document.getElementById('mark-all-read');
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function() {
            const unreadNotifications = document.querySelectorAll('.notification-item.unread');
            unreadNotifications.forEach(notification => {
                notification.classList.remove('unread');
                notification.classList.add('read');
            });
            
            // Update notification count
            const notificationCount = document.getElementById('notification-count');
            if (notificationCount) {
                notificationCount.textContent = '0';
            }
            
            showToast('All notifications marked as read', 'success');
        });
    }
    
    // Delete notification
    const deleteNotificationBtns = document.querySelectorAll('.delete-notification');
    deleteNotificationBtns.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const notificationItem = this.closest('.notification-item');
            
            notificationItem.style.animation = 'fadeOut 0.3s';
            setTimeout(function() {
                notificationItem.remove();
                
                // Update notification count if it was unread
                if (notificationItem.classList.contains('unread')) {
                    const notificationCount = document.getElementById('notification-count');
                    if (notificationCount) {
                        const currentCount = parseInt(notificationCount.textContent, 10);
                        notificationCount.textContent = Math.max(0, currentCount - 1);
                    }
                }
                
                showToast('Notification deleted', 'success');
            }, 300);
        });
    });
    
    // ======================================
    // APPLICATION INITIALIZATION
    // ======================================
    
    // Initialize page
    function initializePage() {
        // CHANGE: Removed call to updateDashboardStats to disable system status updating
        // REMOVED: if (document.getElementById('dashboard-stats')) {
        // REMOVED:     updateDashboardStats();
        // REMOVED: }
        
        const datepickers = document.querySelectorAll('.datepicker');
        datepickers.forEach(input => {
            input.setAttribute('placeholder', 'Select date');
        });
        
        const dataTables = document.querySelectorAll('.datatable');
        dataTables.forEach(table => {
            table.classList.add('table-striped');
        });
    }
    
    // Update dashboard statistics
    /*function updateDashboardStats() {
        // Simulate API call to get latest dashboard stats
        setTimeout(function() {
            // Update statistics
            document.getElementById('total-users').textContent = Math.floor(Math.random() * 100) + 50;
            document.getElementById('active-users-count').textContent = Math.floor(Math.random() * 30) + 15;
            document.getElementById('daily-sales').textContent = '$' + (Math.floor(Math.random() * 10000) + 1000).toLocaleString();
            document.getElementById('new-orders').textContent = Math.floor(Math.random() * 20) + 5;
            
            // Show a subtle notification
            showToast('Dashboard statistics updated', 'info');
        }, 1000);
    }*/
    
    // Initialize page
    initializePage();
    
    // Simulate periodic updates (for demo purposes)
    /*setInterval(function() {
        if (document.getElementById('systemMetricsChart')) {
            // Only update system metrics if chart exists and is visible
            const cpuUsage = Math.floor(Math.random() * 70) + 10;
            const memoryUsage = Math.floor(Math.random() * 60) + 30;
            const diskUsage = Math.floor(Math.random() * 50) + 10;
            
            updateSystemMetricsChart(cpuUsage, memoryUsage, diskUsage);
            updateSystemLogs();
        }
    }, 30000); // Update every 30 seconds*/
});
