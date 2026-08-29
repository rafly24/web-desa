<!-- Notification Button -->
<button id="enableNotificationBtn" class="btn btn-primary d-flex align-items-center gap-2">
    <i class="bi bi-bell"></i>
    <span>Aktifkan Notifikasi</span>
</button>

<div id="notificationStatus" style="display:none;" class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2">
    <i class="bi bi-bell-fill"></i>
    <span>Notifikasi aktif</span>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const enableBtn = document.getElementById('enableNotificationBtn');
    const statusDiv = document.getElementById('notificationStatus');

    // Check if notification already enabled
    if (isNotificationEnabled && isNotificationEnabled()) {
        statusDiv.style.display = 'flex';
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            if (statusDiv) {
                statusDiv.style.display = 'none';
            }
        }, 5000);
    } else {
        enableBtn.style.display = 'flex';
    }

    // Handle button click
    enableBtn.addEventListener('click', async function() {
        try {
            await requestNotificationPermission();
            
            // Update UI
            enableBtn.style.display = 'none';
            statusDiv.style.display = 'flex';
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                if (statusDiv) {
                    statusDiv.style.display = 'none';
                }
            }, 5000);
            
            // Show success message
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Notifikasi berhasil diaktifkan. Anda akan mendapat pemberitahuan untuk update penting.',
                    timer: 3000,
                    showConfirmButton: false
                });
            }
        } catch (error) {
            console.error('Error enabling notification:', error);
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Gagal mengaktifkan notifikasi. Pastikan browser Anda mendukung notifikasi.',
                });
            }
        }
    });
});
</script>

<style>
#enableNotificationBtn {
    position: fixed;
    bottom: 80px;
    left: 20px;
    z-index: 998;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    animation: pulse 2s infinite;
}

#notificationStatus {
    position: fixed;
    top: 80px;
    right: 20px;
    z-index: 999;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin: 0;
    max-width: 300px;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

@media (max-width: 768px) {
    #enableNotificationBtn {
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
    }
    
    #notificationStatus {
        top: 70px;
        right: 10px;
        left: 10px;
        max-width: calc(100% - 20px);
    }
}
</style>
