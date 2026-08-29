// Utility untuk mengelola IndexedDB dan Background Sync Form Offline
const IDB_NAME = 'DesaOfflineDB';
const IDB_VERSION = 1;
const STORE_NAME = 'offline_forms';

// Buka database
function openDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open(IDB_NAME, IDB_VERSION);
        request.onerror = () => reject(request.error);
        request.onsuccess = () => resolve(request.result);
        request.onupgradeneeded = (e) => {
            const db = e.target.result;
            if (!db.objectStoreNames.contains(STORE_NAME)) {
                db.createObjectStore(STORE_NAME, { keyPath: 'id', autoIncrement: true });
            }
        };
    });
}

// Simpan data form ke IDB
async function saveOfflineForm(url, formDataObj, redirectUrl) {
    const db = await openDB();
    const tx = db.transaction(STORE_NAME, 'readwrite');
    const store = tx.objectStore(STORE_NAME);
    
    // Convert FormData to Object arrays for IDB storage since FormData itself cannot be stored directly
    // Wait, File objects CAN be stored in IndexedDB.
    const requestData = {
        url: url,
        formData: formDataObj, // We will pass an object containing {key: value} where value can be File[]
        redirectUrl: redirectUrl,
        timestamp: new Date().getTime()
    };
    
    return new Promise((resolve, reject) => {
        const req = store.add(requestData);
        req.onsuccess = () => resolve();
        req.onerror = () => reject(req.error);
    });
}

// Ekstrak data form ke objek (mendukung file multiple)
async function extractFormData(formElement) {
    const formData = new FormData(formElement);
    const obj = {};
    for (const [key, value] of formData.entries()) {
        if (value instanceof File) {
            if (!obj[key]) obj[key] = [];
            // Only add if file is selected (size > 0)
            if (value.size > 0) {
                obj[key].push(value);
            }
        } else {
            obj[key] = value;
        }
    }
    return obj;
}

// Inisiasi Autosave LocalStorage
function initAutosave(formId, storageKey) {
    const form = document.getElementById(formId);
    if (!form) return;

    // Load saved data
    const saved = localStorage.getItem(storageKey);
    if (saved) {
        try {
            const data = JSON.parse(saved);
            for (const key in data) {
                const input = form.querySelector(`[name="${key}"]`);
                if (input && input.type !== 'file' && input.type !== 'hidden') {
                    if (input.type === 'checkbox' || input.type === 'radio') {
                        if (input.value == data[key]) input.checked = true;
                    } else {
                        input.value = data[key];
                    }
                }
            }
        } catch (e) {
            console.error('Error loading autosave', e);
        }
    }

    // Save inputs on change
    form.addEventListener('input', (e) => {
        if (e.target.type === 'file' || e.target.type === 'hidden') return;
        
        const currentData = JSON.parse(localStorage.getItem(storageKey) || '{}');
        if (e.target.type === 'checkbox') {
             currentData[e.target.name] = e.target.checked ? e.target.value : '';
        } else {
             currentData[e.target.name] = e.target.value;
        }
        localStorage.setItem(storageKey, JSON.stringify(currentData));
    });
}

// Intercept form submission
function handleFormSubmitOffline(formId, storageKey, apiEndpoint, redirectUrl, syncTag) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        if (!navigator.onLine) {
            e.preventDefault();
            
            // Tampilkan loading dari sweetalert
            Swal.fire({
                title: 'Sedang Offline',
                text: 'Memproses data untuk disimpan secara offline...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const formDataObj = await extractFormData(form);
                await saveOfflineForm(apiEndpoint, formDataObj, redirectUrl);
                
                // Daftarkan Background Sync
                if ('serviceWorker' in navigator && 'SyncManager' in window) {
                    const registration = await navigator.serviceWorker.ready;
                    await registration.sync.register(syncTag);
                    
                    Swal.fire({
                        icon: 'info',
                        title: 'Tersimpan Offline!',
                        text: 'Anda sedang offline. Laporan ini telah disimpan di peramban dan akan OTOMATIS terkirim ketika internet terhubung kembali.',
                        confirmButtonText: 'Mengerti'
                    }).then(() => {
                        localStorage.removeItem(storageKey);
                        window.location.href = redirectUrl;
                    });
                } else {
                    Swal.fire('Info', 'Browser tidak mendukung Background Sync. Silakan tekan Kirim lagi saat internet tersedia.', 'warning');
                }
            } catch (err) {
                console.error('Offline save error:', err);
                Swal.fire('Error', 'Gagal menyimpan data offline.', 'error');
            }
        } else {
            // Jika online, biarkan form berjalan normal dan hapus draft
            localStorage.removeItem(storageKey);
            
            // Sweetalert loading dihapus untuk mencegah bug loading macet (stuck screen)
        }
    });
}
